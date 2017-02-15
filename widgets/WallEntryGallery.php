<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \humhub\modules\content\widgets\WallEntry;
use \humhub\modules\gallery\Module;

/**
 * @inheritdoc
 */
class WallEntryGallery extends WallEntry
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
        if (Module::canWrite($this->contentObject->content->container)) {
            return $this->contentObject->content->container->createUrl($this->editRoute, ['item-id' => $this->contentObject->getItemId(), 'fromWall' => true]);
        }
        return "";
    }

}
