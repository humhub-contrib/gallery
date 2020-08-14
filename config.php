<?php

use humhub\modules\gallery\Events;
use \humhub\modules\space\widgets\Menu;
use humhub\modules\space\widgets\Sidebar as SpaceSidebar;
use \humhub\modules\user\widgets\ProfileMenu;
use \humhub\modules\user\widgets\ProfileSidebar;

return [
    'id' => 'gallery',
    'class' => 'humhub\modules\gallery\Module',
    'namespace' => 'humhub\modules\gallery',
    'events' => [
        ['class' => Menu::class,'event' => Menu::EVENT_INIT, 'callback' => [Events::class, 'onSpaceMenuInit']],
        ['class' => ProfileMenu::class,'event' => ProfileMenu::EVENT_INIT, 'callback' => [Events::class,'onProfileMenuInit']],
        ['class' => SpaceSidebar::class, 'event' => SpaceSidebar::EVENT_INIT, 'callback' => [Events::class, 'onSpaceSidebarInit']],
        ['class' => ProfileSidebar::class, 'event' => ProfileSidebar::EVENT_INIT, 'callback' => [Events::class, 'onProfileSidebarInit']],
    ]
];

