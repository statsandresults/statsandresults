<?php

namespace app\modules\bet\controllers;

use yii\web\Controller;

class StatisticsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string visible|hidden
     */
    public function visibleStatus() {
        return 'visible';
    }
}
