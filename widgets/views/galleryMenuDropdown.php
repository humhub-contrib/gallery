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

use humhub\modules\file\widgets\UploadButton;
use humhub\modules\ui\icon\widgets\Icon;

?>

<?php

?>

<ul class="nav nav-pills">
    <li class="nav-item btn-group dropdown">
        <button class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
            <?= Icon::get('cog') ?>
        </button>
        <ul class="dropdown-menu dropdown-menu-end">

            <?php $uploadButton = UploadButton::widget([
                'id' => 'gallery-media-upload',
                'progress' => '#gallery-upload-progress',
                'url' => $uploadUrl,
                'tooltip' => false,
                'label' => Yii::t('GalleryModule.base', 'Upload'),
                'dropZone' => '#gallery-container',
                'asLink' => true,
            ]) ?>
            <li>
                <a href="#" class="dropdown-item" onclick="$(this).find('span')[0].click()"><?= $uploadButton ?></a>
            </li>
            <?php if ($editUrl): ?>
                <li>
                    <a data-bs-target="#globalModal" href="<?= $editUrl ?>" class="dropdown-item">
                        <i class="fa fa-edit"></i> <?= Yii::t('GalleryModule.base', 'Edit Gallery') ?>
                    </a>
                </li>
            <?php endif; ?>
            <?php if ($deleteUrl): ?>
                <li>
                    <a data-action-click="ui.modal.post" class="dropdown-item" data-action-url="<?= $deleteUrl ?>"
                       data-action-confirm-header="<?= Yii::t('GalleryModule.base', '<strong>Confirm</strong> delete gallery') ?>"
                       data-action-confirm="<?= Yii::t('GalleryModule.base', 'Do you really want to delete this gallery with all related content?') ?>">
                        <i class="fa fa-trash"></i> <?= Yii::t('GalleryModule.base', 'Delete Gallery') ?>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </li>
</ul>
