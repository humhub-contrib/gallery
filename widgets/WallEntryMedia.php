<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use humhub\modules\file\converter\PreviewImage;
use humhub\modules\gallery\models\Media;
use humhub\libs\MimeHelper;

/**
 * @inheritdoc
 */
class WallEntryMedia extends \humhub\modules\content\widgets\WallEntry
{

    /**
     * @inheritdoc
     */
    public $editRoute = "/gallery/media/edit";

    /**
     * @inheritdoc
     */
    public $editMode = self::EDIT_MODE_MODAL;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $media = $this->contentObject;

        $galleryUrl = '#';
        if ($media->parentGallery !== null) {
            $galleryUrl = $media->parentGallery->getUrl();
        }

        return $this->render('wallEntryMedia', [
                    'media' => $media,
                    'title' => $media->getTitle(),
                    'fileSize' => $media->getSize(),
                    'file' => $media->baseFile,
                    'previewImage' => new PreviewImage(),
                    'galleryUrl' => $galleryUrl,
                    'mimeIconClass' => MimeHelper::getMimeIconClassByExtension($media->baseFile)
        ]);
    }

    /**
     * Returns the edit url to edit the content (if supported)
     *
     * @return string url
     */
    public function getEditUrl()
    {
        if (parent::getEditUrl() === "") {
            return "";
        }
        if ($this->contentObject instanceof Media) {
            return $this->contentObject->content->container->createUrl($this->editRoute, ['item-id' => $this->contentObject->getItemId(), 'fromWall' => true]);
        }
        return "";
    }

}
