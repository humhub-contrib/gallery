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

use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm;

?>

<?php
ModalDialog::begin([
    'header' => Yii::t('GalleryModule.base', '<strong>Edit</strong> media'),
    'animation' => 'fadeIn',
    'size' => 'small']);
?>
<?php $form = ActiveForm::begin(); ?>

<div class="modal-body">
    <?= $form->field($media, 'description')->textArea(); ?>
</div>

<div class="modal-footer">
    <button href="#" class="btn btn-primary" data-action-click="ui.modal.submit" data-ui-loader type="submit"
            data-action-url="<?= $contentContainer->createUrl('/gallery/media/edit', ['itemId' => $media->getItemId(), 'openGalleryId' => $openGalleryId, 'fromWall' => $fromWall]) ?>">
                <?= \Yii::t('GalleryModule.base', 'Save'); ?>
    </button>
    <button type="button" class="btn btn-primary" data-dismiss="modal">
        <?= \Yii::t('GalleryModule.base', 'Close'); ?>
    </button>
</div>
<?php ActiveForm::end(); ?>
<?php ModalDialog::end(); ?>