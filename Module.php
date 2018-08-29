<?php

namespace humhub\modules\gallery;

use \humhub\modules\content\components\ContentContainerActiveRecord;
use \humhub\modules\content\components\ContentContainerModule;
use \humhub\modules\content\models\Content;
use \humhub\modules\content\models\ContentContainer;
use \humhub\modules\file\models\File;
use humhub\modules\gallery\models\BaseGallery;
use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\Media;
use \humhub\modules\gallery\models\StreamGallery;
use \humhub\modules\gallery\permissions\WriteAccess;
use \humhub\modules\space\models\Space;
use \humhub\modules\user\models\User;
use \Yii;
use \yii\debug\models\search\Profile;

class Module extends ContentContainerModule
{

    public $snippetMaxImages = 20;
    public $debug = false;

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
            User::class
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
        if (!is_string($itemId) || $itemId === '') {
            return null;
        }

        list ($type, $id) = explode('_', $itemId);

        if ($type == 'media') {
            return Media::findOne(['id' => $id]);
        } elseif ($type == 'stream-gallery') {
            return StreamGallery::findOne(['id' => $id]);
        } elseif ($type == 'custom-gallery') {
            return CustomGallery::findOne(['id' => $id]);
        } elseif ($type == 'file') {
            return File::findOne(['id' => $id]);
        }

        return null;
    }

    public function disable()
    {

        $customGalleries = CustomGallery::findAll([]);
        foreach ($customGalleries as $gallery) {
            $gallery->delete();
        }

        $streamGalleries = StreamGallery::findAll([]);
        foreach ($streamGalleries as $gallery) {
            $gallery->delete();
        }

        $mediaList = Media::findAll([]);
        foreach ($mediaList as $media) {
            $media->delete();
        }

        parent::disable();
    }

    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        $streamGallery = new StreamGallery($container, Content::VISIBILITY_PUBLIC, [
            'title' => Yii::t('GalleryModule.base', 'Posted pictures'),
            'description' => Yii::t('GalleryModule.base', 'This gallery contains all posted pictures.')
        ]);

        $streamGallery->save();
    }

    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        $streamGalleries = StreamGallery::find()->contentContainer($container)->where(['type' => StreamGallery::class])->all();
        foreach ($streamGalleries as $gallery) {
            $gallery->delete();
        }

        $customGalleries = CustomGallery::find()->contentContainer($container)->where(['type' => CustomGallery::class])->all();
        foreach ($customGalleries as $gallery) {
            $gallery->delete();
        }

        $mediaList = Media::find()->contentContainer($container)->all();
        foreach ($mediaList as $media) {
            $media->delete();
        }

        parent::disableContentContainer($container);
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
     * @param ContentContainerActiveRecord $contentContainer the current content container.
     * @return bool
     */
    public static function canWrite(ContentContainerActiveRecord $contentContainer)
    {
        // check if user is on his own profile
        if ($contentContainer instanceof User && !Yii::$app->user->isGuest) {
            if ($contentContainer->id === Yii::$app->user->getIdentity()->id) {
                return true;
            }
        }
        return $contentContainer->permissionManager->can(new WriteAccess());
    }

}
