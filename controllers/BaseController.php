<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use humhub\modules\content\components\ActiveQueryContent;
use humhub\modules\content\components\ContentActiveRecord;
use \humhub\modules\content\components\ContentContainerController;
use humhub\modules\gallery\models\BaseGallery;
use humhub\modules\gallery\models\Media;
use \humhub\modules\gallery\Module;
use humhub\modules\gallery\widgets\GalleryList;
use humhub\modules\space\models\Space;
use \humhub\modules\user\models\User;
use \Yii;
use yii\base\Exception;
use \yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\BaseDataProvider;
use \yii\web\HttpException;
use yii\web\NotFoundHttpException;

/**
 * Description of a Base Controller for the gallery module.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
abstract class BaseController extends ContentContainerController
{
    /**
     * @var BaseDataProvider
     */
    protected $dataProvider;

    /**
     * @var int current page
     */
    protected $page = 0;

    public function actionIndex()
    {
        $this->dataProvider = $this->loadPage();
        return $this->renderGallery($this->prepareInitialItems($this->dataProvider->getModels()));
    }

    /**
     * This function can be overwritten by subclasses in order to add or manipulate the initial items array.
     *
     * @param $items
     * @return mixed
     */
    protected function prepareInitialItems($items)
    {
        return $items;
    }

    /**
     * @param int $page
     * @return ActiveDataProvider
     * @throws Exception
     * @throws \Throwable
     */
    protected function loadPage($page = 0)
    {
        $query = $this->getPaginationQuery();
        if(!$query) {
            throw new NotFoundHttpException();
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'page' => $page,
                'pageSize' => $this->getPageSize()
            ]
        ]);
    }

    abstract protected function getPaginationQuery();
    abstract protected function renderGallery($items);

    /**
     * @return BaseGallery|null the current gallery, note the gallery overview itself does not have a gallery model
     */
    abstract protected function getGallery();


    final protected function isAdmin()
    {
        if(Yii::$app->user->isGuest) {
            return false;
        }

        if($this->contentContainer instanceof Space) {
            return $this->contentContainer->isAdmin();
        }

        return $this->contentContainer->id === Yii::$app->user->id;
    }

    public function actionLoadPage($page)
    {
        $page = (int) $page;
        $this->dataProvider = $this->loadPage($page);
        $models = $this->dataProvider ->getModels();

        return $this->asJson([
            'html' => GalleryList::widget(['entryList' => $models, 'entriesOnly' => true, 'parentGallery' => $this->getGallery()]),
            'isLast' => $this->isLastPage($page)
        ]);
    }

    protected function isLastPage($page = 0)
    {
        if(!$this->dataProvider || !$this->dataProvider->getPagination()) {
            return true;
        }

        return $this->dataProvider->getPagination()->getPageCount() <= $page + 1;
    }

    protected function getPageSize()
    {
        return $this->module->galleryMaxImages;
    }
}
