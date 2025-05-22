<?php

use humhub\modules\gallery\assets\Assets;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\BaseGallery;
use humhub\modules\gallery\widgets\GalleryList;
use humhub\modules\ui\icon\widgets\Icon;
use humhub\widgets\bootstrap\Link;
use humhub\widgets\modal\ModalButton;

/* @var BaseGallery[] $galleries*/
/* @var boolean $canWrite */
/* @var boolean $isAdmin */
/* @var boolean $showMore */

$bundle = Assets::register($this);
$container = Yii::$app->controller->contentContainer;
?>

<div id="gallery-container" class="panel panel-default">
    <div class="panel-heading clearfix">
        <div class="float-start" style="margin-right:40px;"><?= Yii::t('GalleryModule.base', '<strong>List</strong> of galleries') ?></div>
        <?php if ($canWrite) : ?>
            <div class="float-end">
                <ul class="nav nav-pills">
                    <li class="nav-item btn-group dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            <?= Icon::get('cog') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">

                                <?php if ($isAdmin): ?>
                                    <li>
                                        <?= Link::to(Yii::t('GalleryModule.base', 'Settings'), Url::toModuleConfig($container))
                                            ->icon('cogs')
                                            ->cssClass('dropdown-item') ?>
                                    </li>
                                <?php endif; ?>

                                <li>
                                    <?= ModalButton::asLink(Yii::t('GalleryModule.base', 'Add Gallery'))
                                        ->load(Url::toCreateCustomGallery($container))
                                        ->icon('plus')
                                        ->cssClass('dropdown-item') ?>
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
