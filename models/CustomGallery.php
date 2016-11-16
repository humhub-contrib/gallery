<?php
namespace humhub\modules\gallery\models;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for a custom gallery.
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class CustomGallery extends BaseGallery
{

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
        return $this->mediaListQuery()->all();
    }
    
    public function isEmpty() {
        return $this->mediaListQuery()->one() === null;
    }
}
