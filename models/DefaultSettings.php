<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by FukycraM (marc.fun)
 * Date: 18.12.2019
 * Time: 13:00
 */

namespace humhub\modules\gallery\models;

use humhub\components\SettingsManager;
use humhub\modules\content\components\ContentContainerActiveRecord;
use Yii;
use yii\base\Model;

class DefaultSettings extends Model
{
    const SETTING_MODULE_LABEL = 'defaults.moduleLabel';
    const SETTING_MODULE_SORT_PRIORITY = 'defaults.moduleSortPriority';
    const SETTINGS_MODULE_SORT_PRIORITY_RANGE = [0, 100, 200, 300];

    /**
     * @var ContentContainerActiveRecord
     */
    public $contentContainer;

    /**
     * @var string
     */
    public $module_label;

    /**
     * @var integer defines the sorting priority of the gallery; It accepts in range [0, 100, 200, 300]
     */
    public $module_sort_priority;

    public $module;


    public function init()
    {
        $this->module = Yii::$app->getModule('gallery');

        $this->module_label = $this->getSettings()->get(
            self::SETTING_MODULE_LABEL,
            Yii::t('GalleryModule.base', 'Gallery')
        );

        $this->module_sort_priority = (int) $this->getSettings()->get(
            self::SETTING_MODULE_SORT_PRIORITY,
            Yii::t('GalleryModule.base', 'Gallery')
        );
    }

    /**
     * @return SettingsManager
     */
    private function getSettings()
    {
        return $this->module->settings->contentContainer($this->contentContainer);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['module_label'], 'string'],
            [['module_sort_priority'], 'in', 'range' => self::SETTINGS_MODULE_SORT_PRIORITY_RANGE, 'message'=>'Please enter a value from 0 to 300 in increments of 100'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'module_label' => Yii::t('GalleryModule.config', 'Module name'),
            'module_sort_priority' => Yii::t('GalleryModule.config', 'Sort priority'),
        ];
    }

    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        $this->getSettings()->set(
            self::SETTING_MODULE_LABEL,
            $this->module_label
//            $this->module_sort_priority
        );
        $this->getSettings()->set(
            self::SETTING_MODULE_SORT_PRIORITY,
            $this->module_sort_priority
        );
        return true;
    }

    public function getSubmitUrl()
    {
        return $this->contentContainer->createUrl('/gallery/container-config');
    }
}
