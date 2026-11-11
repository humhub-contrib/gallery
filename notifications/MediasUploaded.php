<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2026 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\notifications;

use humhub\modules\gallery\libs\MediaUploadBatch;
use humhub\modules\notification\components\BaseNotification;
use humhub\modules\notification\components\NotificationCategory;
use Yii;

/**
 * Announces all media files a user uploaded into a gallery as one notification.
 *
 * Replaces the per file content created notification, which is suppressed by
 * [[\humhub\modules\gallery\models\Media::$silentContentCreation]].
 *
 * The notification stays in the existing "New content" category of the core, so users and
 * administrators do not have to configure a new notification type. The number of the announced
 * files is the `mediaCount` of its payload. The core sends it to the users who can view the gallery.
 *
 * @see MediaUploadBatch
 * @since 1.9
 */
final class MediasUploaded extends BaseNotification
{
    public const PAYLOAD_MEDIA_COUNT = 'mediaCount';

    /**
     * @inheritdoc
     */
    public static function category(): NotificationCategory
    {
        return NotificationCategory::content();
    }

    /**
     * @return int number of uploaded media files this notification announces
     */
    public function getMediaCount(): int
    {
        return max(1, (int)($this->payload[self::PAYLOAD_MEDIA_COUNT] ?? 1));
    }

    /**
     * @inheritdoc
     */
    protected function getMessage(array $params): string
    {
        return Yii::t('GalleryModule.base', '{displayName} added {n,plural,=1{a media file} other{# media files}} to {contentTitle}.', $params);
    }

    /**
     * @inheritdoc
     */
    protected function getMessageParams(): array
    {
        return [
            'n' => $this->getMediaCount(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function getMailSubject(array $params): string
    {
        $params = [
            'originator' => $params['displayName'],
            'contentTitle' => $params['contentTitle'] ?? '',
            'space' => $this->getSpace()?->displayName,
            'n' => $this->getMediaCount(),
        ];

        if ($params['space'] !== null) {
            return Yii::t('GalleryModule.base', '{originator} added {n,plural,=1{a media file} other{# media files}} to {contentTitle} in Space {space}', $params);
        }

        return Yii::t('GalleryModule.base', '{originator} added {n,plural,=1{a media file} other{# media files}} to {contentTitle}', $params);
    }
}
