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
 * Date: 04.06.2017
 * Time: 16:12
 */

namespace humhub\modules\gallery\models\forms;


use humhub\modules\content\components\ContentContainerActiveRecord;
use humhub\modules\content\models\Content;
use humhub\modules\gallery\models\CustomGallery;
use humhub\modules\space\models\Space;
use yii\base\Model;
use yii\web\HttpException;

/**
 * Class GalleryEditForm used to load/save gallery edit form data.
 *
 * @package humhub\modules\gallery\models\forms
 */
class GalleryEditForm extends Model
{
    /**
     * @var CustomGallery gallery instance
     */
    public $instance;

    /**
     * @var int content visibility
     */
    public $visibility;

    /**
     * @var ContentContainerActiveRecord
     */
    public $contentContainer;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if(!$this->instance) {
            $this->visibility = $this->getDefaultVisibility();
            $this->instance = new CustomGallery();
            $this->instance->content->container = $this->contentContainer;
            $this->instance->content->visibility =  $this->visibility;
        } else if(!($this->instance instanceof CustomGallery)) {
            throw new HttpException(404);
        } else if($this->instance->content->container->id != $this->contentContainer->id) {
            throw new HttpException(404);
        } else if(!$this->instance->content->canEdit()) {
            throw new HttpException(403);
        } else {
            $this->visibility = $this->instance->content->visibility;
        }
    }

    /**
     * @return int the default visibility of the given content container
     */
    private function getDefaultVisibility()
    {
        if($this->contentContainer instanceof Space) {
            return $this->contentContainer->getDefaultContentVisibility();
        } else {
            return Content::VISIBILITY_PUBLIC;
        }
    }

    /**
     * @inheritdoc
     */
    public function load($data, $formName = null)
    {
        parent::load($data, $formName);
        return $this->instance->load($data, $formName);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['visibility', 'integer', 'min' => Content::VISIBILITY_PRIVATE, 'max' => Content::VISIBILITY_PUBLIC];
        return $rules;
    }

    /**
     * Saves the gallery data and updates it's visibility settings.
     * @return bool
     * @throws \Throwable
     */
    public function save()
    {
        if(!$this->validate()) {
            return false;
        }

        return CustomGallery::getDb()->transaction(function() {
            $this->updateVisibility();
            return $this->instance->save();
        });
    }

    /**
     * Updates the content visibility and furthermore updates all related media content.
     * @param $insert
     */
    protected function updateVisibility()
    {
        if($this->visibility === null) {
            return;
        }

        if ($this->instance->content->visibility != $this->visibility) {
            $this->instance->content->visibility = $this->visibility;

            $contentIds = [];
            foreach($this->instance->mediaList as $media) {
                $contentIds[] = $media->content->id;
            }

            Content::updateAll(['visibility' => $this->visibility], ['in', 'id', $contentIds]);
        }
    }
}