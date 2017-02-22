<?php

use \humhub\widgets\AjaxButton;
use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
use \yii\web\JsExpression;
?>

<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php
        $form = ActiveForm::begin([
                    'id' => 'Gallery',
                    'class' => 'form-horizontal'
        ]);
        ?>
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
                <?php echo Yii::t('GalleryModule.base', '<strong>Confirm</strong> delete item(s)'); ?>
            </h4>
        </div>

        <div class="modal-body">
            <?php echo Yii::t('GalleryModule.base', 'Do you really want to delete this %number% item(s) with all related content?', ['%number%' => count($selectedItems)]); ?>
        </div>

        <?php
        if (is_array($selectedItems)) {
            foreach ($selectedItems as $index => $item) {
                echo Html::hiddenInput('selected[]', $item);
            }
        }
        ?>

        <div class="modal-footer">
            <?php
            echo AjaxButton::widget([
                'label' => Yii::t('GalleryModule.base', 'Delete'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new JsExpression('function(){ setModalLoader(); }'),
                    'success' => new JsExpression('function(html){ $("#globalModal").modal("hide"); $("#gallery-container").html(html);}'),
                    'error' => new JsExpression('function(data){ $("#globalModal").modal("hide"); updateLog(data.responseJSON.message); }'),
                    'url' => $this->context->contentContainer->createUrl('delete-multiple', [
                        'open-gallery-id' => $openGalleryId,
                        'confirm' => true
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