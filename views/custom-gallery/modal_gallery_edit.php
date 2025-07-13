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
use humhub\widgets\form\ContentVisibilitySelect;
use humhub\widgets\form\SortOrderField;
use humhub\widgets\modal\Modal;
use humhub\widgets\modal\ModalButton;

?>

<?php

/* @var $galleryForm GalleryEditForm */
/* @var $sortByCreated int */
/* @var $createdAt string */
/* @var $contentContainer ContentContainerActiveRecord */

$gallery = $galleryForm->instance;

$title = $gallery->isNewRecord
    ? Yii::t('GalleryModule.base', '<strong>Add</strong> new gallery')
    : Yii::t('GalleryModule.base', '<strong>Edit</strong> gallery');
?>

<?php $form = Modal::beginFormDialog([
    'title' => $title,
    'footer' => ModalButton::cancel() . ' ' . ModalButton::save()->submit(Url::toEditCustomGallery($contentContainer, $gallery->id)),
    'form' => ['id' => 'Gallery', 'class' => 'form-horizontal'],
]); ?>

    <?= $form->field($gallery, 'title')->textInput(['autofocus' => ''])->label(Yii::t('GalleryModule.base', 'Title')); ?>
    <?= $form->field($gallery, 'description')->textArea()->label(Yii::t('GalleryModule.base', 'Description')); ?>
    <?php if (!$sortByCreated): ?>
        <?= $form->field($gallery, 'sort_order')->widget(SortOrderField::class, []); ?>
    <?php endif ?>
    <?= $form->field($galleryForm, 'visibility')->widget(ContentVisibilitySelect::class, ['contentOwner' => 'instance']); ?>

    <?php if ($createdAt): ?>
        <p><?= Yii::t('GalleryModule.base', 'Gallery created at:') . ' ' . $createdAt ?> </p>
    <?php endif ?>

<?php Modal::endFormDialog(); ?>
