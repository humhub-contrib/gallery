<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 * 
 * @package humhub.modules.gallery.views
 * @since 1.0
 * @author Sebastian Stumpf
 */
?>

<?php

use \humhub\modules\comment\widgets\CommentLink;
use \humhub\modules\comment\widgets\Comments;
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\like\widgets\LikeLink;
use humhub\widgets\TimeAgo;
use humhub\libs\Html;

$bundle = Assets::register($this);
?>
<div id="gallery-container" class="panel panel-default">

    <div class="panel-heading clearfix" style="background-color: <?= $this->theme->variable('background-color-secondary') ?>">
        <div style="margin-right:40px;" class="pull-left">
            <?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> ') . Html::encode($gallery->title); ?>
        </div>

        <?= \humhub\modules\gallery\widgets\GalleryMenu::widget(['gallery' => $gallery,
            'canWrite' => $this->context->canWrite(false),
            'contentContainer' => $this->context->contentContainer]); ?>

        <div class="row clearfix">
            <div class="col-sm-12 media">
                <span class="author">
                    <?= Html::containerLink($gallery->content->createdBy); ?> &middot;
                </span>

                <span class="time">
                    <?= TimeAgo::widget(['timestamp' => $gallery->content->created_at]); ?>
                </span>

                <?php if ( $gallery->content->updated_at !== null) : ?>
                    &middot; <span class="tt updated" title="<?= Yii::$app->formatter->asDateTime($gallery->content->updated_at); ?>"><?= Yii::t('ContentModule.base', 'Updated'); ?></span>
                <?php endif; ?>
                <br>
                <?php if($gallery->content->isPublic()) : ?>
                    <span class="label label-info"><?= Yii::t('base', 'Public');?></span>
                <?php endif; ?>
                <a class="btn btn-default btn-sm pull-right" data-ui-loader href="<?= $this->context->contentContainer->createUrl('/gallery/list') ?>">
                    <i class="glyphicon glyphicon-arrow-left"></i> <?= Yii::t('GalleryModule.base', 'Back to overview') ?>
                </a>
            </div>
        </div>
    </div>


    <div class="panel-body">
        <?php if ($gallery->description): ?>
            <div class="row clearfix" style="padding-bottom:5px;">
                <div class="col-sm-12 gallery-description">
                    <i class="fa fa-arrow-circle-right"></i>
                    <?= Html::encode($gallery->description); ?>
                </div>
            </div>
        <?php endif; ?>


        <div id="gallery-upload-progress" style="display:none;"></div>

        <div class="row">
            <?= humhub\modules\gallery\widgets\GalleryList::widget(['entryList' => $gallery->getMediaList(), 'parentGallery' => $gallery]); ?>
        </div>
        <div class="row">
            <div class="col-sm-12 social-activities-gallery colorFont5">
                <?= LikeLink::widget(['object' => $gallery]); ?>
                |
                <?= CommentLink::widget(['object' => $gallery]); ?>
            </div>
            <div class="col-sm-12 comments">
                <?= Comments::widget(['object' => $gallery]); ?>
            </div>
        </div>
    </div>
</div>