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
     *
     * @var boolean Render dropdown or buttons row. 
     */
    public $dropdown = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $fileHandlerImport = FileHandlerCollection::getByType(FileHandlerCollection::TYPE_IMPORT);
        $fileHandlerCreate = FileHandlerCollection::getByType(FileHandlerCollection::TYPE_CREATE);

        $deleteUrl = $this->contentContainer->createUrl('/gallery/list/delete-multiple', ['item-id' => $this->gallery->getItemId()]);
        $editUrl = $this->contentContainer->createUrl('edit', ['open-gallery-id' => $this->gallery->id, 'item-id' => $this->gallery->getItemId()]);
        $uploadUrl = $this->contentContainer->createUrl('upload', ['open-gallery-id' => $this->gallery->id]);

        return $this->render($this->dropdown ? 'galleryMenuDropdown' :'galleryMenu', [
                    'canWrite' => $this->canWrite,
                    'fileHandlers' => array_merge($fileHandlerCreate, $fileHandlerImport),
                    'deleteUrl' => $deleteUrl,
                    'editUrl' => $editUrl,
                    'uploadUrl' => $uploadUrl,
        ]);
    }

}
