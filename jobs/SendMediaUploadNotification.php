<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2026 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\jobs;

use humhub\modules\gallery\libs\MediaUploadBatch;
use humhub\modules\queue\ActiveJob;
use Yii;

/**
 * Announces a batch of uploaded media files with a single notification.
 *
 * The job is queued with a delay by [[MediaUploadBatch::add()]] when the first file of a batch
 * arrives. Since further uploads restart the quiet period, the job re-queues itself as long as
 * the batch is not due yet.
 *
 * @since 1.9
 */
class SendMediaUploadNotification extends ActiveJob
{
    /**
     * @var int upper bound of re-queues, so that queue drivers which ignore the delay (the
     *      Instant and Sync drivers run jobs right away) announce the batch instead of
     *      re-queueing themselves endlessly.
     */
    public const MAX_ATTEMPTS = 10;

    /**
     * @var int
     */
    public $galleryId;

    /**
     * @var int
     */
    public $userId;

    /**
     * @var int how often this job was already re-queued for the same batch
     */
    public $attempt = 0;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $batch = MediaUploadBatch::load((int)$this->galleryId, (int)$this->userId);

        if ($batch->isEmpty()) {
            // Already announced, or the batch was lost through a cache flush
            return;
        }

        $remainingDelay = $batch->getRemainingDelay();

        if ($remainingDelay > 0 && $this->attempt < self::MAX_ATTEMPTS) {
            // Uploads continued after this job was queued, wait for them to settle
            Yii::$app->queue->delay($remainingDelay)->push(new self([
                'galleryId' => $this->galleryId,
                'userId' => $this->userId,
                'attempt' => $this->attempt + 1,
            ]));
            return;
        }

        $batch->notify();
    }
}
