<?php

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\forms\ContainerSettings;
use humhub\widgets\bootstrap\Button;
use humhub\widgets\form\ActiveForm;
use humhub\widgets\form\ContentHiddenCheckbox;

/* @var $settings ContainerSettings */
/* @var $contentContainer ContentContainerActiveRecord */

$overviewUrl = Url::toGalleryOverview($this->context->contentContainer);
?>

<div class="panel panel-default">
    <div class="panel-heading">
        <?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> settings')?>
    </div>
    <div class="panel-body">
        <?= Button::back($overviewUrl, Yii::t('GalleryModule.base', 'Back to overview'))->sm() ?>
        <br />
        <?php if($settings->hasGallery()) : ?>
            <?php $form = ActiveForm::begin() ?>
                <?= $form->field($settings, 'snippetGallery')->dropDownList($settings->getGallerySelection())?>
                <?= $form->field($settings, 'snippetSortOrder')->textInput(['type' => 'number']) ?>
                <?= $form->field($settings, 'hideSnippet')->checkbox() ?>
                <?= $form->field($settings, 'sortByCreated')->checkbox() ?>
                <?= $form->field($settings, 'contentHiddenDefault')->widget(ContentHiddenCheckbox::class, [
                    'type' => ContentHiddenCheckbox::TYPE_CONTENTCONTAINER,
                ]) ?>
                <br />
                <?= Button::save()->submit() ?>
            <?php ActiveForm::end() ?>
        <?php else : ?>
            <?= Yii::t('GalleryModule.base', 'There are no galleries available for this space. In order to configure the gallery snippet, please <a href="{createLink}">create</a> a new gallery', [
                'createLink' => $overviewUrl
            ]) ?>
        <?php endif ?>
    </div>
</div>
