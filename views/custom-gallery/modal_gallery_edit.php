<?php

use \humhub\compat\CActiveForm;
use \humhub\widgets\AjaxButton;
use \yii\web\JsExpression;
?>

<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php
        $form = CActiveForm::begin([
                    'id' => 'Gallery',
                    'class' => 'form-horizontal'
        ]);
        ?>
        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
                <?php if ($gallery->isNewRecord): ?>
                    <?php echo Yii::t('GalleryModule.base', '<strong>Create</strong> gallery'); ?>
                <?php else: ?>
                    <?php echo Yii::t('GalleryModule.base', '<strong>Edit</strong> gallery'); ?>
                <?php endif; ?>
            </h4>
        </div>

        <div class="modal-body">
            <?php echo $form->field($gallery, 'title'); ?>
            <?php echo $form->field($gallery, 'description')->textArea(); ?>
            <?php echo $form->field($gallery->content, 'visibility')->checkbox(['label' => Yii::t('GalleryModule.base', 'Make this gallery public')]); ?>
        </div>

        <div class="modal-footer">
            <?php
            echo AjaxButton::widget([
                'label' => Yii::t('GalleryModule.base', 'Save'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new JsExpression('function(){ setModalLoader(); }'),
                    'success' => new JsExpression('function(html){ $("#globalModal").modal("hide"); $("#gallery-container").html(html);}'),
                    'url' => $this->context->contentContainer->createUrl('/gallery/custom-gallery/edit', [
                        'item-id' => $gallery->getItemId(),
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
        <?php CActiveForm::end() ?>
    </div>
</div>