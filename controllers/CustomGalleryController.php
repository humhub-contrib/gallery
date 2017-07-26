<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\content\models\Content;
use humhub\modules\file\libs\FileHelper;
use humhub\modules\gallery\models\BaseGallery;
use \humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\forms\GalleryEditForm;
use \humhub\modules\gallery\models\Media;
use \Yii;
use \yii\web\HttpException;
use \yii\web\UploadedFile;

/**
 * Description of a Custom Gallery Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class CustomGalleryController extends ListController
{

    /**
     * @return redirect to /view.
     */
    public function actionIndex()
    {
        return $this->redirect('/gallery/custom-gallery/view');
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
     * Action to edit a gallery.
     * @url-param 'itemId' the gallery's id.
     * @url-param 'openGalleryId' id of the open gallery. Used for redirecting.
     *
     * @param null $itemId
     * @param int $visibility
     * @param null $instance
     * @return string if insufficient permission.
     */
    public function actionEdit($itemId = null, $openGalleryId = null, $fromWall = false, $visibility = Content::VISIBILITY_PRIVATE)
    {
        $this->canWrite(true);

        if($itemId) {
            $instance = $this->module->getItemById($itemId);
        } else {
            $instance = null;
        }

        $gallery = new GalleryEditForm(['contentContainer' => $this->contentContainer, 'instance' => $instance]);

        if ($gallery->load(Yii::$app->request->post()) && $gallery->save()) {
            $this->view->saved();
            return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/custom-gallery/view', ['openGalleryId' => $gallery->instance->id]));
        }

        return $this->renderPartial('modal_gallery_edit', [
                    'galleryForm' => $gallery,
                    'contentContainer' => $this->contentContainer,
        ]);
    }

    /**
     * Action to upload multiple files.
     * @url-param 'openGalleryId' id of the open gallery the files should be stored in.
     *
     * @throws HttpException if insufficient permission.
     * @return multitype:string
     */
    public function actionUpload()
    {
        $parentGallery = $this->getOpenGallery();

        if(!$parentGallery->content->canEdit()) {
            throw new HttpException(404);
        }

        $errors = false;
        $files = [];
        foreach (UploadedFile::getInstancesByName('files') as $cFile) {
            $result = $this->handleMediaUpload($parentGallery, $cFile);
            $errors = $errors | $result['error'];
            $files[] = $result;
        }

        return $this->asJson(['files' => $files]);
    }

    /**
     * Handles the file upload for a particular UploadedFile
     */
    protected function handleMediaUpload(BaseGallery $parentGallery, UploadedFile $cfile)
    {
        $media = new Media(['gallery' => $parentGallery]);
        $mediaUpload = $media->handleUpload($cfile);

        $result = FileHelper::getFileInfos($mediaUpload);
        $result['error'] = $mediaUpload->hasErrors();
        $result['errors'] = '';
        foreach ($mediaUpload->getErrors() as $error) {
            $result['errors'] .= $result['name'] . ' - ' . implode(', ', $error) . '\n';
        }

        return $result;
    }

    /**
     * Render a specified custom gallery or the gallery list.
     * @url-param 'openGalleryId' id of the open gallery. The gallery list is rendered if no gallery with this id is found.
     *
     * @param string $ajax render as ajax. default: false
     * @param string $openGalleryId the custom gallery to render.
     */
    protected function renderGallery($ajax = false, $openGalleryId = null)
    {
        $gallery = $this->getOpenGallery($openGalleryId);

        if ($gallery) {
            if(!$gallery->content->canView()) {
                throw new HttpException(404);
            }

            return $ajax ? $this->renderPartial("gallery_view", ['gallery' => $gallery])
                : $this->render("gallery_view", ['gallery' => $gallery]);
        } else {
            return parent::renderGallery($ajax);
        }
    }

    /**
     * @param null $openGalleryId
     * @return CustomGallery
     */
    protected function getOpenGallery($openGalleryId = null)
    {
        $id = $openGalleryId == null ? Yii::$app->request->get('openGalleryId') : $openGalleryId;

        if(!$id) {
            throw new HttpException(404);
        }

        return CustomGallery::findOne(['id' => $id]);
    }

}
