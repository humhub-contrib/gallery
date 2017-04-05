<?php

use \humhub\modules\comment\widgets\CommentLink;
use \humhub\modules\comment\widgets\Comments;
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\widgets\CustomGalleryContent;
use \humhub\modules\like\widgets\LikeLink;
use \yii\helpers\Html;

$bundle = Assets::register($this);
?>
<div id="gallery-container" class="panel panel-default">
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <?php echo Html::endForm(); ?>
    <div class="panel-body">
        <a class="btn btn-default btn-sm back-button" href="<?php echo $this->context->contentContainer->createUrl('/gallery/list'); ?>"><i
                class="glyphicon glyphicon-arrow-left"></i> <?php echo Yii::t('GalleryModule.base', 'Back to the list'); ?></a>
        <h1><strong><?php echo Html::encode($gallery->title); ?></strong></h1>
        <div class="row">
            <div class="col-sm-12 gallery-description">
                <?php echo Html::encode($gallery->description); ?><br />
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12 social-activities colorFont5">
                <?php echo LikeLink::widget(['object' => $gallery]); ?>
                |
                <?php echo CommentLink::widget(['object' => $gallery]); ?>
            </div>
            <div class="col-sm-12 comments">
                <?php echo Comments::widget(['object' => $gallery]); ?>
            </div>
        </div>
        <hr />
        <?php echo \humhub\modules\gallery\widgets\GalleryMenu::widget(['gallery' => $gallery, 'canWrite' => $this->context->canWrite(), 'contentContainer' => $this->context->contentContainer]); ?> 
        <div class="row">
            <div id="logContainer" class="col-sm-12" style="display: none">
                <ul class="alert alert-danger">
                </ul>
            </div>
            <?php echo CustomGalleryContent::widget(['gallery' => $gallery]); ?>
        </div>
    </div>
</div>