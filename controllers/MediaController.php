<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\gallery\models\Media;
use \Yii;
use \yii\web\HttpException;

/**
 * Description of a Media Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class MediaController extends CustomGalleryController
{

    /**
     * Action to edit a media object.
     * @url-param 'item-id' the gallery's id.
     * @url-param 'open-gallery-id' id of the open gallery. Used for redirecting.
     *
     * @throws HttpException if insufficient permission.
     * @return string the redered html.
     */
    public function actionEdit()
    {
        $this->canWrite(true);

        $fromWall = Yii::$app->request->get('fromWall');
        $itemId = Yii::$app->request->get('item-id');
        $openGalleryId = Yii::$app->request->get('open-gallery-id');
        $media = $this->module->getItemById($itemId);

        // no media file with given item id exists
        if (empty($media) || !($media instanceof Media)) {
            $this->view->error(Yii::t('GalleryModule.base', 'Not found'));
        }

        $data = Yii::$app->request->post('Media');

        if ($data !== null && $media->load(Yii::$app->request->post()) && $media->validate()) {
            $media->save();
            if ($fromWall) {
                return $this->asJson(['success' => true]);
            } else {
                $this->view->saved();
                return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/custom-gallery/view', ['open-gallery-id' => $openGalleryId]));
                // TODO: only load the changed element for better performance
            }
        }

        // render modal
        return $this->renderPartial('/media/modal_media_edit', [
                    'openGalleryId' => $openGalleryId,
                    'media' => $media,
                    'contentContainer' => $this->contentContainer,
                    'fromWall' => $fromWall
        ]);
    }

}
