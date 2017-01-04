<?php
namespace humhub\modules\gallery\models;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentActiveRecord;
use yii\helpers\FileHelper;
use humhub\modules\gallery\libs\FileUtils;

/**
 * This is the abstract model class for table "gallery_gallery".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 * @property integer $thumb_file_id
 * @property integer $editable_by
 * @property integer $type
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class BaseGallery extends ContentActiveRecord
{

    const TYPE_CUSTOM_GALLERY = 1;

    const TYPE_STREAM_GALLERY = 2;
    
    /**
     * Overwrite!
     */
    public function getUrl()
    {}
    
    /**
     * Overwrite!
     */
    public function getItemId()
    {}

    /**
     * Overwrite!
     */
    public function getPreviewImageUrl()
    {}

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
            [
                'title',
                'required'
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
            ],
            [
                [
                    'sort_order',
                    'thumb_file_id',
                    'editable_by',
                    'type',
                ],
                'safe'
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
            'title' => 'Title',
            'description' => 'Description'
        ];
    }

    public function getCreator()
    {
        return User::findOne([
            'id' => $this->content->created_by
        ]);
    }

    public function getEditor()
    {
        return User::findOne([
            'id' => $this->content->updated_by
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getContentName()
    {
        return Yii::t('GalleryModule.base', "Gallery");
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
        $path = Yii::$app->getModule('gallery')->getAssetsUrl() . '/resources';
        $path = $path . '/file-picture-o.svg';
        return $path;
    }

    protected function getPreviewImageUrlFromThumbFileId()
    {
        // search for file by given thumbnail id
        if ($this->thumb_file_id !== null) {
            $file = File::findOne($this->thumb_file_id);
            // set thumb image id not found
            if ($file !== null) {
                return FileUtils::getSquareThumbnailUrlFromFile($file);
            } else {
                // save with id null if nthumbfile not found
                $this->thumb_file_id = null;
                $this->save();
            }
        }
        return null;
    }
}
