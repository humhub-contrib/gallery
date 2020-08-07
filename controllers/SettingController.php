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
 * Time: 22:44
 */

namespace humhub\modules\gallery\controllers;

use humhub\modules\user\models\User;
use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\content\components\ContentContainerControllerAccess;
use humhub\modules\space\models\Space;
use humhub\modules\gallery\models\forms\ContainerSettings;

class SettingController extends ContentContainerController
{
    protected function getAccessRules() {
        return [
            ['login'],
            [ContentContainerControllerAccess::RULE_USER_GROUP_ONLY => [Space::USERGROUP_ADMIN]]
        ];
    }

    public function beforeAction($action)
    {
        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
        $settings = new ContainerSettings([
            'contentContainer' => $this->contentContainer
        ]);

        if($settings->load(Yii::$app->request->post()) && $settings->save()) {
            $this->view->saved();
        }


        return $this->render('index', [
            'settings' => $settings,
            'contentContainer' => $this->contentContainer
        ]);
    }

}
