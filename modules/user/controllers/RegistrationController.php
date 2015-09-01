<?php

namespace app\modules\user\controllers;

use yii\helpers\ArrayHelper;
use dektrium\user\controllers\RegistrationController as BaseRegistrationController;

class RegistrationController extends BaseRegistrationController
{

    public function actions()
    {
        return ArrayHelper::merge(
            parent::actions(),
            [
                'captcha' => [
                    'class' => 'app\modules\main\captcha\CaptchaAction',
                    'foreColor' => 0xdb2b35,
                    'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                ]
            ]
        );
    }

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'rules' => [
                        ['allow' => true, 'actions' => ['captcha'], 'roles' => ['?', '@']],
                    ],
                ],
            ]
        );

    }
}