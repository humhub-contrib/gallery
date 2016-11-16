<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use humhub\modules\gallery\models\Media;
use yii\web\HttpException;

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
        $cancel = Yii::$app->request->get('cancel');
        // check if a media file with the given id exists.
        $media = $this->module->getItemById($itemId);        
        
        if ($fromWall && $cancel) {
            return $this->renderAjaxContent($media->getWallOut());
        }
        
        // if not return cause this should not happen
        if (empty($media) || ! ($media instanceof Media)) {
            throw new HttpException(401, Yii::t('GalleryModule.base', 'Cannot edit non existing Media.'));
        }
        
        $data = Yii::$app->request->post('Media');
        
        if ($data !== null && $media->load(Yii::$app->request->post()) && $media->validate()) {
            $media->save();
            if ($fromWall) {
                return $this->renderAjaxContent($media->getWallOut([
                    'justEdited' => true
                    ]));
            } else {
                return $this->renderGallery(true);
            }
        }
        
        // render modal
        return $this->renderAjax($fromWall ? '/media/wall_media_edit' : '/media/modal_media_edit', [
            'openGalleryId' => $openGalleryId,
            'media' => $media,
            'contentContainer' => $this->contentContainer,
            'fromWall' => $fromWall
        ]);
    }
}