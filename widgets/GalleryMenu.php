<?php

namespace humhub\modules\gallery\widgets;

use humhub\modules\file\handler\FileHandlerCollection;
use humhub\modules\gallery\helpers\Url;

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

        return $this->render('galleryMenuDropdown', [
                    'deleteUrl' =>  Url::toDeleteCustomGallery($this->contentContainer, $this->gallery->id),
                    'editUrl' => Url::toEditCustomGallery($this->contentContainer, $this->gallery->id),
                    'uploadUrl' => Url::toUploadMedia($this->contentContainer, $this->gallery->id),
        ]);
    }

}
