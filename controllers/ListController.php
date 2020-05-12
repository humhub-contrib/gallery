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
        return CustomGallery::find()->contentContainer($this->contentContainer)->readable();
    }

    /**
     * Action to delete an item.
     *
     * @return string the rendered view.
     * @throws HttpException
     */
    public function actionDeleteMultiple($itemId, $gid = null)
    {
        $this->forcePostRequest();
        $this->canWrite(true);

        $item = $this->module->getItemById($itemId);

        if($item instanceof ContentActiveRecord && !$item->content->canEdit()) {
            throw new HttpException(403);
        }

        if($item->delete()) {
            $this->view->success(Yii::t('GalleryModule.base', 'Deleted'));
        } else {
            $this->view->error(Yii::t('GalleryModule.base', 'Item could not be deleted!'));
        }

        if($item instanceof CustomGallery) {
            return $this->htmlRedirect($this->contentContainer->createUrl('/gallery/list'));
        }

        return $this->htmlRedirect(Url::toCustomGallery($this->contentContainer, $gid));
    }

    /**
     * @inheritDoc
     */
    protected function getGallery()
    {
        return null;
    }
}
