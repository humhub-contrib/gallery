<?php

namespace humhub\modules\gallery;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\content\models\Content;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\forms\ContainerSettings;
use humhub\modules\gallery\models\Media;
use humhub\modules\gallery\models\StreamGallery;
use humhub\modules\gallery\permissions\WriteAccess;
use humhub\modules\space\models\Space;
use humhub\modules\user\models\User;
use Yii;

class Module extends ContentContainerModule
{
    public $galleryMaxImages = 50;
    public $snippetMaxImages = 20;
    public $debug = false;

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
            User::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getContentClasses(): array
    {
        return [Media::class];
    }

    public function getContainerPermissions($contentContainer = null)
    {
        return [
            new WriteAccess(),
        ];
    }

    public function getContentContainerConfigUrl(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/gallery/setting');
    }

    public function getConfigUrl()
    {
        return Url::to(['/gallery/config']);
    }

    public function disable()
    {
        $customGalleries = CustomGallery::findAll([]);
        foreach ($customGalleries as $gallery) {
            $gallery->hardDelete();
        }

        $streamGalleries = StreamGallery::findAll([]);
        foreach ($streamGalleries as $gallery) {
            $gallery->hardDelete();
        }

        $mediaList = Media::findAll([]);
        foreach ($mediaList as $media) {
            $media->hardDelete();
        }

        parent::disable();
    }

    public function enableContentContainer(ContentContainerActiveRecord $container)
    {
        $streamGallery = new StreamGallery($container, Content::VISIBILITY_PUBLIC, [
            'title' => Yii::t('GalleryModule.base', 'Posted pictures'),
            'description' => Yii::t('GalleryModule.base', 'This gallery contains all posted pictures.'),
        ]);

        $streamGallery->save();
    }

    public function disableContentContainer(ContentContainerActiveRecord $container)
    {
        $streamGalleries = StreamGallery::find()->contentContainer($container)->where(['type' => StreamGallery::class])->all();
        foreach ($streamGalleries as $gallery) {
            $gallery->hardDelete();
        }

        $customGalleries = CustomGallery::find()->contentContainer($container)->where(['type' => CustomGallery::class])->all();
        foreach ($customGalleries as $gallery) {
            $gallery->hardDelete();
        }

        $mediaList = Media::find()->contentContainer($container)->all();
        foreach ($mediaList as $media) {
            $media->hardDelete();
        }

        parent::disableContentContainer($container);
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerName(ContentContainerActiveRecord $container)
    {
        return Yii::t('GalleryModule.base', 'Gallery');
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

    public function getContentHiddenGlobalDefault(): bool
    {
        return (bool) $this->settings->get('contentHiddenGlobalDefault', false);
    }

    public function getContentHiddenDefault(ContentContainerActiveRecord $contentContainer): bool
    {
        return (new ContainerSettings(['contentContainer' => $contentContainer]))->getContentHiddenDefault();
    }
}
