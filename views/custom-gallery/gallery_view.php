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
use \yii\helpers\Html;

$bundle = Assets::register($this);
?>
<div id="gallery-container" class="panel panel-default <?= $gallery->isPublic() ? 'shadowPublic' : '' ?>">

    <div class="panel-heading">
        <div style="margin-right:40px;" class="pull-left"><?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> ') . Html::encode($gallery->title); ?></div>
        <?php echo \humhub\modules\gallery\widgets\GalleryMenu::widget(['dropdown' => true, 'gallery' => $gallery, 'canWrite' => $this->context->canWrite(false), 'contentContainer' => $this->context->contentContainer]); ?> 
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <?php if ($gallery->description): ?>
            <div class="row">
                <div class="col-sm-12 gallery-description">
                    <?php echo Html::encode($gallery->description); ?><br />
                </div>
            </div>
        <?php endif; ?>
        <?php if (0): // deactivate comments section for now?>
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
        <?php endif; ?>
        <div style="padding: 10px 0 10px 0;" class="row">
            <div class="col-sm-1">
                <a class="btn btn-default btn-sm" href="<?= $this->context->contentContainer->createUrl('/gallery/list') ?>">
                    <i class="glyphicon glyphicon-arrow-left"></i> <?= Yii::t('GalleryModule.base', 'Back to the list') ?></a>
            </div>
        </div>
        <div id="gallery-upload-progress" style="display:none;"></div>
        <div class="row">
            <?php echo humhub\modules\gallery\widgets\GalleryList::widget(['entryList' => $gallery->getMediaList(), 'parentGallery' => $gallery]); ?>
        </div>
    </div>
</div>