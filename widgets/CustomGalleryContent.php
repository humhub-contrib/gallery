<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\widgets;

/**
 * Widget that renders the gallery content.
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class CustomGalleryContent extends \yii\base\Widget
{
    
    public $gallery;
    public $context;

    public function run()
    {
        return $this->render('custom_gallery_content', array('gallery' => $this->gallery));
    }
}

?>