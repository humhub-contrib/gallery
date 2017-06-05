<?php

namespace humhub\modules\gallery\widgets;

use humhub\modules\file\handler\FileHandlerCollection;

/**
 * Widget for rendering the menue buttons for the gallery module.
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class GalleryMenu extends \yii\base\Widget
{

    /**
     * var humhub\modules\gallery\models\BaseGallery Current gallery model instance.
     */
    public $gallery;

    /**
     * @var \humhub\modules\content\components\ContentContainerActiveRecord Current content container.
     */
    public $contentContainer;

    /**
     * @var boolean Determines if the user has write permissions.
     */
    public $canWrite;

    /**
     * @var integer FileList item count.
     */
    public $itemCount;

    /**
     * @inheritdoc
     */
    public function run()
    {
        if(!$this->canWrite) {
            return;
        }

        $fileHandlerImport = FileHandlerCollection::getByType(FileHandlerCollection::TYPE_IMPORT);
        $fileHandlerCreate = FileHandlerCollection::getByType(FileHandlerCollection::TYPE_CREATE);

        $deleteUrl = $this->contentContainer->createUrl('/gallery/list/delete-multiple', ['itemId' => $this->gallery->getItemId()]);
        $editUrl = $this->contentContainer->createUrl('edit', ['openGalleryId' => $this->gallery->id, 'itemId' => $this->gallery->getItemId()]);
        $uploadUrl = $this->contentContainer->createUrl('upload', ['openGalleryId' => $this->gallery->id]);

        return $this->render('galleryMenuDropdown', [
                    'deleteUrl' => $deleteUrl,
                    'editUrl' => $editUrl,
                    'uploadUrl' => $uploadUrl,
        ]);
    }

}
