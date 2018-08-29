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

use \humhub\modules\gallery\assets\Assets;
use \humhub\modules\gallery\widgets\GalleryList;
use humhub\modules\space\models\Space;
use \yii\helpers\Html;

$bundle = Assets::register($this);

$contentContainer = Yii::$app->controller->contentContainer;
$isAdmin = $contentContainer instanceof Space && $contentContainer->isAdmin();
?>

<div id="gallery-container" class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left" style="margin-right:40px;"><?= Yii::t('GalleryModule.base', '<strong>List</strong> of galleries'); ?></div>
        <?php if($canWrite) : ?>
            <div class="pull-right">
                <ul class="nav nav-pills preferences">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog"></i>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <?php if ($this->context->canWrite(false)): ?>
                                <?php if($isAdmin): ?>
                                <li>
                                    <a href="<?= $this->context->contentContainer->createUrl('/gallery/setting'); ?>">
                                        <i class="fa fa-cogs"></i> <?= Yii::t('GalleryModule.base', 'Settings') ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <li>
                                    <a data-target="#globalModal" href="<?= $this->context->contentContainer->createUrl('/gallery/custom-gallery/edit'); ?>">
                                        <i class="glyphicon glyphicon-plus"></i> <?= Yii::t('GalleryModule.base', 'Add Gallery') ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php endif ?>
        <div class="clearfix"></div>
    </div>

    <?= Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
        <div class="panel-body">
            <div class="row">
                <?= GalleryList::widget(['entryList' => array_merge($stream_galleries, $custom_galleries)]); ?>
            </div>
        </div>
    <?= Html::endForm(); ?>
</div>