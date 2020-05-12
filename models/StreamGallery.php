<?php

namespace humhub\modules\gallery\models;

use \humhub\modules\comment\models\Comment;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\content\widgets\Stream;
use \humhub\modules\file\models\File;
use humhub\modules\gallery\helpers\Url;
use \humhub\modules\post\models\Post;
use humhub\modules\stream\actions\ContentContainerStream;
use humhub\modules\stream\models\StreamQuery;
use humhub\modules\stream\models\WallStreamQuery;
use \Yii;
use yii\data\Pagination;
use yii\db\ActiveQuery;

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
    public $silentContentCreation = true;


    /**
     * @param ContentContainerActiveRecord $container
     * @param bool $init
     * @throws \yii\base\Exception
     * @return static
     */
    public static function findForContainer(ContentContainerActiveRecord $container, $init = false)
    {
        $result = static::find()->contentContainer($container)->where(['type' => StreamGallery::class])->one();

        if(!$result && $init) {
            $result = new StreamGallery($container, Content::VISIBILITY_PUBLIC, [
                'title' => Yii::t('GalleryModule.base', 'Posted pictures'),
                'description' => Yii::t('GalleryModule.base', 'This gallery contains all posted pictures.')
            ]);
            $result->save();
        }

        return $result;
    }

    public function getUrl()
    {
        return Url::toStreamGallery($this->content->container);
    }

    public function getPreviewImageUrl()
    {
        // search for file by given thumbnail id
        $path = $this->getPreviewImageUrlFromThumbFileId();
        if ($path !== null) {
            return $path;
        }
        // get first image from the complete filelist as fallback
        $file = $this->fileListQuery()->one();

        if(!$file) {
            return $this->getDefaultPreviewImageUrl();
        }

        $preview = SquarePreviewImage::getSquarePreviewImageUrlFromFile($file);

        return $preview ?: $this->getDefaultPreviewImageUrl();
    }

    public function getItemId()
    {
        return 'stream-gallery_' . $this->id;
    }

    /**
     * @return ActiveQuery
     */
    public function fileListQuery()
    {
        $container = $this->content->container;
        $joinCondition = 'content.contentcontainer_id = :containerId AND content.object_id = file.object_id and content.object_model = file.object_model';

        $query = File::find()
            ->innerJoin('content', $joinCondition, ['containerId' => $this->content->container->contentcontainer_id])
           // ->where('content.visibility = :visibility', [':visibility' => Content::VISIBILITY_PUBLIC])
            ->andWhere(['like', 'file.mime_type', 'image/'])
            ->andWhere('content.object_model != :media', ['media' => Media::class])
            ->andWhere('show_in_stream = 1')
            ->orderBy(['file.updated_at' => SORT_DESC]);

        if(!$container->canAccessPrivateContent()) {
            $query->andWhere('content.visibility = :visibility', [':visibility' => Content::VISIBILITY_PUBLIC]);
        }

        return $query;
    }

    public function getFileList($page = 0)
    {
        $fileQuery = $this->fileListQuery();
        $countQuery = clone $fileQuery;

        $pages = new Pagination([
            'page' => $page,
            'pageSize' => $this->getPageSize(),
            'totalCount' => $countQuery->count()]);

        $files = $fileQuery->limit($pages->pageSize)->offset($pages->offset)->orderBy(['file.updated_at' => SORT_DESC])->all();
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
        return empty($this->fileListQuery()->count());
    }

    public function getCreator()
    {
        // stream galleries should be automatically created, internally they have a creator but that should not be displayed
        return '';
    }

}
