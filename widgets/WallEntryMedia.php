<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use humhub\modules\gallery\models\Media;
use humhub\modules\gallery\permissions\WriteAccess;
use humhub\modules\gallery\Module;
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
        if(Module::canWrite($this->contentObject->content->container)) {
            return $this->contentObject->content->container->createUrl($this->editRoute, ['item-id' => $this->contentObject->getItemId(), 'fromWall' => true]);
        }
        return "";
        
    }

}
