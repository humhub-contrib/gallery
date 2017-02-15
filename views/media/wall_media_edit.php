<?php

use \humhub\compat\CActiveForm;
use \humhub\modules\gallery\widgets\MediaPreview;
use \humhub\widgets\AjaxButton;
use \humhub\widgets\RichText;
use \humhub\widgets\RichTextEditor;
use \yii\web\JsExpression;
?>

<div class="content_edit" id="gallery_edit_media_<?php echo $media->id; ?>">

    <?php $form = CActiveForm::begin(); ?>
    <div style="margin-bottom: 10px">

        <!-- create contenteditable div for HEditorWidget to place the data -->
        <div id="gallery_media_description_<?php echo $media->id; ?>_contenteditable" class="form-control atwho-input"
             contenteditable="true"><?php echo RichText::widget(['text' => $media->description, 'edit' => true]); ?></div>

        <?php echo $form->field($media, 'description')->label(false)->textArea(array('class' => 'form-control', 'id' => 'gallery_media_description_' . $media->id, 'placeholder' => Yii::t('GalleryModule.base', 'Edit the media description...'))); ?>

        <?= RichTextEditor::widget(['id' => 'gallery_media_description_' . $media->id, 'inputContent' => $media->description, 'record' => $media]); ?>

    </div>

    <div class="preview">
        <?php echo MediaPreview::widget(['file' => $media, 'width' => 600, 'height' => 350, 'htmlConf' => ['class' => 'preview', 'id' => 'gallery-wallout-media-preview-' . $media->id]]); ?>
    </div>

    <h5><?php echo $form->field($media, 'title')->label(false); ?></h5>


    <?php
    echo AjaxButton::widget([
        'label' => Yii::t('GalleryModule.base', 'Save'),
        'ajaxOptions' => [
            'type' => 'POST',
            'beforeSend' => new JsExpression('function(html){ }'),
            'success' => new JsExpression('function(html){$(".wall_' . $media->getUniqueId() . '").replaceWith(html); }'),
            'statusCode' => ['400' => new JsExpression('function(xhr) { }')],
            'url' => $contentContainer->createUrl('/gallery/media/edit', ['open-gallery-id' => $openGalleryId, 'item-id' => $media->getItemId(), 'fromWall' => 1])
        ],
        'htmlOptions' => [
            'class' => 'btn btn-primary'
        ]
    ]);
    ?>

    <?php
    echo AjaxButton::widget([
        'label' => Yii::t('GalleryModule.base', 'Close'),
        'ajaxOptions' => [
            'type' => 'GET',
            'success' => new JsExpression('function(html){$(".wall_' . $media->getUniqueId() . '").replaceWith(html); }'),
            'statusCode' => ['400' => new JsExpression('function(xhr) { }')],
            'url' => $contentContainer->createUrl('/gallery/media/edit', ['open-gallery-id' => $openGalleryId, 'item-id' => $media->getItemId(), 'fromWall' => 1, 'cancel' => 1])
        ],
        'htmlOptions' => [
            'class' => 'btn btn-primary'
        ]
    ]);
    ?>

    <?php CActiveForm::end() ?>

</div>