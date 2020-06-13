<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\gallery\tests\codeception\fixtures;

use humhub\modules\gallery\models\BaseGallery;
use yii\test\ActiveFixture;

class GalleryFixture extends ActiveFixture
{
    public $modelClass = BaseGallery::class;
    public $dataFile = '@gallery/tests/codeception/fixtures/data/gallery.php';

    public $depends = [
        MediaFixture::class
    ];
}
