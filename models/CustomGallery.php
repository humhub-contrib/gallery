<?php

namespace humhub\modules\gallery\models;

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\space\models\Space;
use Yii;
use \yii\helpers\Url;

/**
 * This is the model class for a custom gallery.
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class CustomGallery extends BaseGallery
{

    /**
     * @inheritdoc
     */
    public $wallEntryClass = "humhub\modules\gallery\widgets\WallEntryGallery";

    public function getWallUrl()
    {
        return Url::to(['/content/perma', 'id' => $this->content->id], true);
    }

    public function getUrl()
    {
        return $this->content->container->createUrl('/gallery/custom-gallery/view', ['openGalleryId' => $this->id]);
    }

    public function isPublic() {
        return $this->content->isPublic();
    }
    
    public function getPreviewImageUrl()
    {
        // get preview image from a set thumbfile
        $path = $this->getPreviewImageUrlFromThumbFileId();
        if ($path !== null) {
            return $path;
        }
        // get preview image from the file list
        $media = $this->mediaListQuery()
                ->orderBy([
                    'sort_order' => SORT_ASC
                ])
                ->one();
        if ($media != null && !empty($media->getSquarePreviewImageUrl())) {
            return $media->getSquarePreviewImageUrl();
        } 
        // return default image if gallery is empty
        return $this->getDefaultPreviewImageUrl();
    }

    public function afterDelete()
    {
        foreach ($this->getMediaList() as $media) {
            $media->delete();
        }

        return parent::afterDelete();
    }

    public function getItemId()
    {
        if($this->id != null) {
            return 'custom-gallery_' . $this->id;
        }

        return null;
    }

    public function mediaListQuery()
    {
        $query = Media::find()->where([
            'gallery_id' => $this->id
        ]);
        return $query;
    }

    public function getMetaData()
    {
        $result = parent::getMetaData();
        $result['deleteUrl'] = $this->content->getContainer()->createUrl('/gallery/list/delete-multiple', ['itemId' => $this->getItemId()]);
        $result['editUrl'] = $this->content->getContainer()->createUrl('/gallery/custom-gallery/edit', ['itemId' => $this->getItemId()]);
        $result['imagePadding'] = $this->isEmpty();
        return $result;
    }

    public static function findLatest(ContentContainerActiveRecord $contentContainer)
    {
        return self::find()->contentContainer($contentContainer)->orderBy('id DESC')->one();
    }

    public function getMediaList($max = null)
    {
        if (Yii::$app->user->isGuest && version_compare(Yii::$app->version, '1.2.1', 'lt')) {
            $query = $this->mediaListQuery()->contentContainer($this->content->container);
            $query->leftJoin('space', 'contentcontainer.pk=space.id AND contentcontainer.class=:spaceClass', [':spaceClass' => Space::className()]);
            return $query->readable()->limit($max)->all();
        } else {
            return $this->mediaListQuery()->contentContainer($this->content->container)->readable()->limit($max)->all();
        }
    }

    public function isEmpty()
    {
        return $this->mediaListQuery()->one() === null;
    }

}
