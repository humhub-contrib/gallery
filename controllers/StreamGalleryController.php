<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\content\components\ContentActiveRecord;
use \humhub\modules\gallery\libs\FileUtils;
use \humhub\modules\gallery\models\StreamGallery;
use \Yii;
use \yii\web\HttpException;

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
     * Ajax action that returns the wall entry content of a given item.
     */
    public function actionGetWallEntry()
    {
        $itemId = Yii::$app->request->get('item-id');
        // check if a gallery with the given id exists.
        $item = $this->module->getItemById($itemId);
        $post = FileUtils::getBasePost($item);
        var_dump($post);
        var_dump($post->className());
        var_dump($post instanceof ContentActiveRecord);
        return ($post instanceof ContentActiveRecord) ? $post->getWallOut() : "";
    }

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
        if (!($gallery instanceof StreamGallery)) {
            // stream gallery must not be created by user
            return -1;
        }

        $data = Yii::$app->request->post('StreamGallery');

        if ($data !== null && $gallery->load(Yii::$app->request->post()) && $gallery->validate()) {
            $gallery->save();
            $this->view->saved();
            return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/custom-gallery/view', ['open-gallery-id' => $openGalleryId]));
            // TODO: only load the changed element
            // return $this->renderGallery(true, $openGalleryId);
        }

        // render modal
        return $this->renderAjax('/stream-gallery/modal_gallery_edit', [
                    'openGalleryId' => $openGalleryId,
                    'gallery' => $gallery,
                    'contentContainer' => $this->contentContainer,
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
