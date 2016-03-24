<?php
namespace humhub\modules\gallery;

use Yii;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use yii\helpers\Url;
use humhub\modules\gallery\models\Gallery;

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
        if(!is_string($itemId) || $itemId === '') {
            return null;
        }
        
        list ($type, $id) = explode('_', $itemId);
        
        if ($type == 'media') {
            return models\Media::findOne([
                'id' => $id
            ]);
        } elseif ($type == 'gallery') {
            return models\Gallery::findOne([
                'id' => $id
            ]);
        }
        return null;
    }

    public function disable()
    {
        foreach (Gallery::find()->all() as $key => $folder) {
            $folder->delete();
        }
        foreach (Media::find()->all() as $key => $file) {
            $file->delete();
        }
    }

    public function disableContentContainer(\humhub\modules\content\components\ContentContainerActiveRecord $container)
    {
        $galleries = Content::findAll([
            'object_model' => Gallery::className(),
            'space_id' => $container->id
        ]);
        foreach ($galleries as $galleryContent) {
            $gallery = Gallery::findOne([
                'id' => $galleryContent->object_id
            ]);
            $gallery->delete();
        }
        $mediaList = Content::findAll([
            'object_model' => Media::className(),
            'space_id' => $container->id
        ]);
        foreach ($mediaList as $mediaContent) {
            $media = Media::findOne([
                'id' => $mediaContent->object_id
            ]);
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
     */
    public function getConfigUrl()
    {
        return Url::to([
            '/gallery/config'
        ]);
    }
}
