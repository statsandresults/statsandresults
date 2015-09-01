<?php

namespace app\modules\bet\controllers;

use yii\web\Controller;

class PredictionsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
