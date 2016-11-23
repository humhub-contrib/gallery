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
    <?php foreach($stream_galleries as $gallery): ?>
        <?php if(!$gallery->isEmpty()): ?>
            <?php if($counter % 3 === 0) :
                echo '<div class="row">';
                $rowClosed = false;
            endif; ?>
                <div class="col-sm-4 gallery">
                    <div class="panel panel-default">
                        <div class="panel-header">
                            <div class="pull-left truncate tt" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo Html::encode($gallery->title); ?>">
                                <?php echo Html::encode($gallery->title); ?>
                            </div>
                            <div class="pull-right">
                                <ul class="nav nav-pills preferences">
                                        <li class="dropdown">
                                            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                            <ul class="dropdown-menu pull-right">
                                                <li>
                                                    <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/stream-gallery/edit', ['item-id' => $gallery->getItemId()])?>"><i class="fa fa-edit"></i><?php echo Yii::t('GalleryModule.base', 'Edit gallery information'); ?></a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="panel-body">
                            <a href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/stream-gallery/view', ['open-gallery-id' => $gallery->id]); ?>">
                                <img src="<?php echo $gallery->getPreviewImageUrl(); ?>" />
                            </a>
                        </div>
                    </div>
                </div>
            <?php if(++$counter % 3 === 0) :
                echo '</div>';
                $rowClosed = true;
            endif; ?>
        <?php endif; ?>
    <?php endforeach; ?>
    
    <?php foreach($custom_galleries as $gallery): ?>
        <?php if($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif; ?>
            <div class="col-sm-4 gallery">
                <div class="panel panel-default">
                    <div class="panel-header">
                        <div class="pull-left truncate tt" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo Html::encode($gallery->title); ?>">
                                <?php echo Html::encode($gallery->title); ?>
                        </div>
                        <div class="pull-right">
                            <ul class="nav nav-pills preferences">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                        <ul class="dropdown-menu pull-right">
                                            <li>
                                                <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/list/delete-multiple', ['item-id' => $gallery->getItemId()])?>"><i class="fa fa-trash"></i><?php echo Yii::t('GalleryModule.base', 'Delete gallery'); ?></a>                                            
                                            </li>
                                            <li>
                                                <a data-target="#globalModal" href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/custom-gallery/edit', ['item-id' => $gallery->getItemId()])?>"><i class="fa fa-edit"></i><?php echo Yii::t('GalleryModule.base', 'Edit gallery information'); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo $gallery->getWallUrl(); ?>"><i class="fa fa-edit"></i><?php echo Yii::t('GalleryModule.base', 'Show on Wall'); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo $this->context->context->contentContainer->createUrl('/gallery/custom-gallery/view', ['open-gallery-id' => $gallery->id]); ?>">
                            <img src="<?php echo $gallery->getPreviewImageUrl(); ?>" />
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
