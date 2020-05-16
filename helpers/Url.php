<?php

namespace humhub\modules\gallery\helpers;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\Media;

class Url extends \yii\helpers\Url
{
    public static function toGalleryOverview(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/gallery/list');
    }

    public static function toCustomGallery(ContentContainerActiveRecord $container, $gid)
    {
        return $container->createUrl('/gallery/custom-gallery', ['gid' => $gid]);
    }

    public static function toCreateCustomGallery(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/gallery/custom-gallery/edit');
    }

    public static function toDeleteCustomGallery(ContentContainerActiveRecord $container, $gid)
    {
        return $container->createUrl('/gallery/custom-gallery/delete', ['gid' => $gid]);
    }

    public static function toDeleteMedia(ContentContainerActiveRecord $container, $id)
    {
        return $container->createUrl('/gallery/media/delete', ['id' => $id]);
    }

    public static function toEditCustomGallery(ContentContainerActiveRecord $container, $gid)
    {
        return $container->createUrl('/gallery/custom-gallery/edit', ['gid' => $gid]);
    }

    public static function toStreamGallery(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/gallery/stream-gallery');
    }

    public static function toEditMedia(ContentContainerActiveRecord $container, Media $media, $fromWall = false)
    {
        return $container->createUrl('/gallery/media/edit', ['id' => $media->id, 'fromWall' => $fromWall]);
    }

    public static function toModuleConfig(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('/gallery/setting');
    }

    public static function toLoadGalleryPage(ContentContainerActiveRecord $container)
    {
        return $container->createUrl('load-page');
    }

    public static function toShowMoreMedia(ContentContainerActiveRecord $container, $gid)
    {
        return $container->createUrl('/gallery/custom-gallery/load-page', ['gid' => $gid]);
    }

    public static function toUploadMedia(ContentContainerActiveRecord $contentContainer, $gid)
    {
        return $contentContainer->createUrl('/gallery/custom-gallery/upload', ['gid' => $gid]);
    }


}