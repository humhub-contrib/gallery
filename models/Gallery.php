<?php
namespace humhub\modules\gallery\models;

use Yii;
use humhub\modules\user\models\User;
use humhub\modules\content\components\ContentActiveRecord;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "gallery_gallery".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $sort_order
 *
 * @package humhub.modules.gallery.models
 * @since 1.0
 * @author Sebastian Stumpf
 */
class Gallery extends ContentActiveRecord
{

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

    public function getPreviewImageUrl()
    {
        $media = Media::find()
            ->where(['gallery_id' => $this->id])
            ->orderBy(['sort_order' => SORT_ASC])
            ->one();
        if($media != null) {
            return $media->getQuadraticThumbnailUrl();
        } else {
            $path = Yii::$app->getModule('gallery')->getAssetsUrl() . '/resources';
            $path = $path . '/file-picture-o.svg';
            return $path;
        }
    }

    public function getMediaList()
    {
        return $this->hasMany(Media::className(), [
            'gallery_id' => 'id'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        foreach ($this->mediaList as $media) {
            $media->delete();
        }
        
        return parent::beforeDelete();
    }

    public function getItemId()
    {
        return 'gallery_' . $this->id;
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
}
