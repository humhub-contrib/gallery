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

use Yii;
use humhub\modules\content\components\ContentContainerController;
use humhub\modules\gallery\models\forms\ContainerSettings;
use humhub\modules\space\modules\manage\components\Controller;

class SettingController extends Controller
{
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