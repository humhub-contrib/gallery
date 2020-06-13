<?php

use \humhub\modules\space\widgets\Menu;
use humhub\modules\space\widgets\Sidebar;
use humhub\widgets\BaseMenu;
use \humhub\modules\user\widgets\ProfileMenu;

return [
    'id' => 'gallery',
    'class' => 'humhub\modules\gallery\Module',
    'namespace' => 'humhub\modules\gallery',
    'events' => [
        ['class' => Menu::className(),'event' => Menu::EVENT_INIT, 'callback' => ['humhub\modules\gallery\Events', 'onSpaceMenuInit']],
        ['class' => ProfileMenu::className(),'event' => ProfileMenu::EVENT_INIT, 'callback' => ['humhub\modules\gallery\Events','onProfileMenuInit']],
        ['class' => Sidebar::className(),'event' =>  BaseMenu::EVENT_INIT, 'callback' => ['humhub\modules\gallery\Events','onSpaceSidebarInit']]
    ]
];
?>