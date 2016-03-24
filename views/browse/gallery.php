<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;

$bundle = \humhub\modules\gallery\Assets::register($this);
?>
<div id="galleryContainer" class="panel panel-default">
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <div class="panel-body">
        <h1><?php echo Html::encode($gallery->title); ?></h1>
        <div class="row">
            <div class="col-sm-4">
                <?php echo Html::encode($gallery->description); ?><br />
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
            <div class="col-sm-12">
                <div class="row">
                <?php foreach($gallery->media as $media): ?>
                    <div class="col-sm-3">
                        <img src="<?php echo $media->getUrl(); ?>" /> 
                        <a href="<?php echo $this->context->contentContainer->createUrl('/gallery/delete', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="icon-remove-sign" /></a>
                        <a href="<?php echo $this->context->contentContainer->createUrl('/gallery/edit/media', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="icon-edit-sign" /></a>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>