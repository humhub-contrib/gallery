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
use humhub\modules\gallery\widgets\GalleryListEntry;
use humhub\modules\gallery\widgets\GalleryListEntryAdd;

$bundle = Assets::register($this);
?>

<div id="gallery-list" class="col">
    <div class="row">
        <?= GalleryListEntryAdd::widget(['parentGallery' => $parentGallery]) ?>
        <?php foreach ($entryList as $entry): ?>
            <?= GalleryListEntry::widget(['entryObject' => $entry, 'parentGallery' => $parentGallery]) ?>
        <?php endforeach; ?>
    </div>
</div>


