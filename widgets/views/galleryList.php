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

use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\helpers\ContentContainerHelper;
use \humhub\modules\gallery\assets\Assets;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\BaseGallery;
use humhub\modules\gallery\widgets\GalleryListEntry;
use humhub\modules\gallery\widgets\GalleryListEntryAdd;
use humhub\widgets\Button;
use humhub\widgets\LinkPager;

Assets::register($this);

/* @var boolean $showMore */
/* @var array $entryList */
/* @var array $entriesOnly */
/* @var BaseGallery $parentGallery */

/* @var ContentContainerActiveRecord $container */
$container = ContentContainerHelper::getCurrent();
?>

<?php if($entriesOnly) :?>
    <?php foreach ($entryList as $entry): ?>
        <?= GalleryListEntry::widget(['entryObject' => $entry, 'parentGallery' => $parentGallery]) ?>
    <?php endforeach; ?>
<?php else: ?>
    <div id="gallery-list" class="col">
        <div id="gallery-media-container" class="row">
            <?= GalleryListEntryAdd::widget(['parentGallery' => $parentGallery]) ?>
            <?php foreach ($entryList as $entry): ?>
                <?= GalleryListEntry::widget(['entryObject' => $entry, 'parentGallery' => $parentGallery]) ?>
            <?php endforeach; ?>
        </div>

        <?php if($showMore) : ?>
            <?php $showMoreUrl = is_string($showMore) ? $showMore : Url::toLoadGalleryPage($container) ?>
            <div style="text-align:center">
                <?= Button::primary(Yii::t('GalleryModule.base', 'Show more'))->action('gallery.showMore', $showMoreUrl) ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>
