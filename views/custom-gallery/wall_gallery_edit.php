<?php 
use yii\helpers\Html; use humhub\compat\CActiveForm;
use humhub\modules\gallery\widgets\GalleryPreview; 
?>


<div class="content_edit" id="gallery_edit_gallery_<?php echo $gallery->id;?>">
    
    <?php $form=CActiveForm::begin(); ?>
    
    <h5><strong><?php echo $form->field($gallery, 'title')->label(false); ?></strong></h5>
    
    <!-- create contenteditable div for HEditorWidget to place the data -->
    <div id="gallery_gallery_description_<?php echo $gallery->id; ?>_contenteditable" class="form-control atwho-input"
         contenteditable="true"><?php echo \humhub\widgets\RichText::widget(['text' => $gallery->description, 'edit' => true]); ?></div>
    <?php echo $form->field($gallery, 'description')->label(false)->textArea(array('class' => 'form-control', 'id' => 'gallery_gallery_description_' . $gallery->id, 'placeholder' => Yii::t('GalleryModule.base', 'Edit the gallery description...'))); ?>
    <?= \humhub\widgets\RichTextEditor::widget(['id' => 'gallery_gallery_description_' . $gallery->id, 'inputContent' => $gallery->description, 'record' => $gallery]); ?>
    
    <?php echo $form->field($gallery->content, 'visibility')->checkbox(['label' => Yii::t('GalleryModule.base', 'Make this gallery public')]); ?>
    
    <?php echo \humhub\widgets\AjaxButton::widget([
        'label' => Yii::t('GalleryModule.base', 'Save'),
        'ajaxOptions' => [
            'type' => 'POST',
            'beforeSend' => new yii\web\JsExpression('function(html){ }'),
            'success' => new yii\web\JsExpression('function(html){$(".wall_' . $gallery->getUniqueId() . '").replaceWith(html); }'),
            'statusCode' => ['400' => new yii\web\JsExpression('function(xhr) { }')],
            'url' => $contentContainer->createUrl('/gallery/custom-gallery/edit', [ 'open-gallery-id' => $openGalleryId, 'item-id' => $gallery->getItemId(), 'fromWall' => 1 ])
        ],
        'htmlOptions' => [ 
            'class' => 'btn btn-primary' 
        ] 
    ]); ?>
    
    <?php echo \humhub\widgets\AjaxButton::widget([
        'label' => Yii::t('GalleryModule.base', 'Close'),
        'ajaxOptions' => [
            'type' => 'GET',
            'success' => new yii\web\JsExpression('function(html){$(".wall_' . $gallery->getUniqueId() . '").replaceWith(html); }'),
            'statusCode' => ['400' => new yii\web\JsExpression('function(xhr) { }')],
            'url' => $contentContainer->createUrl('/gallery/custom-gallery/edit', [ 'open-gallery-id' => $openGalleryId, 'item-id' => $gallery->getItemId(), 'fromWall' => 1 , 'cancel' => 1])
        ],
        'htmlOptions' => [ 
            'class' => 'btn btn-primary' 
        ] 
    ]); ?>

    <?php CActiveForm::end()?>
    
    <div class="preview">
        <?php echo GalleryPreview::widget(['gallery' => $gallery, 'lightboxDataParent' => "#gallery-wallout-gallery-content-$gallery->id", 'lightboxDataGallery' => "FilesModule-Gallery-$gallery->id"]); ?>
    </div>
</div>
