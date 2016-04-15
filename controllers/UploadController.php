<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use yii\web\UploadedFile;
use humhub\modules\gallery\models\Media;
use humhub\modules\file\models\File;
use humhub\modules\gallery\libs\FileUtils;
use humhub\modules\gallery\widgets\GalleryContent;

/**
 * Description of UploadController for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class UploadController extends BrowseController
{

    /**
     * The supported extensions
     */
    public static $validExtensions = [
        'jpg',
        'gif',
        'bmp',
        'svg',
        'tiff',
        'png',
        'mp4',
        'mpeg',
        'swf'
    ];

    /**
     * Action to upload multiple files.
     * @url-param 'open-gallery-id' id of the open gallery the files should be stored in.
     *
     * @throws HttpException if insufficient permission.
     * @return multitype:string
     */
    public function actionIndex()
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
            if (! $this->isValidExtension($uploadedFile->extension)) {
                $response['errors'][] = Yii::t('GalleryModule.base', 'Filetype of %filename% is not supported.', [
                    '%filename%' => $uploadedFile->name
                ]);
                continue;
            }
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
            $response['errors'] = $this->extractAndCombineErrors($response['errors'], [
                $media,
                $baseFile
            ], $uploadedFile->name, false);
        }
        
        // render and add gallery content to the response
        $response['galleryHtml'] = GalleryContent::widget(['gallery' => $parentGallery, 'context' => $this]);        
        return $response;
    }

    /**
     * Check if the given extension is supported.
     *
     * @param string $ext
     *            the extension.
     * @return boolean true if supported.
     */
    public function isValidExtension($ext)
    {
        return in_array($ext, self::$validExtensions);
    }

    /**
     * Combines and merges given errormessages with the errors from a model.
     *
     * @param array $baseErrors
     *            the errors that should be merged with the model errors.
     * @param array&lt;Model&gt; $models
     *            the models the errors will be extracted from.
     * @param string $prefix
     *            appended at the start of the model error (e.g. model-name).
     * @param boolean $useKey
     *            also append the key from the model errors specifying the attribute name. Default true.
     * @return array&lt;string&gt; the merged errors
     */
    public function extractAndCombineErrors($baseErrors, $models, $prefix = '', $useKey = true)
    {
        $errors = $baseErrors;
        foreach ($models as $model) {
            $modelErrors = array_map(function ($value, $key) use($prefix, $useKey)
            {
                return $prefix . ($useKey ? $key : '') . ': ' . $value[0];
            }, array_values($model->getErrors()), array_keys($model->getErrors()));
            $errors = array_merge($errors, $modelErrors);
        }
        return $errors;
    }
}
