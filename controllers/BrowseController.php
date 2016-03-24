<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use humhub\modules\gallery\models\Gallery;

/**
 * Description of BrowseController for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class BrowseController extends BaseController
{

    public function actionIndex()
    {
        return $this->renderGallery();
    }

    protected function getGalleries()
    {
        $query = Gallery::find()->contentContainer($this->contentContainer)->readable();
        return $query->all();
    }

    protected function renderGallery($ajax = false, $openGalleryId = null)
    {
        if($openGalleryId == null) {
            $openGalleryId = Yii::$app->request->get('open-gallery-id');
        }
        if ($openGalleryId != null) {
            $gallery = Gallery::findOne([
                'id' => $openGalleryId
            ]);
            if ($gallery != null) {
                return $ajax ? $this->renderAjax("/browse/gallery", [
                    'gallery' => $gallery
                ]) : $this->render("/browse/gallery", [
                    'gallery' => $gallery
                ]);
            }
        }
        return $ajax ? $this->renderAjax("/browse/index", [
            'galleries' => $this->getGalleries()
        ]) : $this->render("/browse/index", [
            'galleries' => $this->getGalleries()
        ]);
    }
}
