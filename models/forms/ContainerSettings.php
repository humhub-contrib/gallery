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
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\Module;
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
    const SETTING_SORT_ORDER= 'snippetSortOrder';
    const SETTING_SORT_BY_CREATED = 'sortByCreated';
    const SETTING_CONTENT_HIDDEN_DEFAULT = 'contentHiddenDefault';
    const SORT_MIN = 0;
    const SORT_MAX = 32000;
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
     * @var integer defines the sort order snippet for the gallery;
     */
    public $snippetSortOrder;

    /**
     * @var int should sort by the gallery created date
     */
    public $sortByCreated;

    /**
     * @var SettingsManager module setting manager instance
     */
    private $settings;

    /**
     * @var bool Default setting to hide media files on stream
     */
    public $contentHiddenDefault;


    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->hideSnippet = $this->hideSnippet();
        $this->snippetGallery = $this->getSnippetId();
        $this->snippetSortOrder = $this->getSnippetSortOrder();
        $this->sortByCreated = $this->getSortByCreated();
        $this->contentHiddenDefault = $this->getContentHiddenDefault();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['snippetGallery', 'hideSnippet'], 'integer'],
            [['snippetGallery'], 'containerGallery'],
            [['snippetSortOrder'], 'number', 'min' => static::SORT_MIN, 'max' => static::SORT_MAX],
            ['sortByCreated', 'integer'],
            [['contentHiddenDefault'], 'boolean']
        ];
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
            'snippetSortOrder' => Yii::t('GalleryModule.base', 'Sort order'),
            'sortByCreated' => Yii::t('GalleryModule.base', 'Sort Galleries by Created Date'),
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

        $this->snippetSortOrder = $this->snippetSortOrder ? $this->snippetSortOrder : self::SORT_MIN;

        $this->getSettings()->set(self::SETTING_GALLERY_ID, $this->snippetGallery);
        $this->getSettings()->set(self::SETTING_HIDE_SNIPPET, $this->hideSnippet);
        $this->getSettings()->set(self::SETTING_SORT_ORDER, $this->snippetSortOrder);
        $this->getSettings()->set(self::SETTING_SORT_BY_CREATED, $this->sortByCreated);
        $this->getSettings()->set(self::SETTING_CONTENT_HIDDEN_DEFAULT, $this->contentHiddenDefault);

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

    public function getSortByCreated()
    {
        return $this->getSettings()->get(self::SETTING_SORT_BY_CREATED, 0);
    }

    public function getContentHiddenDefault(): bool
    {
        /* @var Module $module */
        $module = Yii::$app->getModule('gallery');
        return (bool) $this->getSettings()->get(self::SETTING_CONTENT_HIDDEN_DEFAULT, $module->getContentHiddenGlobalDefault());
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

}
