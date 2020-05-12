<?php

use \humhub\modules\gallery\assets\Assets;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\BaseGallery;
use \humhub\modules\gallery\widgets\GalleryList;
use humhub\modules\space\models\Space;
use humhub\widgets\Link;
use humhub\widgets\ModalButton;
use \yii\helpers\Html;

/* @var BaseGallery[] $galleries*/
/* @var boolean $canWrite */
/* @var boolean $isAdmin */
/* @var boolean $showMore */

$bundle = Assets::register($this);
$container = Yii::$app->controller->contentContainer;
?>

<div id="gallery-container" class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="pull-left" style="margin-right:40px;"><?= Yii::t('GalleryModule.base', '<strong>List</strong> of galleries') ?></div>
        <?php if($canWrite) : ?>
            <div class="pull-right">
                <ul class="nav nav-pills preferences">
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-cog"></i>
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu pull-right">

                                <?php if($isAdmin): ?>
                                    <li>
                                        <?= Link::to(Yii::t('GalleryModule.base', 'Settings'),
                                            Url::toModuleConfig($container))->icon('cogs') ?>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <?= ModalButton::asLink(Yii::t('GalleryModule.base', 'Add Gallery'))
                                        ->load(Url::toCreateCustomGallery($container)) ->icon('plus') ?>
                                </li>
                        </ul>
                    </li>
                </ul>
            </div>
        <?php endif ?>
    </div>

    <div class="panel-body">
        <div class="row">
            <?= GalleryList::widget(['entryList' => $galleries, 'showMore' => $showMore]) ?>
        </div>
    </div>
</div>