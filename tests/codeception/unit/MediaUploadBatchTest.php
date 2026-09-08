<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2026 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\tests\codeception\unit;

use humhub\modules\content\models\Content;
use humhub\modules\content\notifications\ContentCreated;
use humhub\modules\gallery\jobs\SendMediaUploadNotification;
use humhub\modules\gallery\libs\MediaUploadBatch;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\Media;
use humhub\modules\gallery\Module;
use humhub\modules\gallery\notifications\MediasUploaded;
use humhub\modules\notification\models\Notification;
use humhub\modules\queue\driver\Instant;
use humhub\modules\queue\driver\MySQL;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use tests\codeception\_support\HumHubDbTestCase;
use Yii;

/**
 * Uploading a set of images must not create one notification (and one e-mail) per image, but a
 * single one for the whole upload.
 *
 * @see MediaUploadBatch
 */
class MediaUploadBatchTest extends HumHubDbTestCase
{
    /**
     * Admin follows space 2 with notification settings, so uploads of User2 have exactly one
     * recipient. See the user_follow fixture.
     */
    private const SPACE_ID = 2;
    private const UPLOADER = 'User2';

    private CustomGallery $gallery;

    private int $uploaderId;

    protected function setUp(): void
    {
        parent::setUp();

        Yii::$app->db->createCommand()->truncateTable('queue')->execute();

        // The gallery itself is created while nothing is executed, so that its own content
        // created notification cannot interfere with the assertions below.
        $this->useDelayingQueue();

        $this->uploaderId = $this->becomeUser(self::UPLOADER)->id;
        $this->gallery = new CustomGallery(Space::findOne(self::SPACE_ID), Content::VISIBILITY_PUBLIC, ['title' => 'Batch Test Gallery']);
        $this->assertTrue($this->gallery->save());
    }

    /**
     * The default test driver is Instant, which runs jobs right away and ignores delay(). The
     * MySQL driver stores them instead, which is what a real installation uses and what lets a
     * whole upload be collected before the announcement job runs.
     */
    private function useDelayingQueue(): void
    {
        Yii::$app->set('queue', ['class' => MySQL::class]);
    }

    private function useInstantQueue(): void
    {
        Yii::$app->set('queue', ['class' => Instant::class]);
    }

    /**
     * @return Media[]
     */
    private function upload(int $count, ?CustomGallery $gallery = null): array
    {
        $gallery ??= $this->gallery;
        $media = [];

        for ($i = 1; $i <= $count; $i++) {
            $item = new Media(Space::findOne(self::SPACE_ID), Content::VISIBILITY_PUBLIC, [
                'gallery' => $gallery,
                'title' => 'batch-test-' . $i . '.jpg',
            ]);
            $this->assertTrue($item->save(), 'Media ' . $i . ' could not be saved');
            $media[] = $item;
        }

        return $media;
    }

    private function batch(?CustomGallery $gallery = null, ?int $userId = null): MediaUploadBatch
    {
        return MediaUploadBatch::load(($gallery ?? $this->gallery)->id, $userId ?? $this->uploaderId);
    }

    private function countQueuedBatchJobs(): int
    {
        return (int)Yii::$app->db
            ->createCommand('SELECT COUNT(*) FROM queue WHERE job LIKE :job', [':job' => '%SendMediaUploadNotification%'])
            ->queryScalar();
    }

    private function countNotifications(): int
    {
        return (int)Notification::find()->where(['class' => MediasUploaded::class])->count();
    }

    /**
     * Lets the quiet period expire without waiting for it.
     */
    private function expireQuietPeriod(): void
    {
        $batch = $this->batch();
        $batch->firstAt = time() - 100000;
        $batch->lastAt = time() - 100000;
        $batch->save();

        $this->assertSame(0, $this->batch()->getRemainingDelay());
    }

    private function runBatchJob(?int $attempt = 0): void
    {
        (new SendMediaUploadNotification([
            'galleryId' => $this->gallery->id,
            'userId' => $this->uploaderId,
            'attempt' => $attempt,
        ]))->run();
    }

    private function galleryModule(): Module
    {
        return Yii::$app->getModule('gallery');
    }

    public function testUploadedMediaDoesNotNotifyOnItsOwn()
    {
        $media = $this->upload(3);

        foreach ($media as $item) {
            $this->assertHasNoNotification(ContentCreated::class, $item);
        }

        $this->assertSame(0, $this->countNotifications(), 'Nothing may be announced before the quiet period expired');
    }

    public function testUploadsAreCollectedInASingleBatch()
    {
        $this->upload(5);

        $batch = $this->batch();
        $this->assertFalse($batch->isEmpty());
        $this->assertSame(5, $batch->count);
        $this->assertSame($this->gallery->id, $batch->galleryId);
        $this->assertSame($this->uploaderId, $batch->userId);
    }

    public function testOnlyTheFirstUploadSchedulesAJob()
    {
        $this->upload(5);

        $this->assertSame(1, $this->countQueuedBatchJobs(), '5 uploads must not queue 5 jobs');

        $delay = (int)Yii::$app->db
            ->createCommand('SELECT delay FROM queue WHERE job LIKE :job', [':job' => '%SendMediaUploadNotification%'])
            ->queryScalar();
        $this->assertSame(600, $delay, 'The job must be delayed by the configured 10 minutes');
    }

