<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \humhub\modules\file\handler\FileHandlerCollection;
use \humhub\modules\file\widgets\FileHandlerButtonDropdown;
use \humhub\modules\file\widgets\UploadButton;
use \humhub\modules\gallery\models\StreamGallery;
use \Yii;
use \yii\base\Widget;

/**
 * Widget that renders an entry inside a list in the gallery module
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class GalleryListEntryAdd extends Widget
{

    public $parentGallery;

    public function run()
    {
        $contentContainer = Yii::$app->controller->contentContainer;

        if(!Yii::$app->getModule('gallery')->canWrite($contentContainer)) {
            return;
        }

        // we do not want to render the add icon in stream galleries
        if ($this->parentGallery instanceof StreamGallery) {
            return;
        }

        if ($this->parentGallery) {
            return $this->render('galleryListEntryAdd', [
                        'title' => Yii::t('GalleryModule.base', 'Click or drop files here'),
                        'addActionUrl' => '#',
                        'htmlOptions' => [
                            'data' => [
                                'action-click' => "file.upload",
                                'action-target' => '#gallery-media-upload'
                            ]
                        ]
            ]);
        } else {
            return $this->render('galleryListEntryAdd', [
                        'title' => Yii::t('GalleryModule.base', 'Click here to add new Gallery'),
                        'addActionUrl' => $contentContainer->createUrl('/gallery/custom-gallery/edit'),
                        'htmlOptions' => [
                            'data' => [
                                'target' => "#globalModal",
                            ]
                        ]
            ]);
        }
    }

}
