<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use humhub\modules\file\libs\FileHelper;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\BaseGallery;
use \humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\forms\GalleryEditForm;
use \humhub\modules\gallery\models\Media;
use humhub\modules\gallery\permissions\WriteAccess;
use \Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use \yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use \yii\web\UploadedFile;

/**
 * Description of a Custom Gallery Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class CustomGalleryController extends BaseController
{

    /**
     * @var CustomGallery
     */
    private $gallery;

    /**
     * @inheritDoc
     */
    protected function getPaginationQuery()
    {
        return $this->getGallery()->mediaListQuery()->contentContainer($this->contentContainer)->readable();
    }

    /**
     * @inheritDoc
     */
    protected function renderGallery($items)
    {
        $gallery = $this->getGallery();

        if(!$gallery->content->canView()) {
            throw new ForbiddenHttpException();
        }

        return $this->render("gallery_view", [
            'gallery'=> $gallery,
            'media' => $items,
            'container' => $this->contentContainer,
            'showMore' => !$this->isLastPage() ? Url::toShowMoreMedia($this->contentContainer, $gallery->id) : false
        ]);
    }

    /**
     * Searches for gallery with given id related to this container.
     * @param $gid
     * @return CustomGallery
     * @throws NotFoundHttpException
     * @throws Exception
     */
    protected function getGallery()
    {
        if($this->gallery) {
            return $this->gallery;
        }

        $gid = Yii::$app->request->get('gid');

        if(!$gid) {
            throw new BadRequestHttpException();
        }

        $this->gallery = CustomGallery::find()->contentContainer($this->contentContainer)->where(['gallery_gallery.id' => $gid])->one();

        if(!$this->gallery) {
            throw new NotFoundHttpException();
        }

        return $this->gallery;
    }

    /**
     * Edit/Create new custom gallery
     *
     * @param null|int $gid
     * @throws \Throwable
     * @return Response|string
     */
    public function actionEdit($gid = null)
    {
        if(!$gid && !$this->contentContainer->can(WriteAccess::class)) {
            throw new ForbiddenHttpException();
        }

        $gallery = $gid ? $this->getGallery($gid) : null;

        if($gallery && !$gallery->content->canEdit()) {
            throw new ForbiddenHttpException();
        }

        $form = new GalleryEditForm(['contentContainer' => $this->contentContainer, 'instance' => $gallery]);

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->view->saved();
            return $this->htmlRedirect(Url::toCustomGallery($this->contentContainer, $form->instance->id));
        }

        return $this->renderPartial('modal_gallery_edit', [
                    'galleryForm' => $form,
                    'contentContainer' => $this->contentContainer,
        ]);
    }

    /**
     * Action to delete an item.
     *
     * @return string the rendered view.
     * @throws HttpException
     */
    public function actionDelete($gid)
    {
        $this->forcePostRequest();

        $gallery = CustomGallery::findOne(['id' => $gid]);

        if(!$gallery) {
            throw new NotFoundHttpException();
        }

        if(!$gallery->content->canEdit()) {
            throw new ForbiddenHttpException();
        }

        if($gallery->delete()) {
            $this->view->success(Yii::t('GalleryModule.base', 'Deleted'));
        } else {
            $this->view->error(Yii::t('GalleryModule.base', 'Item could not be deleted!'));
        }

        return $this->htmlRedirect(Url::toGalleryOverview($this->contentContainer));
    }

    public function actionUpload()
    {
        $gallery = $this->getGallery();

        if(!$gallery->content->canEdit()) {
            throw new HttpException(404);
        }

        $errors = false;
        $files = [];
        foreach (UploadedFile::getInstancesByName('files') as $cFile) {
            $result = $this->handleMediaUpload($gallery, $cFile);
            $errors |= $result['error'];
            $files[] = $result;
        }

        return $this->asJson(['files' => $files]);
    }

    /**
     * Handles the file upload for a particular UploadedFile
     */
    protected function handleMediaUpload(BaseGallery $gallery, UploadedFile $cfile)
    {
        $media = new Media(['gallery' => $gallery]);
        $mediaUpload = $media->handleUpload($cfile);

        $result = FileHelper::getFileInfos($mediaUpload);
        $result['error'] = $mediaUpload->hasErrors();
        $result['errors'] = '';
        foreach ($mediaUpload->getErrors() as $error) {
            $result['errors'] .= implode(', ', $error);
        }

        return $result;
    }



    /**
     * @param null $openGalleryId
     * @return CustomGallery
     */
    protected function getOpenGallery($gid = null)
    {
        $id = $gid == null ? Yii::$app->request->get('gid') : $gid;

        if(!$id) {
            throw new HttpException(404);
        }

        return CustomGallery::findOne(['id' => $id]);
    }
}
