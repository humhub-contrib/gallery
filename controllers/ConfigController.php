<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\modules\gallery\controllers;

use \humhub\modules\admin\components\Controller;
use \humhub\modules\gallery\models\forms\ConfigureForm;
use \Yii;

/**
 * ConfigController handles the configuration requests for the gallery module.
 *
 * @deprecated IS DUMMY IMPLEMENTATION.
 *            
 * @package humhub.modules.gallery.controllers
 * @since 1.0
 * @author Sebastian Stumpf
 */
class ConfigController extends Controller
{

    /**
     * Configuration action for super admins.
     * Uses post data to fill the configuration model.
     */
    public function actionIndex()
    {
        $model = new ConfigureForm();
        // prefill config settings

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // save submitted settings
        }

        return $this->render('index', array(
                    'model' => $model
        ));
    }

}

?>
