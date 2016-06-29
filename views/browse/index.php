<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;
use humhub\modules\gallery\widgets\GalleryList;

$bundle = \humhub\modules\gallery\Assets::register($this);
?>
<div id="galleryContainer" class="panel panel-default">
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <div class="panel-body">
        <div class="row button-action-menu">
            <div class="col-sm-4">
                <a class="btn btn-default" data-target="#globalModal"
                    href="<?php echo $this->context->contentContainer->createUrl('/gallery/edit/gallery'); ?>"><i
                    class="glyphicon glyphicon-plus"></i> Add
                    Gallery</a>
            </div>
        </div>

        <div class="row">
            <div id="logContainer" class="col-sm-12" style="display: none">
                <ul class="alert alert-danger">
                </ul>
            </div>
            <?php echo GalleryList::widget([ 'galleries' => $galleries, 'context' => $this->context ]); ?>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>