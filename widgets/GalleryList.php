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
        $writeAccess = Yii::$app->controller->canWrite(false);

        return $this->render('galleryList', [
                    'entryList' => $this->entryList,
                    'parentGallery' => $this->parentGallery,
                    'htmlContentEmpty' => $this->getHtmlContentEmpty($this->parentGallery, $writeAccess)
        ]);
    }

    public function getHtmlContentEmpty($gallery, $writeAccess)
    {
        $html = Html::beginTag('div', ['class' => 'noVisibleContent']);
        if ($gallery instanceof StreamGallery) {
            $html .= Html::tag('span', Yii::t('GalleryModule.base', 'This gallery is still empty.'));
            if ($writeAccess) {
                $html .= Html::tag('br');
                $html .= Html::tag('strong', Html::tag('span', Yii::t('GalleryModule.base', 'Upload photos or Videos to the stream to see them here.')));
            }
        } elseif ($gallery instanceof CustomGallery) {
            $html .= Html::tag('span', Yii::t('GalleryModule.base', 'This gallery is still empty.'));
            if ($writeAccess) {
                $html .= Html::tag('br');
                $html .= Html::tag('strong', Html::tag('span', Yii::t('GalleryModule.base', 'Use the buttons above to upload photos or videos.')));
            }
        } else {
            if ($writeAccess) {
                $html .= Html::tag('span', Yii::t('GalleryModule.base', 'There are no galleries yet.'));
                $html .= Html::tag('br');
                $html .= Html::tag('strong', Html::tag('span', Yii::t('GalleryModule.base', 'Use the button above to create some.')));
            } else {
                $html .= Html::tag('span', Yii::t('GalleryModule.base', 'There are no visible galleries.'));
            }
        }
        $html .= Html::endTag('div');
        return $html;
    }

}
