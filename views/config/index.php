<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
?>

<div class="panel panel-default">

    <div class="panel-heading"><?php echo Yii::t('GalleryModule.base', '<strong>Gallery</strong> module configuration'); ?></div>

    <div class="panel-body">

        <?php
        $form = ActiveForm::begin([
                    'id' => 'configure-form'
        ]);
        ?>

        <div class="form-group">
            <?= $form->field($model, 'dummy')->checkbox(null, false); ?>
        </div>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
