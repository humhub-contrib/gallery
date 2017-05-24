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
?>

<?php

use \humhub\modules\gallery\assets\Assets;

$bundle = Assets::register($this);
?>

<div id="gallery-list" class="col">
    <div class="row">
        <?php echo \humhub\modules\gallery\widgets\GalleryListEntryAdd::widget(['parentGallery' => $parentGallery]) ?>
        <?php foreach ($entryList as $entry): ?>
            <?php echo \humhub\modules\gallery\widgets\GalleryListEntry::widget(['entryObject' => $entry, 'parentGallery' => $parentGallery]) ?>
        <?php endforeach; ?>
    </div>
</div>
