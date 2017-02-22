<?php

use \yii\helpers\Html;

$counter = 0;
$rowClosed = true;
?>

<div id="gallery-content" class="col-sm-12">
    <?php if ($gallery->isEmpty()): ?>
        <div class="galleryEmptyMessage">
            <p><strong><?php echo Yii::t('GalleryModule.base', 'No content has been uploaded yet.'); ?></strong></p>
        </div>
    <?php endif; ?>
    <?php foreach ($gallery->mediaList as $media): ?>
        <?php
        if ($counter % 6 === 0) :
            echo '<div class="row" style="margin-left:-20px; margin-right:-20px;">';
            $rowClosed = false;
        endif;
        ?>
        <div class="col-sm-2 galleryMediaFile" style="padding: 5px;">
            <a href="<?php echo $media->getUrl(); ?>#.jpeg" data-type="image" data-toggle="lightbox" data-parent="#gallery-content"  data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>" data-ui-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
               data-footer='<p style="overflow:hidden; text-overflow:ellipsis;"><strong><?php echo Html::encode(Html::encode($media->title)); ?></strong></p><?php echo $media->description != "" ? "<p>" . Html::encode(Html::encode($media->description)) . "</p>" : ""; ?><button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('GalleryModule.base', 'Close'); ?></button>'>
                <img style="width:100%;" src="<?php echo $media->getSquareThumbnailUrl(); ?>" />
            </a>
        </div>
        <?php
        if (++$counter % 6 === 0) :
            echo '</div>';
            $rowClosed = true;
        endif;
        ?>
    <?php endforeach; ?>
    <?php echo $rowClosed ? "" : '</div>'; ?>
</div>
