<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use humhub\modules\gallery\models\StreamGallery;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\BaseGallery;
/**
 * @inheritdoc
 */
class WallEntryGallery extends \humhub\modules\content\widgets\WallEntry
{

    /**
     * @inheritdoc
     */
    public $editRoute = "/gallery/custom-gallery/edit";

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('wallEntryGallery', array('gallery' => $this->contentObject));
    }
    
    /**
     * Returns the edit url to edit the content (if supported)
     *
     * @return string url
     */
    public function getEditUrl()
    {    
        // Edit route for stream galleries differs
        if($this->contentObject instanceof StreamGallery) {
            $this->editRoute = "/gallery/stream-gallery/edit";
        }
        
        if (parent::getEditUrl() === "") {
            return "";
        }
        
        if(is_subclass_of($this->contentObject, BaseGallery::className())) {
            return $this->contentObject->content->container->createUrl($this->editRoute, ['item-id' => $this->contentObject->getItemId(), 'fromWall' => true]);
        }
    }

}
