<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 * 
 * @package humhub.modules.gallery.views
 * @since 1.0
 * @author Sebastian Stumpf
 */
?>

<?php

use \yii\bootstrap\ActiveForm;
use \yii\helpers\Html;
?>

<div class="modal-dialog modal-dialog-small animated fadeIn">
    <div class="modal-content">
        <?php $form = ActiveForm::begin(['id' => 'Gallery', 'class' => 'form-horizontal']); ?>

        <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel">
                <?= Yii::t('GalleryModule.base', '<strong>Confirm</strong> delete item(s)') ?>
            </h4>
        </div>

        <div class="modal-body">
            <?= Yii::t('GalleryModule.base', 'Do you really want to delete this %number% item(s) with all related content?', ['%number%' => count($selectedItems)]) ?>
        </div>

        <?php
        if (is_array($selectedItems)):
            foreach ($selectedItems as $index => $item):
                echo Html::hiddenInput('selected[]', $item);
            endforeach;
        endif;
        ?>

        <div class="modal-footer">

            <button href="#" class="btn btn-primary" data-action-click="ui.modal.submit" data-ui-loader type="submit"
                    data-action-url="<?= $this->context->contentContainer->createUrl('delete-multiple', ['open-gallery-id' => $openGalleryId, 'confirm' => true]) ?>">
                        <?= \Yii::t('GalleryModule.base', 'Delete') ?>
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                <?= Yii::t('GalleryModule.base', 'Close') ?>
            </button>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>