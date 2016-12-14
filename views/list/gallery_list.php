<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;
use humhub\modules\gallery\widgets\GalleryList;

$bundle = \humhub\modules\gallery\Assets::register($this);
$this->registerJsVar('galleryMediaUploadUrl', 'unused');
?>

<div id="galleryContainer" class="panel panel-default">
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <div class="panel-body">
        <?php if($this->context->canWrite(false)): ?>
        <div class="row button-action-menu">
            <div class="col-sm-4">
                <a class="btn btn-default" data-target="#globalModal"
                    href="<?php echo $this->context->contentContainer->createUrl('/gallery/custom-gallery/edit'); ?>"><i
                    class="glyphicon glyphicon-plus"></i> Add
                    Gallery</a>
            </div>
        </div>
        <?php endif; ?>
        <div class="row">
            <div id="logContainer" class="col-sm-12" style="display: none">
                <ul class="alert alert-danger">
                </ul>
            </div>
            <?php echo GalleryList::widget([ 'stream_galleries' => $stream_galleries, 'custom_galleries' => $custom_galleries, 'context' => $this->context ]); ?>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>