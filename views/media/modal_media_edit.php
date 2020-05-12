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
use humhub\modules\gallery\models\Media;
use humhub\widgets\ModalButton;
use humhub\widgets\ModalDialog;
use yii\bootstrap\ActiveForm;

/* @var boolean $fromWall */
/* @var Media $media */
/* @var ContentContainerActiveRecord $contentContainer */
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
            <?= ModalButton::submitModal(Url::toEditMedia($contentContainer, $media, $fromWall)) ?>
            <?= ModalButton::cancel() ?>
        </div>

    <?php ActiveForm::end(); ?>

<?php ModalDialog::end(); ?>