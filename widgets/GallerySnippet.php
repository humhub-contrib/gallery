<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2017 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 *
 */

/**
 * Created by PhpStorm.
 * User: buddha
 * Date: 25.05.2017
 * Time: 20:21
 */

namespace humhub\modules\gallery\widgets;


use humhub\modules\gallery\permissions\WriteAccess;
use Yii;
use humhub\components\Widget;
use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\gallery\models\forms\ContainerSettings;
use humhub\modules\space\models\Space;

class GallerySnippet extends Widget
{
    /**
     * @var ContentContainerActiveRecord
     */
    public $contentContainer;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $settings = new ContainerSettings(['contentContainer' => $this->contentContainer]);

        if($settings->hideSnippet) {
            return;
        }

        $gallery = $settings->getSnippetGallery();

        if(!$gallery) {
            return;
        }

        $images = $gallery->getMediaList(Yii::$app->getModule('gallery')->snippetMaxImages);

        if(!count($images)) {
            return;
        }

        return $this->render('gallerySnippet', [
            'images' => $images,
            'settingsUrl' => $this->contentContainer->createUrl('/gallery/setting'),
            'galleryUrl' => $gallery->getUrl(),
            'isAdmin' => $this->contentContainer instanceof Space ? $this->contentContainer->isAdmin() : $this->contentContainer->isCurrentUser(),
            'canWrite' => $this->contentContainer->permissionManager->can(new WriteAccess())
        ]);
    }
}