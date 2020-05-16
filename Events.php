<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery;

use humhub\modules\gallery\helpers\Url;
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
        try {
            if ($event->sender->space !== null && $event->sender->space->isModuleEnabled('gallery')) {
                $event->sender->addItem([
                    'label' => Yii::t('GalleryModule.base', 'Gallery'),
                    'group' => 'modules',
                    'url' => Url::toGalleryOverview($event->sender->space),
                    'icon' => '<i class="fa fa-picture-o"></i>',
                    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'gallery')
                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onProfileMenuInit($event)
    {
        try {
            if ($event->sender->user !== null && $event->sender->user->isModuleEnabled('gallery')) {
                $event->sender->addItem([
                    'label' => Yii::t('GalleryModule.base', 'Gallery'),
                    'url' => Url::toGalleryOverview($event->sender->user),
                    'icon' => '<i class="fa fa-picture-o"></i>',
                    'isActive' => (Yii::$app->controller->module && Yii::$app->controller->module->id == 'gallery')
                ]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }

    public static function onSpaceSidebarInit($event)
    {
        try {
            if ($event->sender->space !== null && $event->sender->space->isModuleEnabled('gallery')) {
                $event->sender->addWidget(GallerySnippet::class, ['contentContainer' => $event->sender->space], ['sortOrder' => 0]);
            }
        } catch (\Throwable $e) {
            Yii::error($e);
        }
    }
}
