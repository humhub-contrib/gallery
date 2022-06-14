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

use humhub\modules\file\converter\PreviewImage;
use humhub\modules\file\libs\FileHelper;
use humhub\libs\Html;
use humhub\modules\gallery\assets\WallEntryAssets;
use humhub\modules\gallery\models\Media;
use yii\web\View;

/* @var Media $media */
/* @var PreviewImage $previewImage */
/* @var string $galleryUrl */
/* @var string $galleryName */
/* @var View $this */

WallEntryAssets::register($this);
?>

<div class="gallery-wall-entry">
    <?php if ($previewImage->applyFile($media->baseFile)): ?>
        <?= $previewImage->renderGalleryLink(['style' => 'padding-right:12px']); ?>
    <?php else: ?>
        <i class="fa <?= $media->getIcon(); ?> fa-fw" style="font-size:40px"></i>
    <?php endif; ?>
</div>

<strong><?= FileHelper::createLink($media->baseFile, null, ['style' => 'text-decoration: underline']); ?></strong><br /><br>

<?php if (!empty($media->description)): ?>
    <?= Html::encode($media->description); ?>
    <br /><br>
<?php endif; ?>

<small>
    <?php if ($galleryName) : ?>
        <strong><?= Yii::t('GalleryModule.base', 'Gallery:'); ?></strong> <a href="<?= $galleryUrl ?>"><?= Html::encode($galleryName) ?></a><br />
    <?php endif ?>
    <strong><?= Yii::t('GalleryModule.base', 'Size:'); ?></strong> <?= Yii::$app->formatter->asShortSize($media->getSize(), 1); ?>
</small><br />


<br />

<?= Html::a(Yii::t('GalleryModule.base', 'Open Gallery'), $galleryUrl, ['class' => 'btn btn-sm btn-default', 'data-ui-loader' => '']); ?>

<div class="clearfix"></div>