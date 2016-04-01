<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use humhub\modules\gallery\models\Gallery;
use humhub\modules\gallery\models\Media;

/**
 * Description of EditController for gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class EditController extends BrowseController
{

    /**
     * Action to edit a gallery.
     * @url-param 'item-id' the gallery's id.
     * @url-param 'open-gallery-id' id of the open gallery. Used for redirecting.
     *
     * @throws HttpException if insufficient permission.
     * @return string the redered html.
     */
    public function actionGallery()
    {
        $this->canWrite(true);
        
        $itemId = Yii::$app->request->get('item-id');
        $openGalleryId = Yii::$app->request->get('open-gallery-id');
        // check if a gallery with the given id exists.
        $gallery = $this->module->getItemById($itemId);
        
        // if no gallery is found with the given id, a new one has to be created
        if (! ($gallery instanceof Gallery)) {
            // create a new gallery
            $gallery = new Gallery();
            $gallery->content->container = $this->contentContainer;
        }
        
        $data = Yii::$app->request->post('Gallery');
        
        if ($data !== null && $gallery->load(Yii::$app->request->post()) && $gallery->validate()) {
            $gallery->save();
            return $this->renderGallery(true);
        }
        
        // render modal
        return $this->renderAjax('modal_edit_gallery', [
            'openGalleryId' => $openGalleryId,
            'gallery' => $gallery
        ]);
    }

    /**
     * Action to edit a media object.
     * @url-param 'item-id' the gallery's id.
     * @url-param 'open-gallery-id' id of the open gallery. Used for redirecting.
     *
     * @throws HttpException if insufficient permission.
     * @return string the redered html.
     */
    public function actionMedia()
    {
        $this->canWrite(true);
        
        $itemId = Yii::$app->request->get('item-id');
        $openGalleryId = Yii::$app->request->get('open-gallery-id');
        // check if a gallery with the given id exists.
        $media = $this->module->getItemById($itemId);
        
        // if no gallery is found with the given id, a new one has to be created
        if (! ($media instanceof Media)) {
            // create a new gallery
            $media = new Media();
            $media->content->container = $this->contentContainer;
        }
        
        $data = Yii::$app->request->post('Media');
        
        if ($data !== null && $media->load(Yii::$app->request->post()) && $media->validate()) {
            $media->save();
            return $this->renderGallery(true);
        }
        
        // render modal
        return $this->renderAjax('modal_edit_media', [
            'openGalleryId' => $openGalleryId,
            'media' => $media
        ]);
    }
}
