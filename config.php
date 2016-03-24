<?php
use humhub\modules\space\widgets\Menu;
use humhub\modules\user\widgets\ProfileMenu;

return [
    'id' => 'gallery',
    'class' => 'humhub\modules\gallery\Module',
    'namespace' => 'humhub\modules\gallery',
    'events' => array(
        array(
            'class' => Menu::className(),
            'event' => Menu::EVENT_INIT,
            'callback' => array(
                'humhub\modules\gallery\Events',
                'onSpaceMenuInit'
            )
        ),
        array(
            'class' => ProfileMenu::className(),
            'event' => ProfileMenu::EVENT_INIT,
            'callback' => array(
                'humhub\modules\gallery\Events',
                'onProfileMenuInit'
            )
        )
    )
];
?>