<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

return [
    'modules' => ['gallery'],
    'fixtures' => [
        'default',
        'task' => \humhub\modules\gallery\tests\codeception\fixtures\GalleryFixture::class
    ]
];



