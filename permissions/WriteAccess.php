<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\permissions;

use humhub\modules\user\models\User;
use Yii;
use \humhub\libs\BasePermission;
use \humhub\modules\space\models\Space;

/**
 * WriteAccess Permission
 *
 * @package humhub.modules.gallery.permissions
 * @since 1.0
 * @author Sebastian Stumpf
 */
class WriteAccess extends BasePermission
{

    /**
     * @inheritdoc
     */
    public $defaultAllowedGroups = [
        User::USERGROUP_SELF,
        Space::USERGROUP_OWNER,
        Space::USERGROUP_ADMIN,
        Space::USERGROUP_MODERATOR,
        Space::USERGROUP_MEMBER
    ];

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        User::USERGROUP_SELF,
        User::USERGROUP_GUEST,
        User::USERGROUP_USER,
        Space::USERGROUP_USER,
        Space::USERGROUP_GUEST
    ];

    public function getTitle()
    {
        return Yii::t('GalleryModule.base', 'Gallery write access');
    }

    public function getDescription()
    {
        return Yii::t('GalleryModule.base', 'Allows the user to add, modify images and galleries.');
    }

    /**
     * @inheritdoc
     */
    protected $moduleId = 'gallery';

}
