<?php
namespace humhub\modules\gallery;

use \humhub\modules\content\components\ContentContainerActiveRecord;
use \humhub\modules\content\components\ContentContainerModule;
use \humhub\modules\content\models\Content;
use \humhub\modules\content\models\ContentContainer;
use \humhub\modules\file\models\File;
use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\Media;
use \humhub\modules\gallery\models\StreamGallery;
use \humhub\modules\gallery\permissions\WriteAccess;
use \humhub\modules\space\models\Space;
use \humhub\modules\user\models\User;
use \Yii;
use \yii\debug\models\search\Profile;
use \yii\helpers\Url;

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
                new WriteAccess()
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
            return Media::findOne([
                'id' => $id
            ]);
        } elseif ($type == 'stream-gallery') {
            return StreamGallery::findOne([
                'id' => $id
            ]);
        } elseif ($type == 'custom-gallery') {
            return CustomGallery::findOne([
                'id' => $id
            ]);
        } elseif ($type == 'file') {
            return File::findOne([
                'id' => $id
            ]);
        }
        return null;
    }

    public static function getUserById($id)
    {
        return User::findOne([
            'id' => $id
            ]);
    }
    
    public function disable()
    {
        foreach (CustomGallery::find()->all() as $key => $gallery) {
            $gallery->delete();
        }
        foreach (Media::find()->all() as $key => $media) {
            $media->delete();
        }
    }
    
    public function enableContentContainer($container) {
        $streamGallery = new StreamGallery();
        $streamGallery->title = Yii::t('GalleryModule.base', 'Posted pictures');
        $streamGallery->description = Yii::t('GalleryModule.base', 'This gallery contains all posted pictures.');
        $streamGallery->type = StreamGallery::TYPE_STREAM_GALLERY;
        $streamGallery->content->container = $container;
        $streamGallery->content->visibility = Content::VISIBILITY_PUBLIC;
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
        $mediaList = Media::find()->contentContainer($container)->all();
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
     * Check if the current User has write permission for the gallery module and its content.
     * 
     * @param ContentContainer $contentContainer the current content container.
     * @return boolean
     */
    public static function canWrite(ContentContainerActiveRecord $contentContainer) {
        // check if user is on his own profile
        if ($contentContainer instanceof User) {
            if ($contentContainer->id === Yii::$app->user->getIdentity()->id) {
                return true;
            }
        }
        return $contentContainer->permissionManager->can(new WriteAccess());
    
    }
}
