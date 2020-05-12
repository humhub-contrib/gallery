<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

namespace humhub\modules\gallery\tests\codeception\fixtures;

use humhub\modules\gallery\models\Media;
use yii\test\ActiveFixture;

class MediaFixture extends ActiveFixture
{
    public $modelClass = Media::class;
    public $dataFile = '@gallery/tests/codeception/fixtures/data/media.php';
}
