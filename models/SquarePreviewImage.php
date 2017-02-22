<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\models;

use \humhub\modules\file\converter\PreviewImage;
use \Yii;

/**
 * SquareImageConverter
 *
 * @since 1.2
 * @author Sebastian Stumpf
 */
class SquarePreviewImage extends PreviewImage
{

    const DEFAULT_GALLERY_PREVIEW_IMAGE_MAX_DIM = 400;

    public function applyFile(\humhub\modules\file\models\File $file)
    {
        if (parent::applyFile($file)) {
            $this->options['mode'] = 'force';
            $galleryPreviewImageMaxDim = Yii::$app->getModule('gallery')->settings->get('galleryPreviewImageMaxDim') ? Yii::$app->getModule('gallery')->settings->get('galleryPreviewImageMaxDim') : SquarePreviewImage::DEFAULT_GALLERY_PREVIEW_IMAGE_MAX_DIM;
            $imageInfo = @getimagesize($file->store->get());
            $dim = min($imageInfo[0], $imageInfo[1], $galleryPreviewImageMaxDim);
            $this->options['width'] = $dim;
            $this->options['height'] = $dim;
            return true;
        } else {
            return false;
        }
    }

}
