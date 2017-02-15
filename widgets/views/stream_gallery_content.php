<?php

use \humhub\modules\comment\widgets\CommentLink;
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\libs\FileUtils;
use \humhub\modules\gallery\Module;
use \humhub\modules\like\widgets\LikeLink;
use \yii\helpers\Html;

$bundle = Assets::register($this);
$counter = 0;
$rowClosed = true;
?>

<div id="galleryContent" class="col-sm-12">
    <?php if ($gallery->isEmpty()): ?>
        <div class="galleryEmptyMessage">
            <b><?php echo Yii::t('GalleryModule.base', 'This gallery is empty.'); ?></b><br/>
            <?php echo Yii::t('GalleryModule.base', 'No images have been posted to the stream yet :(.'); ?>
        </div>
    <?php endif; ?>
    <?php foreach ($gallery->fileList as $file): ?>
        <?php
        $creator = Module::getUserById($file->created_by);
        $baseObject = FileUtils::getBaseObject($file);
        $baseContent = FileUtils::getBaseContent($file);
        ?>
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
                                 data-original-title="<?php echo Yii::t('CfilesModule.base', 'posted by ') . $creator->getDisplayName(); ?>"
                                 data-placement="top" title="" data-toggle="tooltip">
                        </a>
                    </div>
                    <div class="pull-left truncate tt" data-toggle="tooltip" data-placement="top" title="" data-original-title="<?php echo Html::encode($baseObject->message); ?>">
                        <?php echo Html::encode($baseObject->message); ?>
                    </div>
                    <div class="pull-right">
                        <ul class="nav nav-pills preferences">
                            <li class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a href="<?php echo $baseContent->getUrl(); ?>"><i class="fa fa-link"></i> <?php echo Yii::t('GalleryModule.base', 'Show post'); ?></a>
                                    </li>
                                    <li>
                                        <?php
                                        //@deprecated: v1.1 compatibility
                                        if (version_compare(Yii::$app->version, '1.2', '<')):
                                            $downloadUrl = $file->getUrl() . '&' . http_build_query(['download' => true]);
                                        else:
                                            $downloadUrl = $file->getUrl(['download' => true]);
                                        endif;
                                        ?>
                                        <a data-pjax-prevent="1" href="<?php echo $downloadUrl; ?>"><i class="fa fa-download"></i> <?php echo Yii::t('GalleryModule.base', 'Save'); ?></a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    <a class="zoom" href="<?php echo $file->getUrl(); ?>#.jpeg" data-type="image" data-toggle="lightbox" data-parent="#galleryContent"  data-gallery="GalleryModule-Gallery-<?php echo $gallery->id; ?>"
                       data-footer='<p style="overflow:hidden; text-overflow:ellipsis;"><strong><?php echo Html::encode(Html::encode($file->title)); ?></strong></p><button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo Yii::t('FileModule.base', 'Close'); ?></button>'>
                        <img src="<?php echo FileUtils::getSquareThumbnailUrlFromFile($file); ?>" />
                        <span class="overlay"><i class="glyphicon glyphicon-fullscreen"></i></span>
                    </a>
                </div>
                <div class="panel-footer">
                    <div class="social-activities colorFont5">

                        <?php echo LikeLink::widget(['object' => $baseObject]); ?>
                        |
    <?php echo CommentLink::widget(['object' => $baseObject, 'mode' => CommentLink::MODE_POPUP]); ?>
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
