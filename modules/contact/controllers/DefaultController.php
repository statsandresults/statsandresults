<?php

namespace app\modules\contact\controllers;

use app\modules\contact\models\Contact;
use yii\web\Controller;
use Yii;

class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'app\modules\main\captcha\CaptchaAction',
                'foreColor'=>0xdb2b35,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new Contact();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        } else {
            return $this->render('index', [
                'model' => $model,
            ]);
        }
    }
}
