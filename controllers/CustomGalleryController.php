<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\content\models\Content;
use \humhub\modules\file\models\File;
use \humhub\modules\file\models\FileUpload;
use \humhub\modules\gallery\libs\FileUtils;
use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\Media;
use \humhub\modules\gallery\widgets\CustomGalleryContent;
use \Yii;
use \yii\base\NotSupportedException;
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
     *
     * @return redirect to /view.
     */
    public function actionIndex()
    {
        return $this->redirect('/gallery/custom-gallery/view');
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
     * Action to sort the media files.
     *
     * @return string the rendered view.
     */
    public function actionSort()
    {
        throw new NotSupportedException("Not yet implemented.");
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

        $fromWall = Yii::$app->request->get('fromWall');
        $itemId = Yii::$app->request->get('item-id');
        $openGalleryId = Yii::$app->request->get('open-gallery-id');
        $visibility = Yii::$app->request->get('visibility');
        // default visibility is private
        $visibility = $visibility !== Content::VISIBILITY_PUBLIC ? Content::VISIBILITY_PRIVATE : Content::VISIBILITY_PUBLIC;
        $cancel = Yii::$app->request->get('cancel');
        // check if a gallery with the given id exists.
        $gallery = $this->module->getItemById($itemId);

        if ($fromWall && $cancel) {
            return $this->renderAjaxContent($gallery->getWallOut());
        }

        // if no gallery is found with the given id, a new one has to be created
        if (!($gallery instanceof CustomGallery)) {
            // can't create galleries from wall!!!
            if ($fromWall) {
                throw new HttpException(401, Yii::t('GalleryModule.base', 'Cannot edit non existing Gallery.'));
            }
            // create a new gallery
            $gallery = new CustomGallery();
            $gallery->type = CustomGallery::TYPE_CUSTOM_GALLERY;
            $gallery->content->container = $this->contentContainer;
        }

        $gallery_form_data = Yii::$app->request->post('CustomGallery');
        $content_form_data = Yii::$app->request->post('Content');
        // format visibility
        $content_form_data['visibility'] = $content_form_data['visibility'] != Content::VISIBILITY_PUBLIC ? Content::VISIBILITY_PRIVATE : Content::VISIBILITY_PUBLIC;

        if ($gallery_form_data !== null && $gallery->load(Yii::$app->request->post()) && $gallery->validate()) {
            $gallery->content->visibility = $content_form_data['visibility'];
            $gallery->save();
            if ($fromWall) {
                return $this->renderAjaxContent($gallery->getWallOut([
                                    'justEdited' => true
                ]));
            } else {
                return $this->renderGallery(true);
            }
        }

        // render modal
        return $this->renderAjax($fromWall ? '/custom-gallery/wall_gallery_edit' : '/custom-gallery/modal_gallery_edit', [
                    'openGalleryId' => $openGalleryId,
                    'gallery' => $gallery,
                    'contentContainer' => $this->contentContainer,
                    'fromWall' => $fromWall
        ]);
    }

    /**
     * Action to upload multiple files.
     * @url-param 'open-gallery-id' id of the open gallery the files should be stored in.
     *
     * @throws HttpException if insufficient permission.
     * @return multitype:string
     */
    public function actionUpload()
    {
        Yii::$app->response->format = 'json';

        $this->canWrite(true);

        $response = [];
        $response['errors'] = [];

        $parentGallery = $this->getOpenGallery();
        if ($parentGallery == null) {
            $response['errors'][] = Yii::t('GalleryModule.base', 'No valid gallery specified for the uploaded files.');
            return $response;
        }

        foreach (UploadedFile::getInstancesByName('files') as $uploadedFile) {
            if (!$this->isValidExtension($uploadedFile->extension)) {
                $response['errors'][] = Yii::t('GalleryModule.base', 'Filetype of %filename% is not supported.', [
                            '%filename%' => $uploadedFile->name
                ]);
                continue;
            }

            //@deprecated: v1.1 compatibility
            if (version_compare(Yii::$app->version, '1.2', '<')) {

                $media = new Media();
                $baseFile = new File();
                $baseFile->setUploadedFile($uploadedFile);
                if ($baseFile->validate()) {
                    $media->title = FileUtils::sanitizeFilename($uploadedFile->name);
                    $media->content->container = $this->contentContainer;
                    $media->gallery_id = $parentGallery->id;
                    if ($media->validate()) {
                        $media->save();
                        $baseFile->object_model = $media->className();
                        $baseFile->object_id = $media->id;
                        $baseFile->save();
                    }
                }
            } else {
                $media = new Media();
                $baseFile = new FileUpload;
                $baseFile->setUploadedFile($uploadedFile);
                if ($baseFile->validate()) {
                    $media->title = $baseFile->file_name;
                    $media->content->container = $this->contentContainer;
                    $media->gallery_id = $parentGallery->id;
                    if ($media->validate()) {
                        $media->save();
                        $baseFile->object_model = $media->className();
                        $baseFile->object_id = $media->id;
                        $baseFile->save();
                    }
                }
            }
            $response['errors'] = $this->extractAndCombineErrors($response['errors'], [
                $media,
                $baseFile
                    ], $uploadedFile->name, false);
        }

        // render and add gallery content to the response
        $response['galleryHtml'] = CustomGalleryContent::widget([
                    'gallery' => $parentGallery,
        ]);
        return $response;
    }

    /**
     * Render a specified custom gallery or the gallery list.
     * @url-param 'open-gallery-id' id of the open gallery. The gallery list is rendered if no gallery with this id is found.
     *
     * @param string $ajax
     *            render as ajax. default: false
     * @param string $openGalleryId
     *            the custom gallery to render.
     */
    protected function renderGallery($ajax = false, $openGalleryId = null)
    {
        $gallery = $this->getOpenGallery($openGalleryId);
        if ($gallery != null) {
            return $ajax ? $this->renderAjax("/custom-gallery/gallery_view", [
                        'gallery' => $gallery
                    ]) : $this->render("/custom-gallery/gallery_view", [
                        'gallery' => $gallery
            ]);
        } else {
            return parent::renderGallery($ajax);
        }
    }

    protected function getOpenGallery($openGalleryId = null)
    {
        $id = $openGalleryId == null ? Yii::$app->request->get('open-gallery-id') : $openGalleryId;
        return CustomGallery::findOne([
                    'id' => $id,
                    'type' => CustomGallery::TYPE_CUSTOM_GALLERY
        ]);
    }

}
