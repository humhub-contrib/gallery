<?php

namespace humhub\modules\gallery\models\forms;

use \yii\base\Model;

/**
 * ConfigureForm defines the configurable fields for the gallery module.
 * 
 * @deprecated IS DUMMY IMPLEMENTATION.
 *            
 * @package humhub\modules\gallery\models\forms
 * @since 1.0
 * @author Sebastian Stumpf
 */
class ConfigureForm extends Model
{

    public $dummy;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [
                'dummy',
                'boolean'
            ]
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'dummy' => 'Dummy config option'
        ];
    }

}
