<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\StreamGallery;
use \Yii;
use \yii\base\Widget;
use \yii\helpers\Html;

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

    public function run()
    {
        return $this->render('galleryList', [
                    'entryList' => $this->entryList,
                    'parentGallery' => $this->parentGallery
        ]);
    }
}
