<?php

use \humhub\modules\gallery\widgets\GalleryPreview;
use \humhub\widgets\RichText;
use \yii\helpers\Html;
?>

<div class="gallery-wallout-gallery">
    <div class="gallery-wallentry-header" style="overflow: hidden; margin-bottom: 20px;">    
        <h1><strong><?php echo Html::encode($gallery->title); ?></strong></h1>    
        <div><?php echo RichText::widget(['text' => (trim($gallery->description) ? $gallery->description : ''), 'record' => $gallery]); ?></div>
    </div>
    <div class="gallery-wallentry-content" id="gallery-wallout-gallery-content-<?php echo $gallery->id; ?>" style="overflow: hidden;">
        <div class="preview">
            <?php echo GalleryPreview::widget(['gallery' => $gallery, 'lightboxDataParent' => "#gallery-wallout-gallery-content-$gallery->id", 'lightboxDataGallery' => "FilesModule-Gallery-$gallery->id"]); ?>
        </div>
    </div>
    <a class="more-link-gallery-wallout-gallery hidden" id="more-link-gallery-wallout-gallery-<?php echo $gallery->id; ?>" data-state="down"
       style="margin: 20px 0 20px 0; display: block;" href="javascript:showMoreFiles(<?php echo $gallery->id; ?>);"><i
            class="fa fa-arrow-down"></i> <?php echo Yii::t('GalleryModule.base', 'Show all files'); ?>
    </a>
    <div class="gallery-wallentry-footer" style="overflow: hidden; margin-bottom: 10px;">
        <div style="overflow: hidden;"><a href="<?php echo $gallery->getUrl(); ?>"><?php echo Yii::t('GalleryModule.base', 'Open gallery!'); ?></a></div>   
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        var _galleryContentHeight = $('#gallery-wallout-gallery-content-<?php echo $gallery->id; ?>').outerHeight();

        if (_galleryContentHeight > 310) {
            // show more-button
            $('#more-link-gallery-wallout-gallery-<?php echo $gallery->id; ?>').removeClass('hidden');
            // set limited height
            $('#gallery-wallout-gallery-content-<?php echo $gallery->id; ?>').css({'display': 'block', 'max-height': '310px'});
        }
    });

    $(document).ajaxComplete(function () {

        var _galleryContentHeight = $('#gallery-wallout-gallery-content-<?php echo $gallery->id; ?>').outerHeight();

        if (_galleryContentHeight > 310) {
            // show more-button
            $('#more-link-gallery-wallout-gallery-<?php echo $gallery->id; ?>').removeClass('hidden');
            // set limited height
            $('#gallery-wallout-gallery-content-<?php echo $gallery->id; ?>').css({'display': 'block', 'max-height': '310px'});
        }
    });

    function showMoreFiles(gallery_id) {

        // set current state
        var _state = $('#more-link-gallery-wallout-gallery-' + gallery_id).attr('data-state');

        if (_state == "down") {

            $('#gallery-wallout-gallery-content-' + gallery_id).css('max-height', '2000px');

            // set new link content
            $('#more-link-gallery-wallout-gallery-' + gallery_id).html('<i class="fa fa-arrow-up"></i> <?php echo Html::encode(Yii::t('GalleryModule.base', 'Collapse')); ?>');

            // update link state
            $('#more-link-gallery-wallout-gallery-' + gallery_id).attr('data-state', 'up');

        } else {
            // set back to limited length
            $('#gallery-wallout-gallery-content-' + gallery_id).css('max-height', '310px');

            // set new link content
            $('#more-link-gallery-wallout-gallery-' + gallery_id).html('<i class="fa fa-arrow-down"></i> <?php echo Html::encode(Yii::t('GalleryModule.base', 'Show all files')); ?>');

            // update link state
            $('#more-link-gallery-wallout-gallery-' + gallery_id).attr('data-state', 'down');

            $('body, html').animate({
                scrollTop: $('#more-link-gallery-wallout-gallery-' + gallery_id).closest('.wall-entry').offset().top - 100
            }, 600);

        }

    }
</script>
