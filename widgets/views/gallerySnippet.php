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
    $(document).on('humhub:ready', function() {

       $('#gallery-snippet-links a').on('click', function(evt) {
           evt.preventDefault();
           var links = $('#gallery-snippet-links a').get();
           blueimp.Gallery(links, {
               index:links[0],
               container: '#sidebar-gallery-carousel',
               carousel: true,
               stretchImages: true
           });
       });
        setTimeout(function() {
            $('#gallery-snippet-links a').first().trigger('click')
        }, 200)
    });
JS
); ?>