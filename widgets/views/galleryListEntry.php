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

use humhub\modules\comment\widgets\CommentLink;
use humhub\modules\content\widgets\ContentObjectLinks;
use humhub\modules\gallery\assets\Assets;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\bootstrap\Link;
use yii\helpers\Html;

/* @var $fileUrl string */
/* @var $uiGalleryId string */
/* @var $imagePadding string */
/* @var $thumbnailUrl string */
/* @var $footerOverwrite string */
/* @var $contentObject \humhub\modules\gallery\models\Media */
/* @var $alwaysShowHeading boolean */
/* @var $showTooltip boolean */
/* @var $creatorUrl string */
/* @var $creatorThumbnailUrl string */
/* @var $creatorName string */

$bundle = Assets::register($this);
?>


<div class="col-6 col-md-4 col-lg-3 col-xl-2 col-xl-2 gallery-list-entry">
    <div class="panel panel-default">
        <div class="panel-body">
            <a href="<?= $fileUrl ?>#.jpeg"
                <?php if ($uiGalleryId): ?>
                    data-type="image"
                    data-bs-toggle="lightbox"
                    data-parent="#gallery-content"
                    data-description="<?= Html::encode($title) ?>"
                    title="<?= Html::encode($title) ?>"
                    data-ui-gallery="<?= $uiGalleryId ?>"
                <?php endif; ?>>
                <img class="gallery-img d-none <?= $imagePadding ? 'padding15perc' : '' ?>" src="<?= $thumbnailUrl ?>"
                     alt="<?= Html::encode($title) ?>" />
                <span class="overlay"><?= Icon::get('arrows-alt') ?></span>
            </a>
        </div>
        <div class="panel-footer overlay">
            <div class="social-activities float-end">
                <?php if ($footerOverwrite): ?>
                    <?= $footerOverwrite ?>
                <?php else: ?>
                    <?= ContentObjectLinks::widget([
                        'object' => $contentObject,
                        'widgetParams' => [CommentLink::class => ['mode' => CommentLink::MODE_POPUP]],
                    ]); ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="panel-heading <?= $alwaysShowHeading ? '' : 'overlay' ?>">
            <div class="footnotesize float-start truncate tt" data-bs-toggle="tooltip" data-placement="top" title=""
                 data-bs-title="<?= $showTooltip ? Html::encode($title) : '' ?>">
                <?= Html::encode($title); ?>
            </div>
            <?php if ($creatorUrl || $creatorThumbnailUrl): ?>
                <div class="float-end" style="margin-right:5px;">
                    <a href="<?= $creatorUrl ?>">
                        <img class="rounded tt img_margin"
                             src="<?= $creatorThumbnailUrl ?>"
                             width="21" height="21" alt="21x21" data-src="holder.js/21x21"
                             style="width: 21px; height: 21px;"
                             data-bs-title="<?= Yii::t('GalleryModule.base', 'added by ') . $creatorName ?>"
                             data-placement="top" title="" data-bs-toggle="tooltip">
                    </a>
                </div>
            <?php endif; ?>

            <?php if ($wallUrl || $downloadUrl || ($writeAccess && ($deleteUrl || $editUrl))): ?>
                <ul class="float-end nav nav-pills">
                    <li class="nav-item btn-group dropdown">
                        <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if ($wallUrl): ?>
                                <li>
                                    <?= Link::to(Yii::t('GalleryModule.base', 'Show connected post'), $wallUrl)
                                        ->icon('link')
                                        ->cssClass('dropdown-item') ?>
                                </li>
                            <?php endif; ?>
                            <?php if ($writeAccess && $editUrl): ?>
                                <li>
                                    <?= Link::modal(Yii::t('GalleryModule.base', 'Edit'))
                                        ->load($editUrl)
                                        ->icon('edit')
                                        ->cssClass('dropdown-item') ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($downloadUrl): ?>
                                <li>
                                    <?= Link::to(Yii::t('GalleryModule.base', 'Download'), $downloadUrl)
                                        ->pjax(false)
                                        ->icon('download')
                                        ->cssClass('dropdown-item') ?>
                                </li>
                            <?php endif; ?>

                            <?php if ($writeAccess && $deleteUrl): ?>
                                <li>
                                    <?= Link::modal(Yii::t('GalleryModule.base', 'Delete'))
                                        ->post($deleteUrl)
                                        ->confirm(
                                            Yii::t('GalleryModule.base', '<strong>Confirm</strong> delete item'),
                                            Yii::t('GalleryModule.base', 'Do you really want to delete this item with all related content?'),
                                        )
                                        ->icon('trash')
                                        ->cssClass('dropdown-item') ?>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</div>
