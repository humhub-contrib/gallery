<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\widgets;

use \humhub\modules\gallery\Module;
use \yii\base\Widget;
use \Yii;

/**
 * Widget that renders the gallery content.
 *
 * @package humhub.modules.gallery.widgets
 * @since 1.0
 * @author Sebastian Stumpf
 */
class MediaListEntry extends Widget
{

    public $entryObject;
    public $parentGallery;
    
    public function run()
    {
        $contentContainer = Yii::$app->controller->contentContainer;
        if($this->entryObject instanceof \humhub\modules\gallery\models\Media) {
            $creator = Module::getUserById($this->entryObject->baseFile->created_by);
            $contentObject = $this->entryObject;
            
            $entryTitle = $this->entryObject->title;
            $entryDescription = $this->entryObject->description;
            
            $wallUrl = $this->entryObject->getWallUrl();
            $deleteUrl = $contentContainer->createUrl('/gallery/custom-gallery/delete-multiple', ['open-gallery-id' => $this->parentGallery->id, 'item-id' => $this->entryObject->getItemId()]);
            $editUrl = $contentContainer->createUrl('/gallery/media/edit', ['open-gallery-id' => $this->parentGallery->id, 'item-id' => $this->entryObject->getItemId()]);
            $downloadUrl = $this->entryObject->getUrl(true);
            $fileUrl = $this->entryObject->getUrl();            
            $thumbnailUrl = $this->entryObject->getSquareThumbnailUrl();
            
            $writeAccess = Yii::$app->controller->canWrite(false);
            
        } elseif ($this->entryObject instanceof \humhub\modules\file\models\File) {
            $creator = Module::getUserById($this->entryObject->created_by);
            $contentObject = \humhub\modules\gallery\libs\FileUtils::getBaseObject($this->entryObject);
            $baseContent = \humhub\modules\gallery\libs\FileUtils::getBaseContent($this->entryObject);
                        
            $entryTitle = $this->entryObject->file_name;
            $entryDescription = $contentObject->message;
            
            $wallUrl = $baseContent->getUrl();
            $deleteUrl = '';
            $editUrl = '';
            $downloadUrl = $this->entryObject->getUrl(['download' => true]);
            $fileUrl = $this->entryObject->getUrl();            
            $thumbnailUrl = \humhub\modules\gallery\libs\FileUtils::getSquareThumbnailUrlFromFile($this->entryObject);
            
            $writeAccess = false;
        }
        $uiGalleryId = "GalleryModule-Gallery-".$this->parentGallery->id;
        
        return $this->render('mediaListEntry', [
                    'creatorUrl' => $creator->createUrl(),
                    'creatorThumbnailUrl' => $creator->getProfileImage()->getUrl(),
                    'creatorName' => $creator->getDisplayName(),
                    'entryTitle' => $entryTitle,
                    'entryDescription' => $entryDescription,
                    'wallUrl' => $wallUrl,
                    'deleteUrl' => $deleteUrl,
                    'editUrl' => $editUrl,
                    'downloadUrl' => $downloadUrl,
                    'fileUrl' => $fileUrl,
                    'thumbnailUrl' => $thumbnailUrl,
                    'contentContainer' => $contentContainer,
                    'writeAccess' => $writeAccess,
                    'uiGalleryId' => $uiGalleryId,
                    'contentObject' => $contentObject,
        ]);
    }

}
