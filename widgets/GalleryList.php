<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \yii\base\Widget;

/**
 * Widget that renders a gallery list.
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class GalleryList extends Widget
{

    public $stream_galleries;
    public $custom_galleries;

    public function run()
    {
        return $this->render('gallery_list', array('stream_galleries' => $this->stream_galleries, 'custom_galleries' => $this->custom_galleries));
    }

}
