<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;

$bundle = \humhub\modules\gallery\Assets::register($this);
$counter = 0;
$rowClosed = true;
?>

<div id="galleryContent" class="col-sm-12">
    <?php foreach($gallery->mediaList as $media): ?>
        <?php if($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif; ?>
            <div class="col-sm-4 galleryMediaFile">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a class="zoom" href="<?php echo $media->getUrl(); ?>#.jpeg" data-type="image" data-toggle="lightbox" data-parent="#galleryContent"  data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
                            data-footer='<p style="overflow:hidden; text-overflow:ellipsis;"><strong><?php echo $media->title; ?></strong></p><?php echo $media->description != "" ? "<p>$media->description</p>" : ""; ?><button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.base', 'Close'); ?></button>'>
                            <img class="img-responsive" alt="<?php echo $media->description; ?>" src="<?php echo $media->getQuadraticThumbnailUrl(); ?>" />
                            <span class="overlay"><i class="glyphicon glyphicon-fullscreen"></i></span>
                        </a>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-right gallery-media-file-controls">
                            <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/delete', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="fa fa-trash"></i></a> 
                            <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/edit/media', ['open-gallery-id' => $gallery->id,'item-id' => $media->getItemId()]);?>"><i class="fa fa-edit"></i></a>
                        </span>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        <?php if(++$counter % 3 === 0) :
            echo '</div>';
            $rowClosed = true;
        endif;
        ?>
    <?php endforeach; ?>
    <?php echo $rowClosed ? "" : '</div>'; ?>
</div>
