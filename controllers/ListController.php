<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\StreamGallery;
use humhub\widgets\ModalClose;
use \Yii;
use \yii\base\NotSupportedException;
use yii\web\HttpException;

/**
 * Description of ListController for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 *        
 */
class ListController extends BaseController
{

    /**
     * Action to view the gallery list.
     *
     * @return string the rendered view.
     */
    public function actionIndex()
    {
        return $this->renderGallery();
    }

    /**
     * Action to delete an item.
     * 
     * @return string the rendered view.
     */
    public function actionDeleteMultiple($itemId, $openGalleryId = null)
    {
        $this->forcePostRequest();
        $this->canWrite(true);

        $item = $this->module->getItemById($itemId);

        if($item instanceof ContentActiveRecord && !$item->content->canEdit()) {
            throw new HttpException(403);
        }

        if($item->delete()) {
            $this->view->success(Yii::t('GalleryModule.base', 'Deleted'));
        } else {
            $this->view->error(Yii::t('GalleryModule.base', 'Item could not be deleted!'));
        }

        if($item instanceof CustomGallery) {
            return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/list'));
        }

        return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/custom-gallery/view', ['openGalleryId' => $openGalleryId]));
    }

    /**
     * Renders the the gallery list.
     *
     * @param string $ajax
     *            render as ajax. default: false
     * @param string $openGalleryId
     *            the gallery to render, if null the gallery list will be rendered.
     */
    protected function renderGallery($ajax = false, $openGalleryId = null)
    {
        $params = [
            'stream_galleries' => $this->getStreamGalleries(),
            'custom_galleries' => $this->getCustomGalleries(),
            'canWrite' => $this->module->canWrite($this->contentContainer)
        ];
        return $ajax ? $this->renderPartial("/list/gallery_list", $params) : $this->render("/list/gallery_list", $params);
    }

    /**
     * Get this content container's stream galleries.
     *
     * @return null | array&lt;models\StreamGallery&gt;
     */
    protected function getStreamGalleries()
    {
        $query = StreamGallery::find()->contentContainer($this->contentContainer)->readable();
        return $query->all();
    }

    /**
     * Get this content container's custom galleries.
     *
     * @return null | array&lt;models\CustomGallery&gt;
     */
    protected function getCustomGalleries()
    {
        $query = CustomGallery::find()->contentContainer($this->contentContainer)->readable();
        return $query->all();
    }

    protected function getOpenGallery($openGalleryId = null)
    {
        // no gallery is open at the list level
        return null;
    }

}
