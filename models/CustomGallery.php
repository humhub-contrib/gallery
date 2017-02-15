<?php

namespace humhub\modules\gallery\models;

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
    public $autoAddToWall = true;

    /**
     * @inheritdoc
     */
    public $wallEntryClass = "humhub\modules\gallery\widgets\WallEntryGallery";

    public function getWallUrl()
    {
        $firstWallEntryId = $this->content->getFirstWallEntryId();

        if ($firstWallEntryId == '') {
            return '';
        }

        return Url::toRoute(['/content/perma/wall-entry', 'id' => $firstWallEntryId]);
    }

    public function getUrl()
    {
        return $this->content->container->createUrl('/gallery/custom-gallery/view', [
                    'open-gallery-id' => $this->id
        ]);
    }

    public function getPreviewImageUrl()
    {
        // search for file by given thumbnail id
        $path = $this->getPreviewImageUrlFromThumbFileId();
        if ($path !== null) {
            return $path;
        }
        $media = $this->mediaListQuery()
                ->orderBy([
                    'sort_order' => SORT_ASC
                ])
                ->one();
        if ($media != null) {
            return $media->getSquareThumbnailUrl();
        } else {
            // return default image if gallery is empty
            return $this->getDefaultPreviewImageUrl();
        }
    }

    public function beforeDelete()
    {
        foreach ($this->getMediaList() as $media) {
            $media->delete();
        }

        return parent::beforeDelete();
    }

    public function getItemId()
    {
        return 'custom-gallery_' . $this->id;
    }

    public function mediaListQuery()
    {
        $query = Media::find()->where([
            'gallery_id' => $this->id
        ]);
        return $query;
    }

    public function getMediaList()
    {
        return $this->mediaListQuery()->contentContainer($this->content->container)->readable()->all();
    }

    public function isEmpty()
    {
        return $this->mediaListQuery()->one() === null;
    }

}
