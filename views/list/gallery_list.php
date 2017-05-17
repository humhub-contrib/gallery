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
use \yii\helpers\Html;

$bundle = Assets::register($this);
?>

<div id="gallery-container" class="panel panel-default">
    <div class="panel-heading">
        <div class="pull-left" style="margin-right:40px;"><?php echo Yii::t('GalleryModule.base', '<strong>List</strong> of galleries'); ?></div>
        <div class="pull-right">
            <ul class="nav nav-pills preferences">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-cog"></i>            
                        <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <?php if ($this->context->canWrite(false)): ?>
                            <li>
                                <a data-target="#globalModal" href="<?php echo $this->context->contentContainer->createUrl('/gallery/custom-gallery/edit'); ?>"><i class="glyphicon glyphicon-plus"></i> <?= Yii::t('GalleryModule.base', 'Add Gallery') ?></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
    </div>
    <?php echo Html::beginForm(null, null, ['data-target' => '#globalModal', 'id' => 'gallery-form']); ?>
    <div class="panel-body">
        <div class="row">
            <?php echo GalleryList::widget(['entryList' => array_merge($stream_galleries, $custom_galleries)]); ?>
        </div>
    </div>
    <?php echo Html::endForm(); ?>
</div>