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

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\forms\GalleryEditForm;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm; ?>

<?php

/* @var $galleryForm GalleryEditForm */
/* @var $contentContainer ContentContainerActiveRecord */

$gallery = $galleryForm->instance;

$title = $gallery->isNewRecord
    ? Yii::t('GalleryModule.base', '<strong>Add</strong> new gallery')
    : Yii::t('GalleryModule.base', '<strong>Edit</strong> gallery');

ModalDialog::begin([
    'header' => $title,
    'animation' => 'fadeIn',
    'size' => 'small']);
?>
    <?php $form = ActiveForm::begin(['id' => 'Gallery', 'class' => 'form-horizontal']); ?>

        <div class="modal-body">
            <?= $form->field($gallery, 'title' )->label(Yii::t('GalleryModule.base', 'title')); ?>
            <?= $form->field($gallery, 'description' )->textArea()->label(Yii::t('GalleryModule.base', 'description')); ?>
            <?= $form->field($galleryForm, 'visibility')->checkbox(['label' => Yii::t('GalleryModule.base', 'Make this gallery public')])?>
        </div>

        <div class="modal-footer">
            <?= ModalButton::submitModal(Url::toEditCustomGallery($contentContainer, $gallery->id)) ?>
            <?= ModalButton::cancel() ?>
        </div>
    <?php ActiveForm::end(); ?>
<?php ModalDialog::end(); ?>
