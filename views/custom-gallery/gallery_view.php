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

    <div class="panel-heading"><?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> ') . Html::encode($gallery->title); ?></div>

    <div class="panel-body">
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
        <?php echo \humhub\modules\gallery\widgets\GalleryMenu::widget(['gallery' => $gallery, 'canWrite' => $this->context->canWrite(), 'contentContainer' => $this->context->contentContainer]); ?> 
        <div class="row">
            <div id="logContainer" class="col-sm-12" style="display: none">
                <ul class="alert alert-danger">
                </ul>
            </div>
            <?php echo humhub\modules\gallery\widgets\GalleryList::widget(['entryList' => $gallery->getMediaList(), 'parentGallery' => $gallery]); ?>
        </div>
    </div>
</div>