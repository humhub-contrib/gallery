<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2021 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\assets;

use yii\web\AssetBundle;

/**
 * The asset bundle for the gallery wall entry.
 */
class WallEntryAssets extends AssetBundle
{

    /**
     * @inheritdoc
     */
    public $sourcePath = '@gallery/resources';

    public $css = [
        'css/wallentry.css',
    ];
}
