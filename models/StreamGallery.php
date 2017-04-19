<?php

namespace humhub\modules\gallery\models;

use \humhub\modules\comment\models\Comment;
use \humhub\modules\file\models\File;
use \humhub\modules\gallery\libs\FileUtils;
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

    public function getUrl()
    {
        return $this->content->container->createUrl('/gallery/stream-gallery/view', [
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
        // get first image from the complete filelist as fallback
        $file = $this->fileListQuery()
                ->orderBy([
                    'updated_at' => SORT_ASC
                ])
                ->one();
        if ($file !== null && !empty(FileUtils::getSquareThumbnailUrlFromFile($file))) {
            return FileUtils::getSquareThumbnailUrlFromFile($file);
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
        $query = File::find();

        // join comments to the file if available
        $query->join('LEFT JOIN', 'comment', '(file.object_id=comment.id AND file.object_model=' . Yii::$app->db->quoteValue(Comment::className()) . ')');
        // join parent post of comment or file
        $query->join('LEFT JOIN', 'content', '(comment.object_model=content.object_model AND comment.object_id=content.object_id) OR (file.object_model=content.object_model AND file.object_id=content.object_id)');

        // select only the one for the given content container for Yii version >= 1.1
        $query->andWhere([
            'content.contentcontainer_id' => $this->content->contentcontainer_id
        ]);
        // only accept Posts as the base content, so stuff from submodules like files itsself or gallery will be excluded
        $query->andWhere([
            'or',
            [
                '=',
                'comment.object_model',
                Post::className()
            ],
            [
                '=',
                'file.object_model',
                Post::className()
            ]
        ]);
        // only get gallery suitable content types
        $query->andWhere([
            'like',
            'file.mime_type',
            'image/'
        ]);
        return $query;
    }

    public function getFileList()
    {
        $files = $this->fileListQuery()
                ->orderBy([
                    'updated_at' => SORT_DESC
                ])
                ->all();
        //TODO: This is ugly and probably slow. Would be much nicer to already filter in the query.
        return array_filter($files, function ($file) {
            return $file->canRead();
        });
    }

    public function isEmpty()
    {
        // TODO: This also seems very slow. Would be much nicer to already filter in the query and use getFileListQuery->one().
        return sizeof($this->getFileList()) == 0;
    }

    public function getCreator()
    {
        // stream galleries should be automatically created
        return '';
    }
}
