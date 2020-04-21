<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\models;

use \humhub\modules\file\converter\PreviewImage;
use Imagine\Image\Box;
use Imagine\Image\ManipulatorInterface;
use \Yii;
use yii\imagine\Image;

/**
 * SquareImageConverter
 *
 * @since 1.2
 * @author Sebastian Stumpf
 */
class SquarePreviewImage extends PreviewImage
{

    const DEFAULT_GALLERY_PREVIEW_IMAGE_MAX_DIM = 400;


    /**
     * @inheritDoc
     */
    public function init()
    {
        // Add value for unique file variant
        $this->options['squared-version'] = 1;
        $this->options['maxWidth'] = Yii::$app->getModule('gallery')->settings->get('galleryPreviewImageMaxDim') ?
            Yii::$app->getModule('gallery')->settings->get('galleryPreviewImageMaxDim') : SquarePreviewImage::DEFAULT_GALLERY_PREVIEW_IMAGE_MAX_DIM;


        parent::init();
    }


    /**
     * @inheritdoc
     */
    protected function convert($fileName)
    {
        if (!is_file($this->file->store->get($fileName))) {
            $image = Image::getImagine()->open($this->file->store->get());
            $newWidth = min([$image->getSize()->getHeight(), $image->getSize()->getWidth(), $this->options['maxWidth']]);

            // Create squared version
            $image->thumbnail(new Box($newWidth, $newWidth), ManipulatorInterface::THUMBNAIL_OUTBOUND)
                ->save($this->file->store->get($fileName), ['format' => 'png']);
        }

        if (version_compare(Yii::$app->version, '1.5', '<')) {
            $this->imageInfo = @getimagesize($this->file->store->get($fileName));
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
