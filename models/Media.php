<?php

namespace humhub\modules\gallery\models;

use \humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\file\handler\DownloadFileHandler;
use \humhub\modules\file\models\File;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\permissions\WriteAccess;
use humhub\modules\search\interfaces\Searchable;
use \humhub\modules\user\models\User;
use \Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "gallery_media".
 *
 * @property integer $id
 * @property integer $gallery_id
 * @property string $description
 * @property string $title
 * @property integer $sort_order
 * @property File $baseFile
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class Media extends ContentActiveRecord implements Searchable
{

    /**
     * @var BaseGallery used for instantiation
     */
    public $gallery;

    /**
     * @inheritdoc
     */
    public $autoAddToWall = true;

    /**
     * @inheritdoc
     */
    public $managePermission = WriteAccess::class;

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

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->gallery) {
            $this->gallery_id = $this->gallery->id;
            $this->content->visibility = $this->gallery->content->visibility;
            $this->content->container = $this->gallery->content->container;
        }
    }

    public function getWallUrl()
    {
        return Url::to(['/content/perma', 'id' => $this->content->id], true);
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

    public function getFileName()
    {
        return $this->baseFile->file_name;
    }

    public function getSize()
    {
        return $this->baseFile->size;
    }

    /**
     * @param bool $download
     * @return string|null
     */
    public function getFileUrl($download = false)
    {
        if ($this->baseFile === null) {
            return null;
        }

        return DownloadFileHandler::getUrl($this->baseFile, $download);
    }

    public function getCreator()
    {
        return User::findOne(['id' => $this->baseFile->created_by]);
    }

    public function getEditor()
    {
        return User::findOne(['id' => $this->baseFile->updated_by]);
    }

    public function getBaseFile()
    {
        $query = $this->hasOne(File::className(), ['object_id' => 'id']);
        $query->andWhere(['file.object_model' => self::class]);
        return $query;
    }

    public static function getFallbackPreviewImageUrl()
    {
        $path = Yii::$app->getModule('gallery')->getAssetsUrl();
        $path .= '/file-picture-o.svg';
        return $path;
    }

    public function getSquarePreviewImageUrl()
    {
        try {
            $previewImage = SquarePreviewImage::getSquarePreviewImageUrlFromFile($this->baseFile);
        } catch (\Exception $e) {

        }
        return empty($previewImage) ? static::getFallbackPreviewImageUrl() : $previewImage;
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
        return $this->hasOne(CustomGallery::class, ['id' => 'gallery_id']);
    }

    public function getEditUrl($fromWall = false)
    {
        return Url::toEditMedia($this->content->container, $this, $fromWall);
    }

    public function getIcon()
    {
        return 'fa-picture-o';
    }

    /**
     * Saves the given uploaded file.
     *
     * @param UploadedFile $file
     * @return MediaUpload
     * @internal param UploadedFile $cfile
     */
    public function handleUpload(UploadedFile $file)
    {
        $mediaUpload = new MediaUpload();
        $mediaUpload->setUploadedFile($file);
        $valid = $mediaUpload->validate();

        if ($valid) {
            $this->title = $mediaUpload->file_name;

            $valid = $this->validate();

            if ($valid) {
                if ($this->save()) {
                    $mediaUpload->object_model = self::class;
                    $mediaUpload->object_id = $this->id;
                    $mediaUpload->show_in_stream = false;
                    $mediaUpload->save();
                }
            }
        }

        return $mediaUpload;
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        if ($this->baseFile) {
            $this->baseFile->delete();
        }
        parent::afterDelete();
    }

    /**
     * @inheritdoc
     */
    public function getSearchAttributes()
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
        ];
    }
}