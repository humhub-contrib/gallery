<?php

namespace humhub\modules\gallery\models;

use \humhub\modules\content\components\ContentActiveRecord;
use \humhub\modules\file\models\File;
use humhub\modules\gallery\permissions\WriteAccess;
use \humhub\modules\user\models\User;
use humhub\modules\gallery\Module;
use \Yii;

/**
 * This is the abstract model class for table "gallery_gallery".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 * @property integer $thumb_file_id
 * @property integer $type
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class BaseGallery extends ContentActiveRecord
{
    /**
     * @inheritdoc
     */
    public $streamChannel = null;

    /**
     * @inheritdoc
     */
    public $managePermission = WriteAccess::class;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'gallery_gallery';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'string', 'max' => 255],
            ['description', 'string', 'max' => 1000],
            [['sort_order', 'thumb_file_id', 'type',], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'title' => Yii::t('GalleryModule.base', 'Title'),
            'description' => Yii::t('GalleryModule.base', 'Description')
        ];
    }

    public function getCreator()
    {
        return User::findOne(['id' => $this->content->created_by]);
    }

    public function getEditor()
    {
        return User::findOne(['id' => $this->content->updated_by]);
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('GalleryModule.base', "Gallery");
    }

    public function getTitle() {
        return $this->title;
    }

    public function getUrl() {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getContentDescription()
    {
        return $this->title;
    }

    protected function getDefaultPreviewImageUrl()
    {
        $path = Yii::$app->getModule('gallery')->getAssetsUrl();
        $path = $path . '/file-picture-o.svg';
        return $path;
    }

    public function getMetaData()
    {
        return [
            'creator' => '', // not in use $this->entryObject->getCreator()
            'title' => $this->getTitle(),
            'wallUrl' => '',
            'deleteUrl' => '',
            'editUrl' => '',
            'downloadUrl' => '',
            'fileUrl' => $this->getUrl(),
            'thumbnailUrl' => $this->getPreviewImageUrl(),
            'contentContainer' => '',
            'writeAccess' => $this->content->container->can(WriteAccess::class),
            'contentObject' => $this,
            'footerOverwrite' => false,
            'alwaysShowHeading' => true,
            'imagePadding' => ''
        ];
    }

    public static function findOne($condition)
    {
        if(static::class !== BaseGallery::class) {
            $condition = $condition ? $condition : [];
            $condition['type'] = isset($condition['type']) ? $condition['type'] : static::class;
            return parent::findOne($condition);
        }

        return parent::findOne($condition);
    }
    
    public static function findAll($condition)
    {
        if(static::class !== BaseGallery::class) {
            $condition = $condition ? $condition : [];
            $condition['type'] = isset($condition['type']) ? $condition['type'] : static::class;
            return parent::findAll($condition);
        }

        return parent::findAll($condition);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if($insert) {
            $this->type = static::class;
        }
        return parent::beforeSave($insert);
    }

    public function getPreviewImageUrl()
    {
        return $this->getDefaultPreviewImageUrl();
    }

    protected function getPreviewImageUrlFromThumbFileId()
    {
        // search for file by given thumbnail id
        if ($this->thumb_file_id !== null) {
            $file = File::findOne($this->thumb_file_id);
            // set thumb image id not found
            if ($file !== null) {
                return SquarePreviewImage::getSquarePreviewImageUrlFromFile($file);
            } else {
                // save with id null if no thumbfile not found
                $this->thumb_file_id = null;
                $this->save();
            }
        }
        return null;
    }

    public function getPageSize()
    {
        return Yii::$app->getModule('gallery')->galleryMaxImages;
    }

}
