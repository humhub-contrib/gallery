<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use humhub\modules\gallery\helpers\Url;
use \humhub\modules\gallery\models\Media;
use \Yii;
use yii\web\ForbiddenHttpException;
use \yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Description of a Media Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class MediaController extends CustomGalleryController
{
    public function actionEdit($id = null, $fromWall = false)
    {
        $media = Media::find()->contentContainer($this->contentContainer)->where(['gallery_media.id' => $id])->one();

        if (!$media) {
            throw new HttpException(404);
        }

        if(!$media->content->canEdit()) {
            throw new ForbiddenHttpException();
        }

        if ($media->load(Yii::$app->request->post()) && $media->save()) {
            if ($fromWall) {
                return $this->asJson(['success' => true]);
            }

            $this->view->saved();
            return $this->htmlRedirect(Url::toCustomGallery($this->contentContainer, $media->gallery_id));
        }

        return $this->renderPartial('modal_media_edit', [
            'gid' => $media->gallery_id,
            'media' => $media,
            'contentContainer' => $this->contentContainer,
            'fromWall' => $fromWall
        ]);
    }

    public function actionDelete($id = null, $fromWall = false)
    {
        $this->forcePostRequest();

        $media = Media::findOne(['id' => $id]);

        if(!$media) {
            throw new NotFoundHttpException();
        }

        if(!$media->content->canEdit()) {
            throw new ForbiddenHttpException();
        }

        if($media->delete()) {
            $this->view->success(Yii::t('GalleryModule.base', 'Deleted'));
        } else {
            $this->view->error(Yii::t('GalleryModule.base', 'Item could not be deleted!'));
        }

        return !$fromWall
            ? $this->htmlRedirect(Url::toCustomGallery($this->contentContainer, $media->gallery_id))
            : $this->asJson([
                'success' => false
            ]);
    }

}
