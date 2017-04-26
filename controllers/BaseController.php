<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\content\components\ContentContainerController;
use \humhub\modules\gallery\Module;
use \humhub\modules\user\models\User;
use \Yii;
use \yii\base\Model;
use \yii\web\HttpException;

/**
 * Description of a Base Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
abstract class BaseController extends ContentContainerController
{

    /**
     * Checks if user can write
     *
     * @param $throw boolean
     *            default true throws exception if permission failure.
     * @return boolean current user has write acces.
     */
    public function canWrite($throw = true)
    {
        $permission = Module::canWrite($this->contentContainer);

        if ($permission) {
            return true;
        } elseif ($throw) {
            throw new HttpException(401, Yii::t('GalleryModule.base', 'Insufficient rights to execute this action.'));
        }
        return false;
    }

    /**
     * Get a user by id.
     *
     * @param integer $id            
     * @return User the user or null.
     */
    protected function getUserById($id)
    {
        return User::findOne(['id' => $id]);
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
        if ($item instanceof Model) {
            return $item->delete();
        }

        return false;
    }

    /**
     * Get the currently open gallery.
     * @url-param 'open-gallery-id' id of the open gallery.
     *
     * @param int $openGalleryId
     *            If specified the id from the url-param is ignored.
     *            
     * @return null | models\Gallery
     */
    abstract protected function getOpenGallery($openGalleryId = null);

    /**
     * Render a specified gallery or the gallery list.
     * @url-param 'open-gallery-id' id of the open gallery. The gallery list is rendered if no gallery with this id is found.
     *
     * @param string $ajax
     *            render as ajax. default: false
     * @param string $openGalleryId
     *            the custom gallery to render.
     */
    abstract protected function renderGallery($ajax = false, $openGalleryId = null);
}
