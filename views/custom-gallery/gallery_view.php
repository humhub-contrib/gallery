<?php

use \humhub\modules\comment\widgets\Comments;
use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\content\widgets\ContentObjectLinks;
use \humhub\modules\gallery\assets\Assets;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\Media;
use humhub\modules\gallery\permissions\WriteAccess;
use humhub\modules\gallery\widgets\GalleryMenu;
use humhub\widgets\Button;
use humhub\widgets\TimeAgo;
use humhub\libs\Html;
use humhub\modules\gallery\widgets\GalleryList;


/* @var CustomGallery $gallery */
/* @var Media[] $media */
/* @var boolean $showMore */
/* @var ContentActiveRecord $container */

$bundle = Assets::register($this);
?>
<div id="gallery-container" class="panel panel-default">

    <div class="panel-heading clearfix" style="background-color: <?= $this->theme->variable('background-color-secondary') ?>">
        <div style="margin-right:40px;" class="pull-left">
            <?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> ') . Html::encode($gallery->title) ?>
        </div>

        <?= GalleryMenu::widget(['gallery' => $gallery,
            'canWrite' => $container->can(WriteAccess::class),
            'contentContainer' =>$container]) ?>

        <div class="row clearfix">
            <div class="col-sm-12 media">
                <span class="author">
                    <?= Html::containerLink($gallery->content->createdBy) ?> &middot;
                </span>

                <span class="time">
                    <?= TimeAgo::widget(['timestamp' => $gallery->content->created_at]) ?>
                </span>

                <?php if ($gallery->content->updated_at) : ?>
                    &middot; <span class="tt updated" title="<?= Yii::$app->formatter->asDateTime($gallery->content->updated_at) ?>">
                        <?= Yii::t('ContentModule.base', 'Updated') ?>
                    </span>
                <?php endif; ?>

                <br>

                <?php if($gallery->content->isPublic()) : ?>
                    <span class="label label-info"><?= Yii::t('base', 'Public') ?></span>
                <?php endif; ?>

                <?= Button::back(Url::toGalleryOverview($container), Yii::t('GalleryModule.base', 'Back to overview'))->right()->sm() ?>
            </div>
        </div>
    </div>


    <div class="panel-body">
        <?php if ($gallery->description): ?>
            <div class="row clearfix" style="padding-bottom:5px;">
                <div class="col-sm-12 gallery-description">
                    <i class="fa fa-arrow-circle-right"></i>
                    <?= Html::encode($gallery->description) ?>
                </div>
            </div>
        <?php endif; ?>

        <div id="gallery-upload-progress" style="display:none;"></div>

        <div class="row">
            <?= GalleryList::widget(['entryList' => $media, 'parentGallery' => $gallery, 'showMore' => $showMore]) ?>
        </div>

        <div class="row">
            <div class="col-sm-12 social-activities-gallery colorFont5">
                <?= ContentObjectLinks::widget(['object' => $gallery]); ?>
            </div>
            <div class="col-sm-12 comments">
                <?= Comments::widget(['object' => $gallery]) ?>
            </div>
        </div>
    </div>
</div>