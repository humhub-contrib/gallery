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
use humhub\widgets\form\ContentHiddenCheckbox;
use humhub\widgets\modal\Modal;
use humhub\widgets\modal\ModalButton;

/* @var boolean $fromWall */
/* @var Media $media */
/* @var ContentContainerActiveRecord $contentContainer */
?>

<?php $form = Modal::beginFormDialog([
    'title' => Yii::t('GalleryModule.base', '<strong>Edit</strong> media'),
    'footer' => ModalButton::cancel() . ' ' . ModalButton::save()->submit(Url::toEditMedia($contentContainer, $media, $fromWall)),
]); ?>

    <?= $form->field($media, 'description')->textArea(); ?>
    <?= $form->field($media, 'hidden')->widget(ContentHiddenCheckbox::class); ?>

<?php Modal::endFormDialog(); ?>
