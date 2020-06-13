<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\assets;

use humhub\modules\gallery\models\Media;
use humhub\modules\ui\view\components\View;
use Yii;
use yii\web\AssetBundle;

/**
 * The asset bundle for the gallery module.
 *
 * @package humhub.modules.gallery.assets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class Assets extends AssetBundle
{

    /**
     * v1.5 compatibility defer script loading
     *
     * Migrate to HumHub AssetBundle once minVersion is >=1.5
     *
     * @var bool
     */
    public $defer = true;

    public $publishOptions = [
        'forceCopy' => false
    ];

    public $css = [
        'css/gallery.css',
    ];

    public $js = [
        'js/gallery.js',
    ];
    
    public $jsOptions = [
        'position' => \yii\web\View::POS_END
    ];

    /**
     * @inheritdoc
     */
    public $sourcePath = '@gallery/resources';

    /**
     * @param View $view
     * @return AssetBundle
     */
    public static function register($view)
    {
        $view->registerJsConfig('gallery', [
            'fallbackImageUrl' => Media::getFallbackPreviewImageUrl(),
            'text' => [
                'error.loadImageError' => Yii::t('GalleryModule.base', 'Image could not be loaded')
            ]
        ]);

        return parent::register($view);
    }
}
