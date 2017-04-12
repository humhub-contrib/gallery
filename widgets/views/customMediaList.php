<?php

use \humhub\modules\comment\widgets\CommentLink;
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\Module;
use \humhub\modules\like\widgets\LikeLink;
use \humhub\modules\gallery\widgets\MediaListEntry;
use \yii\helpers\Html;

$bundle = Assets::register($this);
$contentContainer = Yii::$app->controller->contentContainer;
$counter = 0;
$rowClosed = true;
?>

<div id="gallery-content" class="col-sm-12">
    <script>console.log("gallery loading")</script>
    <?php if ($gallery->isEmpty()): ?>
        <div class="galleryEmptyMessage">
            <?php if (Yii::$app->controller->canWrite(false)): ?>
                <b><?php echo Yii::t('GalleryModule.base', 'This gallery is still empty.'); ?></b><br/>
                <?php echo Yii::t('GalleryModule.base', 'You can upload images using the buttons above.'); ?>
            <?php else: ?>
                <p style="margin-top:10px;"><strong><?php echo Yii::t('GalleryModule.base', 'This gallery is still empty.'); ?></strong></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php foreach ($gallery->mediaList as $media): ?>
        <?php $creator = Module::getUserById($media->baseFile->created_by); ?>
        <?php
        if ($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif;
        echo MediaListEntry::widget(['entryObject' => $media, 'parentGallery' => $gallery]);
        if (++$counter % 3 === 0) :
            echo '</div>';
            $rowClosed = true;
        endif;
        ?>
    <?php endforeach; ?>
    <?php echo $rowClosed ? "" : '</div>'; ?>
    <script>console.log("gallery loading finished")</script>
</div>
