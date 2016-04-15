<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;

$bundle = \humhub\modules\gallery\Assets::register($this);
?>
<div id="galleryContent" class="col-sm-12">
    <?php foreach($gallery->media as $media): ?>
            <a data-toggle="lightbox" data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
                href="<?php echo $media->getUrl(); ?>#.jpeg"
                data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.base', 'Close'); ?></button>'>
                <img style="width:25%" src="<?php echo $media->getUrl(); ?>" />
            </a>
            <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/delete', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="fa fa-trash"></i></a> 
            <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/edit/media', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="fa fa-edit"></i></a>
    <?php endforeach; ?>
</div>
