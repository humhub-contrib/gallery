<?php
namespace humhub\modules\gallery;

use Yii;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use yii\helpers\Url;
use humhub\modules\gallery\models\StreamGallery;
use humhub\modules\gallery\models\CustomGallery;

class Module extends ContentContainerModule
{

    public $debug = false;

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::className(),
            User::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function getPermissions($contentContainer = null)
    {
        if ($contentContainer instanceof Space || $contentContainer instanceof Profile) {
            return [
                new permissions\WriteAccess()
            ];
        }
        
        return [];
    }

    public function getItemById($itemId)
    {
        if (! is_string($itemId) || $itemId === '') {
            return null;
        }
        
        list ($type, $id) = explode('_', $itemId);
        
        if ($type == 'media') {
            return models\Media::findOne([
                'id' => $id
            ]);
        } elseif ($type == 'stream-gallery') {
            return models\StreamGallery::findOne([
                'id' => $id
            ]);
        } elseif ($type == 'custom-gallery') {
            return models\CustomGallery::findOne([
                'id' => $id
            ]);
        }
        return null;
    }

    public function disable()
    {
        foreach (models\CustomGallery::find()->all() as $key => $gallery) {
            $gallery->delete();
        }
        foreach (models\Media::find()->all() as $key => $media) {
            $media->delete();
        }
    }
    
    public function enableContentContainer($container) {
        $streamGallery = new StreamGallery();
        $streamGallery->title = Yii::t('GalleryModule.base', 'Posted pictures');
        $streamGallery->description = Yii::t('GalleryModule.base', 'This gallery contains all posted pictures.');
        $streamGallery->type = StreamGallery::TYPE_STREAM_GALLERY;
        $streamGallery->content->container = $container;
        $streamGallery->save();
    }

    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        $galleries = StreamGallery::find()->contentContainer($container)->all();
        foreach ($galleries as $gallery) {
            $gallery->delete();
        }
        $galleries = CustomGallery::find()->contentContainer($container)->all();
        foreach ($galleries as $gallery) {
            $gallery->delete();
        }
        $mediaList = models\Media::find()->contentContainer($container)->all();
        foreach ($mediaList as $media) {
            $media->delete();
        }
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerDescription(ContentContainerActiveRecord $container)
    {
        if ($container instanceof Space) {
            return Yii::t('GalleryModule.base', 'Adds gallery module to this space.');
        } elseif ($container instanceof User) {
            return Yii::t('GalleryModule.base', 'Adds gallery module to your profile.');
        }
    }

    /**
     * @inheritdoc
     * 
     * @deprecated IS DUMMY IMPLEMENTATION.
     */
    public function getConfigUrl()
    {
        return Url::to([
            '/gallery/config'
        ]);
    }
}
