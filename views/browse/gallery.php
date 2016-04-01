<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;

$bundle = \humhub\modules\gallery\Assets::register($this);
$this->registerJsVar('galleryMediaUploadUrl', $this->context->contentContainer->createUrl('/gallery/upload', [
    'open-gallery-id' => $gallery->id
]));
?>
<div id="galleryContainer" class="panel panel-default">
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <div class="panel-body">
        <h1><?php echo Html::encode($gallery->title); ?></h1>
        <div class="row">
            <div class="col-sm-12">
                <?php echo Html::encode($gallery->description); ?><br />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <span class="fileinput-button btn"> <i
                    class="glyphicon glyphicon-plus"></i> <?php Yii::t('GalleryModule.base', 'Upload'); ?> <input
                    id="galleryMediaUpload" type="file" name="files[]"
                    multiple>
                </span>
            </div>
            <div class="col-sm-4">
                <a class="btn" data-target="#globalModal"
                    href="<?php echo $this->context->contentContainer->createUrl('/gallery/edit/gallery', ['open-gallery-id' => $gallery->id, 'item-id' => $gallery->getItemId()]); ?>">Edit
                    Gallery</a>
            </div>
            <div class="col-sm-4">
                <a class="btn" data-target="#globalModal"
                    href="<?php echo $this->context->contentContainer->createUrl('/gallery/delete', ['item-id' => $gallery->getItemId()]); ?>">Delete
                    Gallery</a>
            </div>
        </div>
        <div class="row">
            <div id="logContainer" class="row" style="display: none">
                <div class="col-sm-12 alert alert-danger">
                    <ul>
                    </ul>
                </div>
            </div>
            <div id="galleryMediaList" class="col-sm-12">
                <?php foreach($gallery->media as $media): ?>
                        <a data-toggle="lightbox" data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
                            href="<?php echo $media->getUrl(); ?>#.jpeg"
                            data-footer='<button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.base', 'Close'); ?></button>'>
                            <img style="width:25%" src="<?php echo $media->getUrl(); ?>" />
                        </a>
                        <a data-target="#globalModal" href="<?php echo $this->context->contentContainer->createUrl('/gallery/delete', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="fa fa-trash"></i></a> 
                        <a data-target="#globalModal" href="<?php echo $this->context->contentContainer->createUrl('/gallery/edit/media', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="fa fa-edit"></i></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>