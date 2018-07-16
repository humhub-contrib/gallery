<?php

namespace humhub\modules\gallery\models;

use \humhub\modules\comment\models\Comment;
use \humhub\modules\file\models\File;
use \humhub\modules\post\models\Post;
use \Yii;

/**
 * This is the model class for a stream gallery.
 * TODO: Video support!
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class StreamGallery extends BaseGallery
{

    /**
     * @inheritdoc
     */
    public $autoAddToWall = false;

    /**
     * @inheritdoc
     */
    public $silentContentCreation = true;


    public function getUrl()
    {
        return $this->content->container->createUrl('/gallery/stream-gallery/view', ['openGalleryId' => $this->id]);
    }

    public function getPreviewImageUrl()
    {
        // search for file by given thumbnail id
        $path = $this->getPreviewImageUrlFromThumbFileId();
        if ($path !== null) {
            return $path;
        }
        // get first image from the complete filelist as fallback
        $fileArray = $this->fileListQuery()
                ->orderBy(['content.updated_at' => SORT_DESC])
                ->asArray()
                ->one();
        $file = File::findOne($fileArray['id']);
        if ($file !== null && !empty(SquarePreviewImage::getSquarePreviewImageUrlFromFile($file))) {
            return SquarePreviewImage::getSquarePreviewImageUrlFromFile($file);
        }
        // return default image if gallery is empty
        return $this->getDefaultPreviewImageUrl();
    }

    public function getItemId()
    {
        return 'stream-gallery_' . $this->id;
    }

    private function fileListQuery()
    {
        $query = Post::find()->select('file.id')->contentContainer($this->content->container)->readable();
        $query->join('LEFT JOIN', 'comment', '(post.id=comment.object_id AND comment.object_model=' . Yii::$app->db->quoteValue(Comment::className()) . ')');
        $query->join('RIGHT JOIN', 'file', '((post.id=file.object_id AND file.object_model=' . Yii::$app->db->quoteValue(Post::className()) . ') OR (comment.id=file.object_id AND file.object_model=' . Yii::$app->db->quoteValue(Comment::className()) . '))');

        // only get gallery suitable content types
        $query->andWhere(['like', 'file.mime_type', 'image/']);
        return $query;
    }

    public function getFileList()
    {
        $fileQuery = $this->fileListQuery();
        $files = $fileQuery->limit(50)->orderBy(['content.updated_at' => SORT_DESC])->asArray()->all();
        return $files;
    }

    public function getMetaData()
    {
        $result = parent::getMetaData();
        $result['footerOverwrite'] = ' ';
        return $result;
    }

    public function getTitle()
    {
        return Yii::t('GalleryModule.base', 'Posted Media Files');
    }

    public function isEmpty()
    {
        // TODO: This also seems very slow. Would be much nicer to already filter in the query and use getFileListQuery->one().
        return sizeof($this->getFileList()) == 0;
    }

    public function getCreator()
    {
        // stream galleries should be automatically created, internally they have a creator but that should not be displayed
        return '';
    }

}
