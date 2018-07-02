<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery;

use humhub\modules\gallery\widgets\GallerySnippet;
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
        if ($event->sender->space !== null && $event->sender->space->isModuleEnabled('gallery')) {
            $event->sender->addItem([
                'label' => Yii::t('GalleryModule.base', 'Gallery'),
                'group' => 'modules',
                'url' => $event->sender->space->createUrl('/gallery/list'),
                'icon' => '<i class="fa fa-picture-o"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'gallery')
            ]);
        }
    }

    public static function onProfileMenuInit($event)
    {
        if ($event->sender->user !== null && $event->sender->user->isModuleEnabled('gallery')) {
            $event->sender->addItem([
                'label' => Yii::t('GalleryModule.base', 'Gallery'),
                'url' => $event->sender->user->createUrl('/gallery/list'),
                'icon' => '<i class="fa fa-picture-o"></i>',
                'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'gallery')
            ]);
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        if ($event->sender->space !== null && $event->sender->space->isModuleEnabled('gallery')) {
            $event->sender->addWidget(GallerySnippet::class, ['contentContainer' => $event->sender->space], ['sortOrder' => 0]);
        }
    }
}
