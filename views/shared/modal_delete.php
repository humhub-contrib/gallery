<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
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
                echo yii\helpers\Html::hiddenInput('selected[]', $item);
            }
        }
        ?>

        <div class="modal-footer">
            <?php
            echo \humhub\widgets\AjaxButton::widget([
                'label' => Yii::t('GalleryModule.base', 'Delete'),
                'ajaxOptions' => [
                    'type' => 'POST',
                    'beforeSend' => new yii\web\JsExpression('function(){ setModalLoader(); }'),
                    'success' => new yii\web\JsExpression('function(html){ $("#globalModal").modal("hide"); $("#galleryContainer").html(html);}'),
                    'error' => new yii\web\JsExpression('function(data){ $("#globalModal").modal("hide"); updateLog(data.responseJSON.message); }'),
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
                <?php echo Yii::t( 'GalleryModule.base', 'Close'); ?>
            </button>

        </div>
        <?php ActiveForm::end()?>
    </div>
</div>