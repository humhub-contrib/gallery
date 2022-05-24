<?php

use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\user\models\User;
use yii\helpers\Html;

/* @var $originator User */
/* @var $source CustomGallery */

echo Yii::t('GalleryModule.base', '{displayName} uploaded new media files into the Gallery "{galleryName}"', [
    'displayName' => '<strong>' . Html::encode($originator->displayName) . '</strong>',
    'galleryName' => Html::encode($source->title)
]);