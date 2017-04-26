<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\StreamGallery;
use \Yii;
use \yii\base\NotSupportedException;

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
     * Action to delete multiple files.
     * 
     * @return string the rendered view.
     */
    public function actionDeleteMultiple()
    {
        $this->canWrite(true);

        $confirmed = Yii::$app->request->get('confirm');
        $itemId = Yii::$app->request->get('item-id');
        $openGalleryId = Yii::$app->request->get('open-gallery-id');
        $selectedItems = Yii::$app->request->post('selected');

        if ($confirmed) {
            if (is_array($selectedItems)) {
                $notDeleted = [];
                foreach ($selectedItems as $itemId) {
                    if (!$this->deleteItem($itemId)) {
                        $notDeleted[] = $itemId;
                    }
                }
                if (!empty($notDeleted)) {
                    $this->view->error(Yii::t('GalleryModule.base', 'Some items could not be deleted. Ids :%ids%', [
                        '%ids%' => implode(', ', $notDeleted)
                    ]));
                }
                $this->view->success(Yii::t('GalleryModule.base', 'Deleted'));
                return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/custom-gallery/view', ['open-gallery-id' => $openGalleryId]));
            }
        } else {
            return $this->renderPartial('/shared/modal_delete', [
                        'openGalleryId' => $openGalleryId,
                        'selectedItems' => array_merge($selectedItems == null ? [] : $selectedItems, $itemId == null ? [] : [$itemId])
            ]);
        }
    }

    /**
     * Action to sort the galleries.
     *
     * @return string the rendered view.
     */
    public function actionSort()
    {
        throw new NotSupportedException("Not yet implemented.");
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
            'custom_galleries' => $this->getCustomGalleries()
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
