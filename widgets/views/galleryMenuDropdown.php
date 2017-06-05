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

use humhub\modules\file\widgets\FileHandlerButtonDropdown;
?>

<div class="pull-right">
    <ul class="nav nav-pills preferences">
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fa fa-cog"></i>            
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu pull-right">

                <?php $uploadButton = humhub\modules\file\widgets\UploadButton::widget([
                            'id' => 'gallery-media-upload',
                            'progress' => '#gallery-upload-progress',
                            'url' => $uploadUrl,
                            'tooltip' => false,
                            'label' => Yii::t('GalleryModule.base', 'Upload'),
                            'dropZone' => '#gallery-container',
                            'buttonOptions' => [
                                    'class' => '',
                                    'style' => 'width:100%;'
                            ]
                ])?>
                <li>
                    <a href="#" onclick="$(this).find('span')[0].click()"><?= $uploadButton ?></a>
                </li>
                <?php if ($editUrl): ?>
                    <li>
                        <a data-target="#globalModal" href="<?= $editUrl ?>"><i class="fa fa-edit"></i> <?= Yii::t('GalleryModule.base', 'Edit Gallery') ?></a>
                    </li>
                <?php endif; ?>
                <?php if ($deleteUrl): ?>
                    <li>
                        <a data-action-click="ui.modal.post" data-action-url="<?= $deleteUrl ?>"
                                data-action-confirm-header="<?= Yii::t('GalleryModule.base', '<strong>Confirm</strong> delete gallery') ?>"
                                data-action-confirm="<?= Yii::t('GalleryModule.base', 'Do you really want to delete this gallery with all related content?') ?>">
                            <i class="fa fa-trash"></i> <?= Yii::t('GalleryModule.base', 'Delete Gallery') ?>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </li>
    </ul>
</div>