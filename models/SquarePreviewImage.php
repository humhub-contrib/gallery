<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2016 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\models;

use humhub\modules\file\converter\PreviewImage;
use humhub\modules\file\models\File;
use Imagine\Image\Format;
use Yii;
use yii\imagine\Image;

/**
 * SquareImageConverter
 *
 * @since 1.2
 * @author Sebastian Stumpf
 */
class SquarePreviewImage extends PreviewImage
{
    public const DEFAULT_GALLERY_PREVIEW_IMAGE_MAX_DIM = 400;

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return 'gallery-preview';
    }

    /**
     * @inheritDoc
     */
    public function init()
    {
        // Add value for unique file variant
        $this->options['squared-version'] = 1;
        $this->options['maxWidth'] = Yii::$app->getModule('gallery')->settings->get('galleryPreviewImageMaxDim') ?: SquarePreviewImage::DEFAULT_GALLERY_PREVIEW_IMAGE_MAX_DIM;


        parent::init();
    }

    /**
     * @inheritdoc
     */
    protected function convert($fileName)
    {
        if (!$this->file->store->has($fileName)) {
            $image = Image::getImagine()->load($this->file->store->getContent());

            $image = Image::autorotate($image);

            $newWidth = min([$image->getSize()->getHeight(), $image->getSize()->getWidth(), $this->options['maxWidth']]);

            // Create squared version
            $image = Image::thumbnail($image, $newWidth, $newWidth);

            $this->file->store->setContent($image->get(Format::ID_PNG, ['format' => 'png']), $fileName);
        }
    }


    /**
     * Crop an image file to a square thumbnail.
     * The thumbnail will be saved with the suffix "&lt;width&gt;_thumb_square"
     * @param File|null $basefile the file to crop.
     * @return string the thumbnail's url or null if an error occurred.
     */
    public static function getSquarePreviewImageUrlFromFile(?File $basefile = null): string
    {
        $previewImage = new SquarePreviewImage();
        if ($basefile && $previewImage->applyFile($basefile)) {
            return $previewImage->getUrl();
        } else {
            return '';
        }
    }

}
