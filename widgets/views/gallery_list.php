<?php

use \humhub\modules\comment\widgets\CommentLink;
use \humhub\modules\content\models\Content;
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\Module;
use \humhub\modules\like\widgets\LikeLink;
use \yii\helpers\Html;

$bundle = Assets::register($this);
$contentContainer = Yii::$app->controller->contentContainer;
$counter = 0;
$rowClosed = true;
$noVisibleContent = true;
?>

<div id="galleryList" class="col-sm-12">
    <?php foreach ($stream_galleries as $gallery): ?>
        <?php if (!$gallery->isEmpty()): ?>
            <?php $noVisibleContent = false; ?>
            <?php
            if ($counter % 3 === 0) :
                echo '<div class="row">';
                $rowClosed = false;
            endif;
            ?>
            <div class="col-sm-4 gallery">
                <div class="panel panel-default shadowPublic">
                    <div class="panel-header">
                        <div class="pull-left truncate tt" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo Html::encode($gallery->title); ?>">
                            <?php echo Html::encode($gallery->title); ?>
                        </div>
                        <?php if (Yii::$app->controller->canWrite(false)): ?>
                            <div class="pull-right">
                                <ul class="nav nav-pills preferences">
                                    <li class="dropdown">
                                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                        <ul class="dropdown-menu pull-right">
                                            <li>
                                                <a data-target="#globalModal" href="<?php echo $contentContainer->createUrl('/gallery/stream-gallery/edit', ['item-id' => $gallery->getItemId()]) ?>"><i class="fa fa-edit"></i><?php echo Yii::t('GalleryModule.base', 'Edit gallery information'); ?></a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <div class="clearfix"></div>
                    </div>
                    <div class="panel-body">
                        <a href="<?php echo $contentContainer->createUrl('/gallery/stream-gallery/view', ['open-gallery-id' => $gallery->id]); ?>">
                            <img src="<?php echo $gallery->getPreviewImageUrl(); ?>" />
                        </a>
                    </div>
                    <div class="panel-footer">
                        <div class="social-activities colorFont5">
                            <?php echo Html::encode($gallery->description); ?>
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
        <?php endif; ?>
    <?php endforeach; ?>

    <?php foreach ($custom_galleries as $gallery): ?>
        <?php $noVisibleContent = false; ?>
        <?php $creator = Module::getUserById($gallery->content->created_by); ?>
        <?php
        if ($counter % 3 === 0) :
            echo '<div class="row">';
            $rowClosed = false;
        endif;
        ?>
        <div class="col-sm-4 gallery">
            <div class="panel panel-default <?php if ($gallery->content->visibility == Content::VISIBILITY_PUBLIC) : echo 'shadowPublic';
    endif;
        ?>">
                <div class="panel-header">
                    <div class="pull-left" style="margin-right:4px;">
                        <a href="<?php echo $creator->createUrl(); ?>">
                            <img class="img-rounded tt img_margin"
                                 src="<?php echo $creator->getProfileImage()->getUrl(); ?>"
                                 width="21" height="21" alt="21x21" data-src="holder.js/21x21"
                                 style="width: 21px; height: 21px;"
                                 data-original-title="<?php echo Yii::t('CfilesModule.base', 'Gallery created by ') . $creator->getDisplayName(); ?>"
                                 data-placement="top" title="" data-toggle="tooltip">
                        </a>
                    </div>
                    <div class="pull-left truncate tt" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo Html::encode($gallery->title); ?>">
    <?php echo Html::encode($gallery->title); ?>
                    </div>
                    <div class="pull-right">
                        <ul class="nav nav-pills preferences">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu pull-right">
    <?php if (Yii::$app->controller->canWrite(false)): ?>
                                        <li>
                                            <a data-target="#globalModal" href="<?php echo $contentContainer->createUrl('/gallery/list/delete-multiple', ['item-id' => $gallery->getItemId()]) ?>"><i class="fa fa-trash"></i><?php echo Yii::t('GalleryModule.base', 'Delete gallery'); ?></a>                                            
                                        </li>
                                        <li>
                                            <a data-target="#globalModal" href="<?php echo $contentContainer->createUrl('/gallery/custom-gallery/edit', ['item-id' => $gallery->getItemId()]) ?>"><i class="fa fa-edit"></i><?php echo Yii::t('GalleryModule.base', 'Edit gallery information'); ?></a>
                                        </li>
    <?php endif; ?>
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
                    <a href="<?php echo $contentContainer->createUrl('/gallery/custom-gallery/view', ['open-gallery-id' => $gallery->id]); ?>">
                        <img src="<?php echo $gallery->getPreviewImageUrl(); ?>" />
                    </a>
                </div>
                <div class="panel-footer">
                    <div class="social-activities colorFont5">
                        <?php echo LikeLink::widget(['object' => $gallery]); ?>
                        |
    <?php echo CommentLink::widget(['object' => $gallery, 'mode' => CommentLink::MODE_POPUP]); ?>
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
        <?php if ($noVisibleContent): ?>
        <div class="noVisibleContent">
            <?php if (Yii::$app->controller->canWrite(false)): ?>
                <b><?php echo Yii::t('GalleryModule.base', 'There is no content yet.'); ?></b></br>
                <?php echo Yii::t('GalleryModule.base', 'You can create galleries and post images to change that.'); ?>
                    <?php else: ?>
                <p style="margin-top:10px;"><strong><?php echo Yii::t('GalleryModule.base', 'There is no visible content yet.'); ?></strong></p>
        <?php endif; ?>
        </div>
<?php endif; ?>
</div>
