<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2023 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\models\forms;

use humhub\modules\gallery\Module;
use Yii;
use yii\base\Model;

/**
 * ConfigureForm defines the global configurable fields.
 */
class ConfigureForm extends Model
{
    public $contentHiddenDefault;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->contentHiddenDefault = $this->getModule()->getContentHiddenGlobalDefault();
    }

    public function getModule(): Module
    {
        return Yii::$app->getModule('gallery');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contentHiddenDefault'], 'boolean'],
        ];
    }

    public function save(): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $this->getModule()->settings->set('contentHiddenGlobalDefault', $this->contentHiddenDefault);

        return true;
    }
}
