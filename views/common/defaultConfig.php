<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */
/* @var $this yii\web\View */
/* @var $model \humhub\modules\gallery\models\DefaultSettings */

\humhub\modules\gallery\assets\Assets::register($this);

use humhub\widgets\ActiveForm;
use humhub\widgets\Button;
?>

<div class="panel panel-default">

    <div class="panel-heading"><?= Yii::t('GalleryModule.config', 'Default gallery settings'); ?></div>

    <div class="panel-body" data-ui-widget="calendar.Form">
        <?php $form = ActiveForm::begin(['action' => $model->getSubmitUrl()]); ?>
            <div class="help-block">
                <?= Yii::t('GalleryModule.config', 'Here you can configure default settings for the gallery module.') ?>
            </div>

            <hr>

            <?= $form->field($model, 'module_sort_priority')->textInput(['type' => 'number', 'min' => 0, 'max' => 300, 'placeholder' => Yii::t('GalleryModule.base', 'from 0 to 300 in increments of 100'), 'maxLength' => 4]) ?>
            <?= $form->field($model, 'module_label')->textInput(['maxlength' => 50]) ?>

            <?= Button::primary(Yii::t('base', 'Save'))->submit() ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>
