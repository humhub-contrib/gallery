<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2026 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\notifications;

use humhub\helpers\Html;
use humhub\modules\content\notifications\ContentCreated;
use humhub\modules\gallery\libs\MediaUploadBatch;
use Yii;

/**
 * Announces all media files a user uploaded into a gallery as one notification.
 *
 * Replaces the per file content created notification, which is suppressed by
 * [[\humhub\modules\gallery\models\Media::$silentContentCreation]].
 *
 * Extending [[ContentCreated]] keeps the notification in the existing "New content" category,
 * so users and administrators do not have to configure a new notification type, and reuses its
 * `canView()` check for the announced gallery.
 *
 * @see MediaUploadBatch
 * @since 1.9
 */
class MediasUploaded extends ContentCreated
{
    /**
     * @inheritdoc
     *
     * No view file is needed: no `mediasUploaded.php` exists in `notifications/views` nor in
     * `@notification/views`, so the renderer falls back to `@notification/views/default.php`
     * (and `mails/default.php`), which renders [[html()]] plus a "View Online" button.
     * Adding `notifications/views/mediasUploaded.php` or `notifications/views/mails/mediasUploaded.php`
     * later overrides that without any code change.
     *
     * @see \humhub\components\rendering\DefaultViewPathRenderer::getViewFile()
     */
    public $viewName = 'mediasUploaded';

    /**
     * @inheritdoc
     */
    public $moduleId = 'gallery';

    /**
     * @param int $mediaCount number of uploaded media files this notification announces
     * @return $this
     */
    public function mediaCount(int $mediaCount)
    {
        return $this->payload(['mediaCount' => $mediaCount]);
    }

    /**
     * @return int number of uploaded media files this notification announces
     */
    public function getMediaCount(): int
    {
        return max(1, (int)($this->payload['mediaCount'] ?? 1));
    }

    /**
     * @inheritdoc
     */
    public function html()
    {
        return Yii::t('GalleryModule.base', '{displayName} added {n,plural,=1{a media file} other{# media files}} to {contentTitle}.', [
            'displayName' => Html::tag('strong', Html::encode($this->originator->displayName)),
            'contentTitle' => $this->getContentInfo($this->source),
            'n' => $this->getMediaCount(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getMailSubject()
    {
        $space = $this->getSpace();

        if ($space) {
            return Yii::t('GalleryModule.base', '{originator} added {n,plural,=1{a media file} other{# media files}} to {contentTitle} in Space {space}', [
                'originator' => $this->originator->displayName,
                'contentTitle' => $this->getContentInfo(),
                'space' => $space->displayName,
                'n' => $this->getMediaCount(),
            ]);
        }

        return Yii::t('GalleryModule.base', '{originator} added {n,plural,=1{a media file} other{# media files}} to {contentTitle}', [
            'originator' => $this->originator->displayName,
            'contentTitle' => $this->getContentInfo(),
            'n' => $this->getMediaCount(),
        ]);
    }

    /**
     * @inheritdoc
     *
     * The base implementation only keeps source and originator, but the media count has to
     * survive the queue, since it is only persisted when the notification records are created.
     */
    public function __serialize(): array
    {
        $data = parent::__serialize();
        $data['mediaCount'] = $this->getMediaCount();

        return $data;
    }

    /**
     * @inheritdoc
     */
    public function __unserialize($unserializedArr)
    {
        parent::__unserialize($unserializedArr);

        if (isset($unserializedArr['mediaCount'])) {
            $this->mediaCount((int)$unserializedArr['mediaCount']);
        }
    }
}
