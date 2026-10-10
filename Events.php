<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery;

use humhub\helpers\ControllerHelper;
use humhub\modules\gallery\helpers\Url;
use humhub\modules\gallery\models\forms\ContainerSettings;
use humhub\modules\gallery\widgets\GallerySnippet;
use humhub\modules\space\widgets\Menu;
use humhub\modules\space\widgets\Sidebar as SpaceSidebar;
use humhub\modules\ui\menu\MenuLink;
use humhub\modules\user\widgets\ProfileMenu;
use humhub\modules\user\widgets\ProfileSidebar;
use Yii;

/**
 * The event handler for the gallery module.
 *
 * @package humhub.modules.gallery
 * @since 1.0
 * @author Sebastian Stumpf
 */
class Events
{
    public static function onSpaceMenuInit($event)
    {
        try {
            /* @var Menu $menu */
            $menu = $event->sender;

            if ($menu->space !== null && $menu->space->moduleManager->isEnabled('gallery')) {
                $menu->addEntry(new MenuLink([
                    'label' => Yii::t('GalleryModule.base', 'Gallery'),
                    'url' => Url::toGalleryOverview($menu->space),
                    'icon' => 'picture-o',
                    'isActive' => ControllerHelper::isActivePath('gallery'),
                ]));
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        try {
            /* @var Module $module */
            $module = Yii::$app->getModule('gallery');

            /* @var SpaceSidebar $sidebar */
            $sidebar = $event->sender;

            if ($sidebar->space !== null && $sidebar->space->moduleManager->isEnabled('gallery')) {
                $sidebar->addWidget(
                    GallerySnippet::class,
                    ['contentContainer' => $event->sender->space],
                    ['sortOrder' => (int) $module->settings
                        ->contentContainer($sidebar->space)
                        ->get(ContainerSettings::SETTING_SORT_ORDER)],
                );
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onProfileMenuInit($event)
    {
        try {
            /* @var $menu ProfileMenu */
            $menu = $event->sender;

            if ($menu->user !== null && $menu->user->moduleManager->isEnabled('gallery')) {
                $menu->addEntry(new MenuLink([
                    'label' => Yii::t('GalleryModule.base', 'Gallery'),
                    'url' => Url::toGalleryOverview($menu->user),
                    'icon' => 'picture-o',
                    'isActive' => ControllerHelper::isActivePath('gallery'),
                ]));
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onProfileSidebarInit($event)
    {
        try {
            /* @var Module $module */
            $module = Yii::$app->getModule('gallery');

            /* @var ProfileSidebar $sidebar */
            $sidebar = $event->sender;

            if ($sidebar->user !== null && $sidebar->user->moduleManager->isEnabled('gallery')) {
                $sidebar->addWidget(
                    GallerySnippet::class,
                    ['contentContainer' => $sidebar->user],
                    ['sortOrder' => (int) $module->settings
                        ->contentContainer($sidebar->user)
                        ->get(ContainerSettings::SETTING_SORT_ORDER)],
                );
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }
}
