<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\content\models\Content;
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
     * @url-param 'itemId' the gallery's id.
     * @url-param 'openGalleryId' id of the open gallery. Used for redirecting.
     *
     * @throws HttpException if insufficient permission.
     * @return string the redered html.
     */
    public function actionEdit($itemId = null, $openGalleryId = null, $fromWall = false, $visibility = Content::VISIBILITY_PRIVATE)
    {
        $this->canWrite(true);

        $media = $this->module->getItemById($itemId);

        if (empty($media) || !($media instanceof Media)) {
            throw new HttpException(404);
        }

        $data = Yii::$app->request->post('Media');

        if ($data !== null && $media->load(Yii::$app->request->post()) && $media->save()) {
            if ($fromWall) {
                return $this->asJson(['success' => true]);
            } else {
                $this->view->saved();
                return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/custom-gallery/view', ['openGalleryId' => $openGalleryId]));
            }
        }

        return $this->renderPartial('modal_media_edit', [
                    'openGalleryId' => $openGalleryId,
                    'media' => $media,
                    'contentContainer' => $this->contentContainer,
                    'fromWall' => $fromWall
        ]);
    }

}
