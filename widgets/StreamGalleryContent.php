<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \yii\base\Widget;

/**
 * Widget that renders the gallery content.
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class StreamGalleryContent extends Widget
{

    public $gallery;

    public function run()
    {
        return $this->render('stream_gallery_content', array('gallery' => $this->gallery));
    }

}
