<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\notifications;

use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\notification\components\BaseNotification;
use Yii;
use yii\bootstrap\Html;

/**
 * MediaUploaded notification for multiple files uploaded.
 */
class MediaUploaded extends BaseNotification
{

    /**
     * @inheritdoc
     */
    public $viewName = 'mediaUploaded';

    /**
     * @inheritdoc
     */
    public $moduleId = 'gallery';

    /**
     * @inheritdoc
     * @var CustomGallery
     */
    public $source;

    /**
     * @inheritdoc
     */
    public function category()
    {
        return new GalleryNotificationCategory();
    }

    /**
     * @inheritdoc
     */
    public function html()
    {
        return Yii::t('GalleryModule.base', '{displayName} uploaded new files into {contentTitle}.', [
            'displayName' => Html::tag('strong', Html::encode($this->originator->displayName)),
            'contentTitle' => $this->getContentInfo($this->source)
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getMailSubject()
    {
        return Yii::t('GalleryModule.base', '{originator} uploaded files into {contentTitle}', [
            'originator' => $this->originator->displayName,
            'contentTitle' => $this->getContentInfo($this->source)
        ]);
    }
}