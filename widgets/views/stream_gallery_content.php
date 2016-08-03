<?php
use yii\helpers\Url;
use yii\helpers\Html;
use humhub\modules\cfiles\controllers\BrowseController;
use humhub\models\Setting;
use yii\bootstrap\ButtonDropdown;
use humhub\modules\cfiles\widgets\DropdownButton;
use humhub\modules\gallery\libs\FileUtils;

$bundle = \humhub\modules\gallery\Assets::register($this);
$counter = 0;
$rowClosed = true;
?>

<div id="galleryContent" class="col-sm-12">
    <?php if($gallery->isEmpty()): ?>
        <div class="galleryEmptyMessage">
            <div class="panel">
                <div class="panel-body">
                    <p><strong><?php echo Yii::t('GalleryModule.base', 'This gallery is empty.');?></strong></p>
                    <?php echo Yii::t('GalleryModule.base', 'No images have been posted to the stream yet :(.');?>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <?php foreach($gallery->fileList as $file): ?>
        <?php if($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif; ?>
            <div class="col-sm-4 galleryMediaFile">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <a class="zoom" href="<?php echo $file->getUrl(); ?>#.jpeg" data-type="image" data-toggle="lightbox" data-parent="#galleryContent"  data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
                            data-footer='<p style="overflow:hidden; text-overflow:ellipsis;"><strong><?php echo $file->title; ?></strong></p><button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.base', 'Close'); ?></button>'>
                            <img src="<?php echo FileUtils::getSquareThumbnailUrlFromFile($file); ?>" />
                            <span class="overlay"><i class="glyphicon glyphicon-fullscreen"></i></span>
                        </a>
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
