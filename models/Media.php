<?php
namespace humhub\modules\gallery\models;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\models\Content;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\gallery\libs\FileUtilities;
use humhub\modules\file\libs\ImageConverter;

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
    public static function tableName()
    {
        return 'gallery_media';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                'gallery_id',
                'integer'
            ],
            [
                'title',
                'string',
                'max' => 255
            ],
            [
                'description',
                'string',
                'max' => 1000
            ]
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
        // FIXME: dirty workaround to avoid errors if basefile is uninitialized. this happens sometimes when basefile is accessed shortly after being saved with its related media file       
        return isset($this->baseFile) ? $this->baseFile->getUrl() . ($download ? '&' . http_build_query([
            'download' => 1
        ]) : '') : "";
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
        $query = $this->hasOne(\humhub\modules\file\models\File::className(), [
            'object_id' => 'id'
        ]);
        $query->andWhere([
            'file.object_model' => self::className()
        ]);
        return $query;
    }
    
    // TODO: move me out of here to File
    public function getQuadraticThumbnailUrl()
    {
        
        $suffix = 'thumb_quad';
    
        $basefile = $this->baseFile;
        
        if(!isset($this->baseFile)) {
            return "";
        }
        
        $originalFilename = $basefile->getStoredFilePath();
        $previewFilename = $basefile->getStoredFilePath($suffix);
    
        // already generated
        if (is_file($previewFilename)) {
            return $basefile->getUrl($suffix);
        }
    
        // Check file exists & has valid mime type
        if ($basefile->getMimeBaseType() != "image" || !is_file($originalFilename)) {
            return "";
        }
    
        $imageInfo = @getimagesize($originalFilename);
    
        // Check if we got any dimensions - invalid image
        if (!isset($imageInfo[0]) || !isset($imageInfo[1])) {
            return "";
        }
    
        // Check if image type is supported
        if ($imageInfo[2] != IMAGETYPE_PNG && $imageInfo[2] != IMAGETYPE_JPEG && $imageInfo[2] != IMAGETYPE_GIF) {
            return "";
        }
        
        $dim = min($imageInfo[0], $imageInfo[1]);
        
        ImageConverter::Resize($originalFilename, $previewFilename, array('mode' => 'force', 'width' => $dim, 'height' => $dim));
        return $basefile->getUrl($suffix);
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
}
