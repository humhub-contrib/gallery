<?php

namespace humhub\modules\gallery\controllers;

use humhub\modules\gallery\helpers\Url;
use \humhub\modules\gallery\models\CustomGallery;
use \humhub\modules\gallery\models\StreamGallery;
use humhub\modules\gallery\permissions\WriteAccess;
use \Yii;
use yii\web\HttpException;

/**
 * Controller responsible for listing all available galleries including stream gallery and custom galleries of a container.
 *
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 *
 */
class ListController extends BaseController
{
    protected function renderGallery($items)
    {
        return $this->render("gallery_list", [
            'galleries' => $items,
            'canWrite' => $this->contentContainer->can(WriteAccess::class),
            'isAdmin' => $this->isAdmin(),
            'showMore' => !$this->isLastPage()
        ]);
    }

    /**
     * @inheritDoc
     */
    protected function prepareInitialItems($items)
    {
        return array_merge([StreamGallery::findForContainer($this->contentContainer)], $items);
    }

    /**
     * @inheritDoc
     */
    protected function getPaginationQuery()
    {
        $pageQuery = CustomGallery::find();

        if ($this->getSettings()->sortByCreated) {
            // Using sortByCreated we add this additional orderBy
            $pageQuery
                ->orderBy([
                    'content.created_at' => SORT_DESC
                ]);
        } else {
            $pageQuery
                ->orderBy([
                    'sort_order' => SORT_DESC,
                    'title' => SORT_ASC,
                ]);
        }

        return $pageQuery->contentContainer($this->contentContainer)->readable();
    }

    /**
     * @inheritDoc
     */
    protected function getGallery()
    {
        return null;
    }
}
