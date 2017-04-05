<?php \humhub\widgets\ModalDialog::begin([
    'header' =>  Yii::t('CfilesModule.base', '<strong>Edit</strong> media'),
    'animation' => 'fadeIn',
    'size' => 'small']) ?>

    <?php $form = \yii\bootstrap\ActiveForm::begin(); ?>

        <div class="modal-body">
            <?php echo $form->field($media, 'title'); ?>
            <?php echo $form->field($media, 'description')->textArea(); ?>
        </div>

        <div class="modal-footer">
            <button href="#" class="btn btn-primary" data-action-click="ui.modal.submit" data-ui-loader type="submit"
               data-action-url="<?= $contentContainer->createUrl('/gallery/media/edit', ['item-id' => $media->getItemId(), 'open-gallery-id' => $openGalleryId, 'fromWall' => $fromWall]) ?>">
                   <?= \Yii::t('GalleryModule.base', 'Save'); ?>
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                <?= \Yii::t('GalleryModule.base', 'Close'); ?>
            </button>
        </div>
    <?php \yii\bootstrap\ActiveForm::end() ?>

<?php \humhub\widgets\ModalDialog::end() ?>