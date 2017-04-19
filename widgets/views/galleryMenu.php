<?php

use humhub\modules\file\widgets\FileHandlerButtonDropdown;
?>
<?= \humhub\modules\file\widgets\UploadProgress::widget(['id' => 'gallery-upload-progress']) ?>
<?php if ($canWrite): ?>
    <div class="row button-action-menu">
        <div class="col-sm-4">
            <?php
            $uploadButton = humhub\modules\file\widgets\UploadButton::widget([
                        'id' => 'gallery-media-upload',
                        'progress' => '#gallery-upload-progress',
                        'url' => $uploadUrl,
                        'tooltip' => false,
                        'cssButtonClass' => 'btn-success',
                        'label' => Yii::t('GalleryModule.base', 'Upload'),
                        'dropZone' => '#gallery-container'
                    ])
            ?>
            <?= FileHandlerButtonDropdown::widget(['primaryButton' => $uploadButton, 'handlers' => $fileHandlers, 'cssButtonClass' => 'btn-success', 'pullRight' => true]); ?>
        </div>
        <div class="col-sm-4">
            <a class="btn btn-default" data-target="#globalModal"
               href="<?= $editUrl ?>">Edit
                Gallery</a>
        </div>
        <div class="col-sm-4">
            <a class="btn btn-danger" data-target="#globalModal"
               href="<?= $deleteUrl ?>">Delete
                Gallery</a>
        </div>
    </div>
<?php endif; ?>

