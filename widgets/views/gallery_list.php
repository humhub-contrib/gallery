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

<div id="galleryList" class="col-sm-12">
    <?php foreach($galleries as $gallery): ?>
        <?php if($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif; ?>
            <div class="col-sm-4 gallery">
                <div class="panel panel-default">
                    <div class="panel-header">
                        <?php echo Html::encode($gallery->title); ?>
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/browse', ['open-gallery-id' => $gallery->id]); ?>">
                            <img src="<?php echo $gallery->getPreviewImageUrl(); ?>" />
                        </a>
                    </div>
                    <div class="panel-footer">
                        <span class="pull-right gallery-media-file-controls">
                            <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/delete', ['item-id' => $gallery->getItemId()])?>"><i class="fa fa-trash"></i></a> 
                            <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/edit/gallery', ['item-id' => $gallery->getItemId()])?>"><i class="fa fa-edit"></i></a>
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
