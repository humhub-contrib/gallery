<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */
namespace humhub\modules\gallery\controllers;

use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\user\models\User;
use humhub\modules\gallery\permissions\WriteAccess;
use yii\web\HttpException;

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
        $permission = false;
        // check if user is on his own profile
        if ($this->contentContainer instanceof User) {
            if ($this->contentContainer->id === Yii::$app->user->getIdentity()->id) {
                $permission = true;
            }
        } else {
            $permission = $this->contentContainer->permissionManager->can(new WriteAccess());
        }
        
        if(!$permission) {
            if($throw) {
                throw new HttpException(401, Yii::t('GalleryModule.base', 'Insufficient rights to execute this action.'));
            }
            return false;
        }
        return true;
    }
    
    /**
     * Get a user by id.
     * 
     * @param integer $id
     * @return User the user or null.
     */
    public function getUserById($id)
    {
        return User::findOne([
            'id' => $id
            ]);
    }
}
