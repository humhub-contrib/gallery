<?php

namespace humhub\modules\gallery\models;

use \humhub\modules\content\components\ContentActiveRecord;
use \humhub\modules\file\models\File;
use \humhub\modules\gallery\libs\FileUtils;
use \humhub\modules\user\models\User;
use \Yii;
use \yii\helpers\Url;

/**
 * This is the model class for table "gallery_media".
 *
 * @property integer $id
 * @property integer $gallery_id
 * @property string $description
 * @property string $title
 * @property integer $sort_order
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class Media extends ContentActiveRecord
{

    /**
     * @inheritdoc
     */
    public $autoAddToWall = true;

    /**
     * @inheritdoc
     */
    public $wallEntryClass = "humhub\modules\gallery\widgets\WallEntryMedia";

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery_media';
    }

    public function getWallUrl()
    {
        //@deprecated: v1.1 compatibility
        if (version_compare(Yii::$app->version, '1.2', '<')) {
            $firstWallEntryId = $this->content->getFirstWallEntryId();
            if ($firstWallEntryId == '') {
                return '';
            }
            return Url::toRoute(['/content/perma/wall-entry', 'id' => $firstWallEntryId]);
        } else {
            $permaLink = Url::to(['/content/perma', 'id' => $this->content->id], true);
            return $permaLink;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['gallery_id', 'integer'],
            ['title', 'string', 'max' => 255],
            ['description', 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gallery_id' => 'Gallery ID',
            'description' => 'Description'
        ];
    }

    public function getItemId()
    {
        return 'media_' . $this->id;
    }

    public function getItemType()
    {
        return FileUtils::getItemTypeByExt($this->baseFile->getExtension());
    }

    public function getFileName()
    {
        return $this->baseFile->file_name;
    }

    public function getSize()
    {
        return $this->baseFile->size;
    }

    public function getUrl($download = false)
    {
        //@deprecated: v1.1 compatibility
        if (version_compare(Yii::$app->version, '1.2', '<')) {
            return isset($this->baseFile) ? $this->baseFile->getUrl() . ($download ? '&' . http_build_query([
                        'download' => 1
                    ]) : '') : "";
        } else {
            return $this->baseFile->getUrl(['download' => $download]);
        }
    }

    public function getCreator()
    {
        return User::findOne([
                    'id' => $this->baseFile->created_by
        ]);
    }

    public function getEditor()
    {
        return User::findOne([
                    'id' => $this->baseFile->updated_by
        ]);
    }

    public function getBaseFile()
    {
        $query = $this->hasOne(File::className(), [
            'object_id' => 'id'
        ]);
        $query->andWhere([
            'file.object_model' => self::className()
        ]);
        return $query;
    }

    protected function getFallbackPreviewImageUrl()
    {
        $path = Yii::$app->getModule('gallery')->getAssetsUrl();
        $path = $path . '/file-picture-o.svg';
        return $path;
    }

    public function getSquareThumbnailUrl($maxDimension = 1000)
    {
        $previewImage = FileUtils::getSquareThumbnailUrlFromFile($this->baseFile, $maxDimension);
        if (!empty($previewImage)) {
            return $previewImage;
        } else {
            return $this->getFallbackPreviewImageUrl();
        }
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('GalleryModule.base', "Media");
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->getTitle();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getParentGallery()
    {
        $query = $this->hasOne(CustomGallery::className(), [
            'id' => 'gallery_id'
        ]);
        return $query;
    }

}