    public function testWholeUploadIsAnnouncedByOneNotificationAndOneMail()
    {
        $this->upload(5);
        $this->expireQuietPeriod();

        // From here on the notification targets have to run, as they would in a queue worker
        $this->useInstantQueue();
        $this->runBatchJob();

        $this->assertSame(1, $this->countNotifications(), '5 uploaded files must result in exactly one notification');
        $this->assertMailSent(1);

        $notification = Notification::find()->where(['class' => MediasUploaded::class])->one();
        $this->assertSame('{"mediaCount":5}', $notification->payload);
        $this->assertSame(User::findOne(['username' => self::UPLOADER])->id, $notification->originator_user_id);

        $this->assertTrue($this->batch()->isEmpty(), 'The batch must be closed after being announced');
    }

    public function testAnnouncedCountIsKeptWhenTheNotificationIsRenderedAgain()
    {
        $this->upload(4);
        $this->expireQuietPeriod();
        $this->useInstantQueue();
        $this->runBatchJob();

        // The count only survives in the stored payload, the notification list re-renders from it
        $notification = Notification::find()->where(['class' => MediasUploaded::class])->one();
        $rendered = $notification->getBaseModel();
        $rendered->getViewParams();

        $this->assertSame(4, $rendered->getMediaCount());
        $this->assertStringContainsString('4 media files', $rendered->html());
    }

    public function testASingleUploadIsAnnouncedInSingular()
    {
        $this->upload(1);
        $this->expireQuietPeriod();
        $this->useInstantQueue();
        $this->runBatchJob();

        $notification = Notification::find()->where(['class' => MediasUploaded::class])->one();
        $rendered = $notification->getBaseModel();
        $rendered->getViewParams();

        $html = $rendered->html();
        $this->assertStringContainsString('a media file', $html);
        $this->assertStringNotContainsString('1 media files', $html);
    }

    public function testEveryUploadRestartsTheQuietPeriod()
    {
        $this->upload(1);

        $batch = $this->batch();
        $batch->lastAt = time() - 550;
        $batch->save();
        $this->assertLessThanOrEqual(50, $this->batch()->getRemainingDelay());

        $this->upload(1);

        $this->assertGreaterThan(500, $this->batch()->getRemainingDelay(), 'A further upload must restart the quiet period');
    }

    public function testOngoingUploadsCannotPostponeTheNotificationForever()
    {
        $this->upload(1);

        $batch = $this->batch();
        // Still being uploaded into, but running since longer than the hard limit
        $batch->firstAt = time() - (MediaUploadBatch::getDelay() * MediaUploadBatch::MAX_POSTPONE_FACTOR) - 10;
        $batch->lastAt = time();
        $batch->save();

        $this->assertSame(0, $this->batch()->getRemainingDelay());
    }

    public function testJobRequeuesItselfWhileTheBatchIsNotDue()
    {
        $this->upload(2);
        $this->assertSame(1, $this->countQueuedBatchJobs());

        $this->runBatchJob();

        $this->assertSame(2, $this->countQueuedBatchJobs(), 'A job running too early must queue a new one');
        $this->assertSame(0, $this->countNotifications());
        $this->assertFalse($this->batch()->isEmpty(), 'The batch must stay open');
    }

    public function testJobDoesNothingWithoutAnOpenBatch()
    {
        $this->assertTrue($this->batch()->isEmpty());

        $this->useInstantQueue();
        $this->runBatchJob();

        $this->assertSame(0, $this->countNotifications());
        $this->assertMailSent(0);
    }

    public function testQuietPeriodIsTakenFromTheModuleConfiguration()
    {
        $this->assertSame(10, $this->galleryModule()->uploadNotificationDelay);
        $this->assertSame(600, MediaUploadBatch::getDelay());

        $this->galleryModule()->uploadNotificationDelay = 30;
        $this->assertSame(1800, MediaUploadBatch::getDelay());

        $this->galleryModule()->uploadNotificationDelay = 0;
        $this->assertSame(0, MediaUploadBatch::getDelay());

        $this->galleryModule()->uploadNotificationDelay = 10;
    }

    public function testBatchesOfDifferentGalleriesAreIndependent()
    {
        $other = new CustomGallery(Space::findOne(self::SPACE_ID), Content::VISIBILITY_PUBLIC, ['title' => 'Other Batch Test Gallery']);
        $this->assertTrue($other->save());

        $this->upload(3);
        $this->upload(2, $other);

        $this->assertSame(3, $this->batch()->count);
        $this->assertSame(2, $this->batch($other)->count);
        $this->assertSame(2, $this->countQueuedBatchJobs(), 'Each gallery gets its own job');
    }

    public function testBatchesOfDifferentUsersAreIndependent()
    {
        $this->upload(3);

        $otherId = $this->becomeUser('Admin')->id;
        $this->upload(1);

        $this->assertSame(3, $this->batch(null, $this->uploaderId)->count);
        $this->assertSame(1, $this->batch(null, $otherId)->count);
    }
}
