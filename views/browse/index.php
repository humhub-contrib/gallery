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
        <div class="row">
            <div class="col-sm-4">
                <a class="btn" data-target="#globalModal"
                    href="<?php echo $this->context->contentContainer->createUrl('/gallery/edit/gallery'); ?>">Add
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
                <?php foreach($galleries as $gallery): ?>
                    <?php
                    $title = $gallery->title;
                    $description = $gallery->description;
                    $previewImageLink = $gallery->previewMedia === null ? '' : $gallery->previewMedia->getUrl();
                    $previewImage = $previewImageLink === '' ? '<i class="fa fa-picture-o" style="font-size:150px;"></i>' : '<img src="' . $previewImageLink . '; ?>" style="max-width:200px;max-height:200px;"/>';
                    ?>
                    <div class="col-sm-12">
                        <h1><?php echo Html::encode($title); ?></h1>
                        <a href="<?php echo $this->context->contentContainer->createUrl('/gallery/browse', ['open-gallery-id' => $gallery->id]); ?>"><?php echo $previewImage; ?><br /></a>
                        <?php echo Html::encode($description); ?><br />
                        <a class="btn" data-target="#globalModal"
                            href="<?php echo $this->context->contentContainer->createUrl('/gallery/edit/gallery', ['item-id' => $gallery->getItemId()])?>">Edit
                            Gallery</a> <a class="btn"
                            data-target="#globalModal"
                            href="<?php echo $this->context->contentContainer->createUrl('/gallery/delete', ['item-id' => $gallery->getItemId()])?>">Delete
                            Gallery</a>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>