<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

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
     *
     * @return redirect to /view.
     */
    public function actionIndex()
    {
        return $this->redirect('/gallery/stream-gallery/view');
    }

    /**
     * Action to render the custom gallery view specified by openGalleryId.
     * @url-param 'openGalleryId' id of the open gallery.
     *
     * @return The rendered view.
     */
    public function actionView()
    {
        return $this->renderGallery();
    }

    /**
     * Render a specified stream gallery or the gallery list.
     * @url-param 'openGalleryId' id of the open gallery. The gallery list is rendered if no gallery with this id is found.
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
        $id = ($openGalleryId == null) ? Yii::$app->request->get('openGalleryId') : $openGalleryId;
        return StreamGallery::findOne(['id' => $id]);
    }

    /**
     * 
     * @overwrite
     */
    public function canWrite($throw = true)
    {
        if ($throw) {
            throw new HttpException(401, Yii::t('GalleryModule.base', 'Insufficient rights to execute this action.'));
        }
        return false;
    }
}
