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
use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\like\widgets\LikeLink;
use \yii\helpers\Html;

$bundle = Assets::register($this);
?>


<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 gallery-list-entry">
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?= $fileUrl ?>#.jpeg"
                <?php if ($uiGalleryId): ?>
                    data-type="image"
                    data-toggle="lightbox"
                    data-parent="#gallery-content"
                    data-description="<?= Html::encode($title) ?>"
                    title="<?= Html::encode($title) ?>"
                    data-ui-gallery="<?= $uiGalleryId ?>"
                <?php endif; ?>>
                <img class="<?= $imagePadding ? 'padding15perc' : '' ?>" src="<?= $thumbnailUrl ?>"
                     alt="<?= Html::encode($title) ?>" style="display:none" onload="$(this).fadeIn('slow')"/>
                <span class="overlay"><i class="glyphicon glyphicon-fullscreen"></i></span>
            </a>
        </div>
        <div class="panel-footer overlay">
            <div class="social-activities colorFont5 pull-right">
                <?php if ($footerOverwrite): ?>
                    <?= $footerOverwrite ?>
                <?php else: ?>
                    <?= LikeLink::widget(['object' => $contentObject]) ?> | <?= CommentLink::widget(['object' => $contentObject, 'mode' => CommentLink::MODE_POPUP]) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel-heading background-none <?= $alwaysShowHeading ? '' : 'overlay' ?>">
            <div class="footnotesize pull-left truncate tt" data-toggle="tooltip" data-placement="top" title=""
                 data-original-title="<?= $showTooltip ? Html::encode($title) : '' ?>">
                <?= Html::encode($title); ?>
            </div>
            <?php if ($creatorUrl || $creatorThumbnailUrl): ?>
                <div class="pull-right" style="margin-right:5px;">
                    <a href="<?= $creatorUrl ?>">
                        <img class="img-rounded tt img_margin"
                             src="<?= $creatorThumbnailUrl ?>"
                             width="21" height="21" alt="21x21" data-src="holder.js/21x21"
                             style="width: 21px; height: 21px;"
                             data-original-title="<?php echo Yii::t('GalleryModule.base', 'added by ') . $creatorName ?>"
                             data-placement="top" title="" data-toggle="tooltip">
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($wallUrl || $downloadUrl || ($writeAccess && ($deleteUrl || $editUrl))): ?>
                <ul class="pull-right nav nav-pills preferences">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-angle-down"></i></a>
                        <ul class="dropdown-menu pull-right">
                            <?php if ($wallUrl): ?>
                                <li>
                                    <a href="<?= $wallUrl ?>"><i
                                                class="fa fa-link"></i> <?= Yii::t('GalleryModule.base', 'Show connected post') ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($writeAccess): ?>
                                <?php if ($deleteUrl): ?>
                                    <li>
                                        <a data-action-click="ui.modal.post" data-action-url="<?= $deleteUrl ?>"
                                           data-action-confirm-header="<?= Yii::t('GalleryModule.base', '<strong>Confirm</strong> delete item') ?>"
                                           data-action-confirm="<?= Yii::t('GalleryModule.base', 'Do you really want to delete this item with all related content?') ?>">
                                            <i class="fa fa-trash"></i> <?= Yii::t('GalleryModule.base', 'Delete') ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                                <?php if ($editUrl): ?>
                                    <li>
                                        <a data-target="#globalModal" href="<?= $editUrl ?>"><i
                                                    class="fa fa-edit"></i> <?= Yii::t('GalleryModule.base', 'Edit') ?>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if ($downloadUrl): ?>
                                <li>
                                    <a data-pjax-prevent="1" href="<?= $downloadUrl ?>"><i
                                                class="fa fa-download"></i> <?= Yii::t('GalleryModule.base', 'Download') ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>