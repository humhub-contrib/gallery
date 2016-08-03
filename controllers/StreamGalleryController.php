<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use humhub\modules\gallery\models\StreamGallery;

/**
 * Description of a Stream Gallery Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class StreamGalleryController extends ListController
{

    /**
     *
     * @return redirect to /view.
     */
    public function actionIndex()
    {
        return $this->redirect('/gallery/stream-gallery/view');
    }

    /**
     * Action to render the custom gallery view specified by open-gallery-id.
     * @url-param 'open-gallery-id' id of the open gallery.
     *
     * @return The rendered view.
     */
    public function actionView()
    {
        return $this->renderGallery();
    }

    /**
     * Action to edit a gallery.
     * @url-param 'item-id' the gallery's id.
     * @url-param 'open-gallery-id' id of the open gallery. Used for redirecting.
     *
     * @throws HttpException if insufficient permission.
     * @return string the redered html.
     */
    public function actionEdit()
    {
        $this->canWrite(true);
        
        $itemId = Yii::$app->request->get('item-id');
        $openGalleryId = Yii::$app->request->get('open-gallery-id');
        // check if a gallery with the given id exists.
        $gallery = $this->module->getItemById($itemId);
        
        // if no gallery is found with the given id, a new one has to be created
        if (! ($gallery instanceof StreamGallery)) {
            // stream gallery must not be created by user
            return -1;            
        }
        
        $data = Yii::$app->request->post('StreamGallery');
        
        if ($data !== null && $gallery->load(Yii::$app->request->post()) && $gallery->validate()) {
            $gallery->save();
            return $this->renderGallery(true);
        }
        
        // render modal
        return $this->renderAjax('/stream-gallery/modal_gallery_edit', [
            'openGalleryId' => $openGalleryId,
            'gallery' => $gallery
        ]);
    }

    /**
     * Render a specified stream gallery or the gallery list.
     * @url-param 'open-gallery-id' id of the open gallery. The gallery list is rendered if no gallery with this id is found.
     *
     * @param string $ajax
     *            render as ajax. default: false
     * @param string $openGalleryId
     *            the stream gallery to render.
     */
    protected function renderGallery($ajax = false, $openGalleryId = null)
    {
        $gallery = $this->getOpenGallery($openGalleryId);
        if ($gallery != null) {
            return $ajax ? $this->renderAjax("/stream-gallery/gallery_view", [
                'gallery' => $gallery
            ]) : $this->render("/stream-gallery/gallery_view", [
                'gallery' => $gallery
            ]);
        } else {
            return parent::renderGallery($ajax);
        }
    }

    protected function getOpenGallery($openGalleryId = null)
    {
        $id = $openGalleryId == null ? Yii::$app->request->get('open-gallery-id') : $openGalleryId;
        return StreamGallery::findOne([
            'id' => $id
        ]);
    }
}
