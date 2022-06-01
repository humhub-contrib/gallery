<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2022 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\activities;

use humhub\modules\activity\components\BaseActivity;
use humhub\modules\activity\interfaces\ConfigurableActivityInterface;
use humhub\modules\comment\models\Comment;
use humhub\modules\gallery\models\CustomGallery;
use Yii;

/**
 * MediaUploaded activity
 */
class MediaUploaded extends BaseActivity implements ConfigurableActivityInterface
{

    /**
     * @inheritdoc
     */
    public $moduleId = 'gallery';

    /**
     * @inheritdoc
     */
    public $viewName = 'mediaUploaded';

    /**
     * @var CustomGallery
     */
    public $source;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return Yii::t('GalleryModule.base', 'Galleries');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('CommentModule.base', 'Whenever new media files were uploaded.');
    }

    /**
     * @inheritdoc
     */
    public function getUrl()
    {
        return $this->source->url;
    }

}