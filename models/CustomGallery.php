<?php

namespace humhub\modules\gallery\models;

use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\space\models\Space;
use Yii;
use humhub\modules\gallery\helpers\Url;

/**
 * This is the model class for a custom gallery.
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class CustomGallery extends BaseGallery
{

    public function getWallUrl()
    {
        return Url::to(['/content/perma', 'id' => $this->content->id], true);
    }

    public function getUrl()
    {
        return Url::toCustomGallery($this->content->container, $this->id);
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

    /**
     * @return ActiveQueryContent
     */
    public function mediaListQuery()
    {
        return Media::find()->where(['gallery_id' => $this->id])->orderBy('id DESC');
    }

    public function getMetaData()
    {
        $result = parent::getMetaData();
        $result['deleteUrl'] = Url::toDeleteCustomGallery($this->content->container, $this->id);
        $result['editUrl'] = Url::toEditCustomGallery( $this->content->container, $this->id);
        $result['imagePadding'] = $this->isEmpty();
        return $result;
    }

    public static function findLatest(ContentContainerActiveRecord $contentContainer)
    {
        return self::find()->contentContainer($contentContainer)->orderBy('id DESC')->one();
    }

    public function getMediaList($max = null)
    {
        return $this->mediaListQuery()->contentContainer($this->content->container)->readable()->limit($max)->all();
    }

    public function isEmpty()
    {
        return $this->mediaListQuery()->one() === null;
    }

}
