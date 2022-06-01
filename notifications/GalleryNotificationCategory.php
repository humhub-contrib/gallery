<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\notifications;

use humhub\modules\notification\components\NotificationCategory;
use Yii;

/**
 * GalleryNotificationCategory
 */
class GalleryNotificationCategory extends NotificationCategory
{

    /**
     * @inheritdoc
     */
    public $id = 'gallery';

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('GalleryModule.base', 'Gallery');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('GalleryModule.base', 'Receive Notifications for new uploaded media files.');
    }
}