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

    /**
     * Crop an image file to a square thumbnail.
     * The thumbnail will be saved with the suffix "&lt;width&gt;_thumb_square"
     * @param File $basefile the file to crop.
     * @param number $maxDimension limit maximum with/height.
     * @return string the thumbnail's url or null if an error occured.
     */
    public static function getSquarePreviewImageUrlFromFile($basefile = null)
    {
        $previewImage = new SquarePreviewImage();
        if ($previewImage->applyFile($basefile)) {
            return $previewImage->getUrl();
        } else {
            return "";
        }
    }
    
}
