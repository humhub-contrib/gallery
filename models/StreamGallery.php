<?php
namespace humhub\modules\gallery\models;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentActiveRecord;
use yii\helpers\FileHelper;
use humhub\modules\file\models\File;
use humhub\modules\comment\models\Comment;
use humhub\modules\post\models\Post;
use humhub\modules\gallery\libs\FileUtils;

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
        if ($file !== null) {
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
        $query = \humhub\modules\file\models\File::find();
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
        return $this->fileListQuery()
            ->orderBy([
            'updated_at' => SORT_DESC
        ])
            ->all();
    }
    
    public function isEmpty() {
        return $this->fileListQuery()->one() === null;
    }
}
