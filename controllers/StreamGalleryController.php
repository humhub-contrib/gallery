<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\gallery\models\StreamGallery;
use humhub\modules\stream\actions\Stream;
use \Yii;
use \yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Posted Media Files gallery (Images from stream)
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class StreamGalleryController extends BaseController
{
    /**
     * @var StreamGallery
     */
    public $streamGallery;

    /**
     * @inheritDoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        $this->streamGallery = StreamGallery::findForContainer($this->contentContainer);
        if(!$this->streamGallery) {
            throw new NotFoundHttpException();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    protected function getPaginationQuery()
    {
        return $this->streamGallery->fileListQuery();
    }

    /**
     * @param $items
     * @return string
     */
    protected function renderGallery($items)
    {
        return $this->render("gallery_view", [
            'files' => $items,
            'gallery' => $this->streamGallery,
            'container' => $this->contentContainer,
            'showMore' => !$this->isLastPage()
        ]);
    }

    protected function getGallery()
    {
        return $this->streamGallery;
    }
}
