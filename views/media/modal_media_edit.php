<?php

use \humhub\widgets\AjaxButton;
use \yii\bootstrap\ActiveForm;
use \yii\web\JsExpression;
?>

<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'Media',
                    'class' => 'form-horizontal'
        ]);
        ?>
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
                <?php if ($media->isNewRecord): ?>
                    <?php echo Yii::t('GalleryModule.base', '<strong>Add</strong> picture'); ?>
                <?php else: ?>
                    <?php echo Yii::t('GalleryModule.base', '<strong>Edit</strong> picture'); ?>
                <?php endif; ?>
            </h4>
        </div>

        <div class="modal-body">
            <?php echo $form->field($media, 'title'); ?>
            <?php echo $form->field($media, 'description')->textArea(); ?>
        </div>

        <div class="modal-footer">
            <?php
            echo AjaxButton::widget([
                'label' => Yii::t('GalleryModule.base', 'Save'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new JsExpression('function(){ setModalLoader(); }'),
                    'success' => new JsExpression('function(html){ $("#globalModal").modal("hide"); $("#gallery-container").html(html);}'),
                    'url' => $this->context->contentContainer->createUrl('/gallery/media/edit', [
                        'item-id' => $media->getItemId(),
                        'open-gallery-id' => $openGalleryId
                    ])
                ],
                'htmlOptions' => [
                    'class' => 'btn btn-primary'
                ]
            ]);
            ?>
            <button type="button" class="btn btn-primary"
                    data-dismiss="modal">
                        <?php echo Yii::t('GalleryModule.base', 'Close'); ?>
            </button>

        </div>
        <?php ActiveForm::end() ?>
    </div>
</div>