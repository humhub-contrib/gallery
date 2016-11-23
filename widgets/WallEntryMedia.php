<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use humhub\modules\gallery\models\Media;
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
    public $showFiles = false;
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('wallEntryMedia', array('media' => $this->contentObject));
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
        if($this->contentObject instanceof Media) {
            return $this->contentObject->content->container->createUrl($this->editRoute, ['item-id' => $this->contentObject->getItemId(), 'fromWall' => true]);
        }
        return "";
    }

}
