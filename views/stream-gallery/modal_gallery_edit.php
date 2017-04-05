<?php \humhub\widgets\ModalDialog::begin([
    'header' =>  Yii::t('GalleryModule.base', '<strong>Edit</strong> gallery'),
    'animation' => 'fadeIn',
    'size' => 'small']) ?>

    <?php $form = \yii\bootstrap\ActiveForm::begin(['id' => 'Gallery', 'class' => 'form-horizontal']); ?>

        <div class="modal-body">
            <?php echo $form->field($gallery, 'title'); ?>
            <?php echo $form->field($gallery, 'description')->textArea(); ?>
        </div>

        <div class="modal-footer">
            <button href="#" class="btn btn-primary" data-action-click="ui.modal.submit" data-ui-loader type="submit"
               data-action-url="<?= $contentContainer->createUrl('/gallery/stream-gallery/edit', ['item-id' => $gallery->getItemId(), 'open-gallery-id' => $openGalleryId]) ?>">
                   <?= \Yii::t('GalleryModule.base', 'Save'); ?>
            </button>
            <button type="button" class="btn btn-primary" data-dismiss="modal">
                <?= \Yii::t('GalleryModule.base', 'Close'); ?>
            </button>
        </div>
    <?php \yii\bootstrap\ActiveForm::end() ?>

<?php \humhub\widgets\ModalDialog::end() ?>