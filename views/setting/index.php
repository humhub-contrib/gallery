<?php
use yii\bootstrap\ActiveForm;
/* @var $settings \humhub\modules\gallery\models\forms\ContainerSettings */
/* @var $contentContainer \humhub\modules\content\components\ContentContainerActiveRecord */
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> settings')?>
    </div>
    <div class="panel-body">
            <a class="btn btn-default btn-sm pull-right" data-ui-loader href="<?= $this->context->contentContainer->createUrl('/gallery/list') ?>">
                <i class="glyphicon glyphicon-arrow-left"></i> <?= Yii::t('GalleryModule.base', 'Back to overview') ?>
            </a>
        <br />
        <?php if($settings->hasGallery()) : ?>
            <?php $form = ActiveForm::begin() ?>
                <?= $form->field($settings, 'snippetGallery')->dropDownList($settings->getGallerySelection())?>
                <?= $form->field($settings, 'hideSnippet')->checkbox() ?>
                <br />
                <button type="submit" class="btn btn-primary" data-ui-loader><?= Yii::t('base', 'Save');?></button>
            <?php ActiveForm::end() ?>
        <?php else : ?>
            <?= Yii::t('GalleryModule.base', 'There are no galleries available for this space. In order to configure the gallery snippet, please <a href="{createLink}">create</a> a new gallery', [
                'createLink' => $contentContainer->createUrl('/gallery/list')
            ]);?>
        <?php endif ?>
    </div>
</div>
