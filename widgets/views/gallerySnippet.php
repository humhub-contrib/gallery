<?php
/* @var $settingsUrl string */
/* @var $snippetId string */
/* @var $galleryUrl string */
/* @var $canWrite boolean */
/* @var $isAdmin boolean */
/* @var $images \humhub\modules\gallery\models\Media[] */

use humhub\libs\Html;

$extraMenus = ($isAdmin) ? '<li><a href="'.$settingsUrl.'"><i class="fa fa-cogs"></i> '. Yii::t('GalleryModule.base', 'Settings') .'</a></li>' : '';
$extraMenus .= '<li><a href="'.$galleryUrl.'"><i class="fa fa-arrow-circle-right"></i> '. Yii::t('GalleryModule.base', 'Open Gallery') .'</a></li>';


?>

<div class="panel panel-default" id="space-gallery-snippet">
    <div class="panel-heading">
        <i class="fa fa-picture-o"></i> <strong><?= Yii::t('GalleryModule.base', 'Gallery') ?></strong>
        <?= \humhub\widgets\PanelMenu::widget(['id' => 'space-gallery-snippet', 'extraMenus' => $extraMenus]); ?>
    </div>
    <div class="panel-body">


        <div id="sidebar-gallery-carousel" class="blueimp-gallery blueimp-gallery-carousel" style="box-shadow: 0 0 2px #000;border-radius:2px;margin:0px">
            <div class="slides"></div>
            <a class="prev">‹</a>
            <a class="next">›</a>
            <a class="play-pause"></a>
        </div>

        <div id="gallery-snippet-links" style="display:none;">
            <?php foreach ($images as $media) : ?>
                <?php /* @var $media \humhub\modules\gallery\models\Media */ ?>
                <a href="<?= $media->getFileUrl() ?>#jpg" data-pjax-prevent data-type="image">
                    <img src="<?= $media->getSquarePreviewImageUrl() ?>" alt="<?= Html::encode($media->description) ?>">
                </a>
            <?php endforeach; ?>
        </div>
    </div>

</div>


<?= Html::script(<<<JS
    $(document).one('humhub:ready', function() {
        try {
            var gallery = $('#sidebar-gallery-carousel').parent();
            
            if(!gallery.length) {
                return;
            }
            
            var initSidebarWidget = function() {
                if(!gallery.is(':visible')) {
                    return false;
                }
                
                var links = $('#gallery-snippet-links a').get();
                
                blueimp.Gallery(links, {
                   index:links[0],
                   container: '#sidebar-gallery-carousel',
                   carousel: true,
                   stretchImages: true,
                   onopen: function () {
                       // Fix for small screens where sidebar is hidden,
                       // prevent slide animation when NaN is passed in `index` param,
                       // NOTE: on switching back to large screen with visible slider it can be started only manually.
                       this.originalOnslide = this.onslide;
                       this.onslide = function(index) {
                           if (Number.isInteger(index)) {
                               this.originalOnslide(index);
                           }
                       }
                   },
               });
                
                return true;
            };
            
            var timeOutInit = function() {
                setTimeout(function() {
                   if(!initSidebarWidget()) {
                       timeOutInit()
                   } 
                }, 2000);
            };
            
            if(!initSidebarWidget()) {
                timeOutInit();
            }
        } catch (e) {
          console.error(e);
        }
    });
JS
); ?>