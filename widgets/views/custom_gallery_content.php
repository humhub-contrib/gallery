<?php

use \humhub\modules\comment\widgets\CommentLink;
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\Module;
use \humhub\modules\like\widgets\LikeLink;
use \yii\helpers\Html;

$bundle = Assets::register($this);
$contentContainer = Yii::$app->controller->contentContainer;
$counter = 0;
$rowClosed = true;
?>

<div id="galleryContent" class="col-sm-12">
    <?php if ($gallery->isEmpty()): ?>
        <div class="galleryEmptyMessage">
            <?php if (Yii::$app->controller->canWrite(false)): ?>
                <b><?php echo Yii::t('GalleryModule.base', 'This gallery is still empty.'); ?></b><br/>
                <?php echo Yii::t('GalleryModule.base', 'You can upload images using the buttons above.'); ?>
            <?php else: ?>
                <p style="margin-top:10px;"><strong><?php echo Yii::t('GalleryModule.base', 'This gallery is still empty.'); ?></strong></p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    <?php foreach ($gallery->mediaList as $media): ?>
        <?php $creator = Module::getUserById($media->baseFile->created_by); ?>
        <?php
        if ($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif;
        ?>
        <div class="col-sm-4 galleryMediaFile">
            <div class="panel panel-default">
                <div class="panel-header">
                    <div class="pull-left" style="margin-right:4px;">
                        <a href="<?php echo $creator->createUrl(); ?>">
                            <img class="img-rounded tt img_margin"
                                 src="<?php echo $creator->getProfileImage()->getUrl(); ?>"
                                 width="21" height="21" alt="21x21" data-src="holder.js/21x21"
                                 style="width: 21px; height: 21px;"
                                 data-original-title="<?php echo Yii::t('CfilesModule.base', 'added by ') . $creator->getDisplayName(); ?>"
                                 data-placement="top" title="" data-toggle="tooltip">
                        </a>
                    </div>
                    <div class="pull-left truncate tt" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo Html::encode($media->title); ?>">
                        <?php echo Html::encode($media->title); ?>
                    </div>
                    <div class="pull-right">
                        <ul class="nav nav-pills preferences">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="<?php echo $media->getWallUrl(); ?>"><i class="fa fa-link"></i> <?php echo Yii::t('GalleryModule.base', 'Show post'); ?></a>
                                    </li>
                                    <?php if (Yii::$app->controller->canWrite(false)): ?>
                                        <li>
                                            <a data-target="#globalModal" href="<?php echo $contentContainer->createUrl('/gallery/custom-gallery/delete-multiple', ['open-gallery-id' => $gallery->id, 'item-id' => $media->getItemId()]); ?>"><i class="fa fa-trash"></i> <?php echo Yii::t('GalleryModule.base', 'Delete image'); ?></a>
                                        </li>
                                        <li>
                                            <a data-target="#globalModal" href="<?php echo $contentContainer->createUrl('/gallery/media/edit', ['open-gallery-id' => $gallery->id, 'item-id' => $media->getItemId()]); ?>"><i class="fa fa-edit"></i> <?php echo Yii::t('GalleryModule.base', 'Edit image info'); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <li>
                                        <a data-pjax-prevent="1" href="<?php echo $media->getUrl(true); ?>"><i class="fa fa-download"></i> <?php echo Yii::t('GalleryModule.base', 'Save'); ?></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <a class="zoom" href="<?php echo $media->getUrl(); ?>#.jpeg" data-type="image" data-toggle="lightbox" data-parent="#galleryContent"  data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>" data-ui-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
                       data-footer='<p style="overflow:hidden; text-overflow:ellipsis;"><strong><?php echo Html::encode(Html::encode($media->title)); ?></strong></p><?php echo ($media->description != "" ? "<p>" . Html::encode(Html::encode($media->description)) . "</p>" : ""); ?><button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.base', 'Close'); ?></button>'>
                        <img src="<?php echo $media->getSquareThumbnailUrl(); ?>" />
                        <span class="overlay"><i class="glyphicon glyphicon-fullscreen"></i></span>
                    </a>
                </div>
                <div class="panel-footer">
                    <div class="social-activities colorFont5">
                        <?php echo LikeLink::widget(['object' => $media]); ?>
                        |
                        <?php echo CommentLink::widget(['object' => $media, 'mode' => CommentLink::MODE_POPUP]); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if (++$counter % 3 === 0) :
            echo '</div>';
            $rowClosed = true;
        endif;
        ?>
    <?php endforeach; ?>
    <?php echo $rowClosed ? "" : '</div>'; ?>
</div>
