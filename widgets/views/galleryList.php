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

<div id="gallery-list" class="col-sm-12">
    <?php foreach ($stream_galleries as $gallery): ?>
        <?php if (!$gallery->isEmpty()): ?>
            <?php $noVisibleContent = false; ?>
            <?php
            if ($counter % 3 === 0) :
                echo '<div class="row">';
                $rowClosed = false;
            endif;
            echo \humhub\modules\gallery\widgets\GalleryListEntry::widget(['entryObject' => $gallery]);
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
        echo \humhub\modules\gallery\widgets\GalleryListEntry::widget(['entryObject' => $gallery]);
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
