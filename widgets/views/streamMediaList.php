<?php

use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\libs\FileUtils;
use \humhub\modules\gallery\Module;


$bundle = Assets::register($this);
$counter = 0;
$rowClosed = true;
?>

<div id="gallery-list" class="col-sm-12">
    <?php if ($gallery->isEmpty()): ?>
        <div class="galleryEmptyMessage">
            <b><?php echo Yii::t('GalleryModule.base', 'This gallery is empty.'); ?></b><br/>
            <?php echo Yii::t('GalleryModule.base', 'No images have been posted to the stream yet :(.'); ?>
        </div>
    <?php endif; ?>
    <?php foreach ($gallery->fileList as $file): ?>
        <?php
        $creator = Module::getUserById($file->created_by);
        $baseObject = FileUtils::getBaseObject($file);
        $baseContent = FileUtils::getBaseContent($file);
        ?>
        <?php
        if ($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif;
        echo humhub\modules\gallery\widgets\GalleryListEntry::widget(['entryObject' => $file, 'parentGallery' => $gallery]);
        if (++$counter % 3 === 0) :
            echo '</div>';
            $rowClosed = true;
        endif;
        ?>
    <?php endforeach; ?>
<?php echo $rowClosed ? "" : '</div>'; ?>
</div>
