<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \yii\base\Widget;

/**
 * Widget that renders a list of entries in the gallery module.
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class GalleryList extends Widget
{

    public $entryList;
    public $parentGallery;
    public $entriesOnly = false;
    public $showMore = true;

    public function run()
    {
        return $this->render('galleryList', [
            'entryList' => $this->entryList,
            'entriesOnly' => $this->entriesOnly,
            'parentGallery' => $this->parentGallery,
            'showMore' => $this->showMore
        ]);
    }
}
