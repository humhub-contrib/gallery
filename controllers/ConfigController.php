<?php
/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2023 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use humhub\modules\admin\components\Controller;
use humhub\modules\gallery\models\forms\ConfigureForm;
use Yii;

/**
 * ConfigController handles the global configuration.
 */
class ConfigController extends Controller
{
    public function actionIndex()
    {
        $form = new ConfigureForm();

        if ($form->load(Yii::$app->request->post()) && $form->save()) {
            $this->view->saved();
        }

        return $this->render('index', ['model' => $form]);
    }
}
