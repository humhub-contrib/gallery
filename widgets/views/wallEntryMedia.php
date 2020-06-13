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

use humhub\modules\file\libs\FileHelper;
use humhub\libs\Html;
?>

<div class="pull-left" style="min-height:133px;">
    <?php if ($previewImage->applyFile($file)): ?>
        <?= $previewImage->renderGalleryLink(['style' => 'padding-right:12px']); ?>
    <?php else: ?>
        <i class="fa <?= $media->getIcon(); ?> fa-fw" style="font-size:40px"></i>
    <?php endif; ?>
</div>

<span></span>
<strong><?= FileHelper::createLink($file, null, ['style' => 'text-decoration: underline']); ?></strong><br /><br>

<?php if (!empty($media->description)): ?>
    <?= Html::encode($media->description); ?>
    <br /><br>
<?php endif; ?>

<small>
    <?php if($galleryName) : ?>
        <strong><?= Yii::t('GalleryModule.base', 'Gallery:'); ?></strong> <a href="<?= $galleryUrl ?>"><?= Html::encode($galleryName) ?></a><br />
    <?php endif ?>
    <strong><?= Yii::t('GalleryModule.base', 'Size:'); ?></strong> <?=Yii::$app->formatter->asShortSize($fileSize, 1); ?>
</small><br />


<br />

<?= Html::a(Yii::t('GalleryModule.base', 'Open Gallery'), $galleryUrl, ['class' => 'btn btn-sm btn-default', 'data-ui-loader' => '']); ?>

<div class="clearfix"></div>