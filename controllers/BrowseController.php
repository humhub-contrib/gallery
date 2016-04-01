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

    /**
     * Action to view a gallery or a the list of all galleries.
     * @url-param 'open-gallery-id' id of the open gallery. If empty the gallery list will be rendered.
     *
     * @return string the rendered view.
     */
    public function actionIndex()
    {
        return $this->renderGallery();
    }

    /**
     * Get this content container's galleries.
     *
     * @return null | array&lt;models\Gallery&gt;
     */
    protected function getGalleries()
    {
        $query = Gallery::find()->contentContainer($this->contentContainer)->readable();
        return $query->all();
    }

    /**
     * Render a specified gallery or the gallery list.
     * @url-param 'open-gallery-id' id of the open gallery. If empty the gallery list will be rendered.
     *
     * @param string $ajax
     *            render as ajax. default: false
     * @param string $openGalleryId
     *            the gallery to render, if null the gallery list will be rendered.
     */
    protected function renderGallery($ajax = false, $openGalleryId = null)
    {
        $gallery = $this->getOpenGallery($openGalleryId);
        if ($gallery != null) {
            return $ajax ? $this->renderAjax("/browse/gallery", [
                'gallery' => $gallery
            ]) : $this->render("/browse/gallery", [
                'gallery' => $gallery
            ]);
        }
        return $ajax ? $this->renderAjax("/browse/index", [
            'galleries' => $this->getGalleries()
        ]) : $this->render("/browse/index", [
            'galleries' => $this->getGalleries()
        ]);
    }
}
