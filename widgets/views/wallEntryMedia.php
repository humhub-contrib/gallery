<?php

use \humhub\modules\gallery\widgets\MediaPreview;
use \humhub\widgets\RichText;
use \humhub\widgets\TimeAgo;
use \yii\helpers\Html;
?>

<style>
    .gallery-wallout-media {

    }

    .gallery-wallout-media .info-small {
        color: #bebebe;
        font-size: 11px;
    }

    .gallery-wallout-media .media-wallentry-header i {
        font-size: 20px;
    }

    .gallery-wallout-media .media-wallentry-header h5 i,.gallery-wallout-media .media-wallentry-header h5 span
    {
        vertical-align: middle;
    }

    .gallery-wallout-media .media-wallentry-content {
        margin-bottom: 20px;
    }

    .gallery-wallout-media .media-wallentry-content .preview a img {
        max-width: 100%;
    }

    .gallery-wallout-media .media-wallentry-content ul {
        margin: 0;
        padding-left: 5px;
        list-style-type: none;

    }
    .gallery-wallout-media .media-wallentry-content ul li {
        margin-bottom: 2px;
        vertical-align: middle;

    }

    .gallery-wallout-media .media-wallentry-content ul li a {
        color: #555;
        vertical-align: middle;
    }
</style>

<?php
$downloadUrl = $media->getUrl(true);
$url = $media->getUrl();
$title = Html::encode($media->getTitle());
$description = trim($media->description) ? $media->description : '';
$size = $media->getSize();
$creator = $media->creator;
$editor = $media->editor;
$updatedAt = $media->content->updated_at;
$createdAt = $media->content->created_at;
?>

<div class="gallery-wallout-media" id="gallery-wallout-media-<?php echo $media->id; ?>">
    <div class="media-wallentry-header"
         style="overflow: hidden; margin-bottom: 20px;">
        <div><?php echo RichText::widget(['text' => $description, 'record' => $media]); ?></div>
    </div>
    <div class="media-wallentry-content"
         style="overflow: hidden;">
        <div class="preview">
            <?php echo MediaPreview::widget(['file' => $media, 'width' => 600, 'height' => 350, 'htmlConf' => ['class' => 'preview', 'id' => 'gallery-wallout-media-preview-' . $media->id]]); ?>
        </div>
        <hr />
        <ul>
            <li>
                <span><?php echo Yii::t('GalleryModule.base', 'Created at:') ?>&nbsp;</span>
                <span><?php echo TimeAgo::widget(['timestamp' => $createdAt]); ?></span>
            </li>
            <li>
                <span><?php echo Yii::t('GalleryModule.base', 'Created by:') ?>&nbsp;</span>
                <span>
                    <a href="<?php echo $creator->createUrl(); ?>"><?php echo $creator->getDisplayName(); ?></a>
                </span>
            </li>
            <?php if (!empty($editor) && $creator->id !== $editor->id): ?>
                <li>
                    <span><?php echo Yii::t('GalleryModule.base', 'Last edited at:') ?>&nbsp;</span>
                    <span><?php echo TimeAgo::widget(['timestamp' => $updatedAt]); ?></span>
                </li>
                <li>
                    <span><?php echo Yii::t('GalleryModule.base', 'Last edited by:') ?>&nbsp;</span>
                    <span>
                        <a href="<?php echo $editor->createUrl(); ?>"><?php echo $editor->getDisplayName(); ?></a>
                    </span>
                </li>
            <?php endif; ?>
            <li>
                <span><?php echo Yii::t('GalleryModule.base', 'Filesize:') ?>&nbsp;</span>
                <span class="time"><?php echo Yii::$app->formatter->asShortSize($size, 1); ?></span>
            </li>
        </ul>
        <a class="more-link-gallery-wallout-media hidden" id="more-link-gallery-wallout-media-<?php echo $media->id; ?>" data-state="down"
           style="margin: 20px 0 20px 0; display: block;" href="javascript:showMoreFiles(<?php echo $media->id; ?>);"><i
                class="fa fa-arrow-down"></i> <?php echo Yii::t('GalleryModule.base', 'Show complete media preview'); ?>
        </a> 
    </div>
    <div class="media-wallentry-footer"
         style="overflow: hidden; margin-bottom: 10px;">
        <div style="overflow: hidden;">
            <a href="<?php echo $media->parentGallery->getUrl(); ?>"><?php echo Yii::t('GalleryModule.base', 'Open Gallery!'); ?></a>
        </div>
    </div>
</div>