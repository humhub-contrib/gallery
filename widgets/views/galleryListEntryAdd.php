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

$bundle = Assets::register($this);
?>
<div class="col-xs-6 col-sm-4 col-md-3 col-lg-2 col-xl-2 gallery-list-entry add-entry">
    <div class="panel panel-default">
        <div class="panel-body">
            <a class="backgroundSuccess" <?= yii\bootstrap\Html::renderTagAttributes($htmlOptions) ?> href="<?= $addActionUrl ?>">
                <img class="padding15perc" src="<?= Yii::$app->getModule('gallery')->getAssetsUrl() . '/plus.svg' ?>" />
                <span class="overlay"></span>
            </a>
        </div>
        <div class="panel-heading background-none">
            <a class="backgroundSuccess" <?= yii\bootstrap\Html::renderTagAttributes($htmlOptions) ?> href="<?= $addActionUrl ?>">
                <span class="pull-left"><i class="fa fa-plus"></i> <?= $title ?></span>
            </a>
        </div>
    </div>
</div>