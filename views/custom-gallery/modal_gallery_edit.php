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

/* @var $galleryForm \humhub\modules\gallery\models\forms\GalleryEditForm */
?>

<?php
\humhub\widgets\ModalDialog::begin([
    'header' => $galleryForm->instance->isNewRecord ? Yii::t('GalleryModule.base', '<strong>Add</strong> new gallery') : Yii::t('GalleryModule.base', '<strong>Edit</strong> gallery'),
    'animation' => 'fadeIn',
    'size' => 'small']);
?>
    <?php $form = \yii\bootstrap\ActiveForm::begin(['id' => 'Gallery', 'class' => 'form-horizontal']); ?>

        <div class="modal-body">
            <?= $form->field($galleryForm->instance, 'title'); ?>
            <?= $form->field($galleryForm->instance, 'description')->textArea(); ?>
            <?= $form->field($galleryForm, 'visibility')->checkbox(['label' => Yii::t('GalleryModule.base', 'Make this gallery public')])?>
        </div>

        <div class="modal-footer">
            <button class="btn btn-primary" data-action-click="ui.modal.submit" data-ui-loader type="submit"
                    data-action-url="<?= $contentContainer->createUrl('/gallery/custom-gallery/edit', ['itemId' => $galleryForm->instance->getItemId()]) ?>">
                        <?= \Yii::t('GalleryModule.base', 'Save'); ?>
            </button>
            <button type="button" class="btn btn-primary" data-modal-close>
                <?= \Yii::t('GalleryModule.base', 'Close'); ?>
            </button>
        </div>
    <?php \yii\bootstrap\ActiveForm::end(); ?>
<?php \humhub\widgets\ModalDialog::end(); ?>