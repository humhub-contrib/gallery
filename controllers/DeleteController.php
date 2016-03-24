<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use yii\web\HttpException;
use humhub\modules\gallery\models\Gallery;

/**
 * Description of DeleteController for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class DeleteController extends BrowseController
{

    /**
     * Action to delete one or multiple items identified by its type and id: &lt;type&gt;_&lt;id&gt;.
     * @url-param 'item-id' the items id.
     * @url-param 'selected' array of item ids.
     * @url-param 'confirm' true: delete, false: render confirm modal.
     * @url-param 'open-gallery-id' id of the open gallery. Used for redirecting.
     *
     * @throws HttpException if insufficient permission | if the specified item could not be found.
     */
    public function actionIndex()
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
                    $item = $this->module->getItemById($itemId);
                    if (! $this->deleteItem($itemId)) {
                        $notDeleted[] = $itemId;
                    }
                }
                if (! empty($notDeleted) && $this->module->debug) {
                    throw new HttpException(400, Yii::t('GalleryModule.base', 'Following items could not be deleted: (%ids%).', [
                        '%ids%' => implode(', ', $notDeleted)
                    ]));
                }
                return $this->renderGallery(true);
            }
        } else {
            return $this->renderAjax('modal_delete', [
                'openGalleryId' => $openGalleryId,
                'selectedItems' => array_merge($selectedItems == null ? [] : $selectedItems, $itemId == null ? [] : [
                    $itemId
                ])
            ]);
        }
    }

    /**
     * Delete an item identified by its type and id: &lt;type&gt;_&lt;id&gt;.
     * Also deletes all subcontent.
     *
     * @param string $id
     *            &lt;type&gt;_&lt;id&gt;.
     */
    protected function deleteItem($itemId)
    {
        $item = $this->module->getItemById($itemId);
        
        if ($item instanceof Gallery) {
            $subitems = $item->media;
            foreach ($subitems as $media) {
                $media->delete();
            }
            return $item->delete();
        } elseif ($item instanceof Media) {
            return $item->delete();
        }
        
        return false;
    }
}
