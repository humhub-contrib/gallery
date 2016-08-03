<?php
namespace humhub\modules\gallery\models;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\comment\models\Comment;
use humhub\modules\content\models\Content;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\gallery\libs\FileUtils;

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
    
    public function getSquareThumbnailUrl($maxDimension = 1000)
    {
        return FileUtils::getSquareThumbnailUrlFromFile($this->baseFile, $maxDimension);
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
