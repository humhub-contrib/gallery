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

use humhub\modules\content\components\ContentActiveRecord;
use humhub\modules\file\models\File;
use humhub\modules\gallery\assets\Assets;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\StreamGallery;
use humhub\widgets\Button;
use yii\helpers\Html;
use humhub\modules\space\models\Space;
use humhub\modules\gallery\widgets\GalleryList;

/* @var $files File[] */
/* @var $gallery StreamGallery */
/* @var $showMore boolean */
/* @var $container ContentActiveRecord */

$bundle = Assets::register($this);

$description = ($container instanceof Space)
    ?  Yii::t('GalleryModule.base', 'This gallery contains all posted media files from the space.')
    : Yii::t('GalleryModule.base', 'This gallery contains all posted media files from the profile.');
?>

<div id="gallery-container" class="panel panel-default">

    <div class="panel-heading" style="background-color: <?= $this->theme->variable('background-color-secondary') ?>"><?= Yii::t('GalleryModule.base', '<strong>Gallery</strong> ') . Yii::t('GalleryModule.base', 'of posted media files') ?></div>

    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 gallery-description">
                <i class="fa fa-arrow-circle-right"></i>
                <?= Html::encode($description) ?>
                <?= Button::back(Url::toGalleryOverview($container), Yii::t('GalleryModule.base', 'Back to overview'))->right()->sm() ?>
            </div>
        </div>

        <div class="row">
            <?= GalleryList::widget([
                'entryList' => $files,
                'parentGallery' => $gallery,
                'showMore' => $showMore
            ]) ?>
        </div>
    </div>
</div>