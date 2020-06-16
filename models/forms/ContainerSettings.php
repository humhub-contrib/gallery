<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\gallery\models\forms;

use humhub\components\SettingsManager;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\gallery\models\CustomGallery;
use Yii;
use yii\base\Model;
use yii\helpers\Html;

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 25.05.2017
 * Time: 23:09
 */
class ContainerSettings extends Model
{
    const SETTING_HIDE_SNIPPET = 'hideSnippet';
    const SETTING_GALLERY_ID = 'galleryId';
    const SETTING_SORT_ORDER= 'sortOrder';
    const SORT_MIN = 0;
    const SORT_MAX = 5000;
    /**
     * @var ContentContainerActiveRecord $contentContainer of this snippet
     */
    public $contentContainer;

    /**
     * @var int gallery id for snippet
     */
    public $snippetGallery;

    /**
     * @var int determines if the snippet should be hidden
     */
    public $hideSnippet;

    /**
     * @var integer defines the sorting priority of the gallery; It accepts in range [0, 100, 200, 300]
     */
    public $sortOrder;

    /**
     * @var SettingsManager module setting manager instance
     */
    private $settings;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->hideSnippet = $this->hideSnippet();
        $this->snippetGallery = $this->getSnippetId();
        $this->sortOrder = $this->getSnippetSortOrder();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['snippetGallery', 'hideSnippet'], 'integer'],
            [['snippetGallery'], 'containerGallery'],
            [['sortOrder'], 'validateSortOrder'], //'in', 'range' => [0,100], 'message'=>'Please enter a value from 0 to 300 in increments of 100'
        ];
    }

    public function validateSortOrder($attribute, $params)
    {
        if(!is_numeric((int)$this->$attribute)) {
            $this->addError($attribute, 'It must be an integer');
            return false;
        }
        if($this->$attribute % 100 != 0 && $this->$attribute != 0) {
            $this->addError($attribute, 'Please enter an even number to the number 100');
            return  false;
        }
        if ($this->$attribute < self::SORT_MIN) {
            $this->addError($attribute, 'Please enter a value greater or equal to ' . self::SORT_MIN);
            return false;
        }
        if($this->$attribute > self::SORT_MAX) {
            $this->addError($attribute, 'Please enter a value less or equal to ' . self::SORT_MAX);
            return false;
        }
        return true;
    }

    public function containerGallery($attribute, $params) {
        if(!$this->snippetGallery) {
            return;
        }

        $gallery = CustomGallery::findOne(['id' => $this->snippetGallery]);
        if(!$gallery->content->contentcontainer_id === $this->contentContainer->contentContainerRecord->id) {
            $this->addError($attribute, 'Invalid gallery selection.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'snippetGallery' => Yii::t('GalleryModule.base', 'Choose snippet gallery'),
            'hideSnippet' => Yii::t('GalleryModule.base', 'Don\'t show the gallery snippet in this space.'),
            'sortOrder' => Yii::t('GalleryModule.config', 'Sort order'),
        ];
    }

    public function attributeHints()
    {
        return [
            'snippetGallery' => Yii::t('GalleryModule.base', 'In case the gallery is not visible for the current user, the snippet will use the latest accessible gallery instead.')
        ];
    }

    public function getGallerySelection()
    {
        $galleries = CustomGallery::find()->contentContainer($this->contentContainer)->all();

        $latest = CustomGallery::findLatest($this->contentContainer);
        $visibility = $latest->content->isPublic() ? Yii::t('base', 'Public') : Yii::t('base', 'Private');
        $result = ['0' => Yii::t('GalleryModule.base', 'Latest Gallery - {title} ({visibility})', [
            'title' => Html::encode($latest->title),
            'visibility' => $visibility
        ])];

        foreach ($galleries as $gallery) {
            $visibility = $gallery->content->isPublic() ? Yii::t('base', 'Public') : Yii::t('base', 'Private');
            $result[$gallery->id] = Html::encode($gallery->title).' ('.$visibility.')';
        }

        return $result;
    }

    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $this->getSettings()->set(self::SETTING_GALLERY_ID, $this->snippetGallery);
        $this->getSettings()->set(self::SETTING_HIDE_SNIPPET, $this->hideSnippet);
        $this->getSettings()->set(self::SETTING_SORT_ORDER, $this->sortOrder);

        return true;
    }

    public function hideSnippet()
    {
        return $this->getSettings()->get(self::SETTING_HIDE_SNIPPET, 0);
    }

    /**
     * @return SettingsManager
     */
    protected function getSettings()
    {
        return ($this->settings) ? $this->settings : $this->settings =  Yii::$app->getModule('gallery')->settings->contentContainer($this->contentContainer);
    }

    public function getSnippetId()
    {
        return $this->getSettings()->get(self::SETTING_GALLERY_ID, 0);
    }

    public function getSnippetSortOrder()
    {
        return $this->getSettings()->get(self::SETTING_SORT_ORDER, 0);
    }

    public function hasGallery()
    {
        return CustomGallery::find()->contentContainer($this->contentContainer)->count() > 0;
    }

    public function getSnippetGallery()
    {
        $galleryId = $this->getSnippetId();

        if(!$galleryId) {
            $gallery = CustomGallery::findLatest($this->contentContainer);
        } else {
            $gallery = CustomGallery::findOne(['id' => $galleryId]);
        }

        if($gallery && !$gallery->content->canView()) {
            $gallery = CustomGallery::find()->contentContainer($this->contentContainer)->readable()->one();
        }

        return $gallery;
    }

    public function getLatest()
    {

    }

}
