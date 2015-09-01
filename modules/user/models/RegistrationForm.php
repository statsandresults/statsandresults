<?php

namespace app\modules\user\models;

use yii\helpers\ArrayHelper;
use dektrium\user\models\RegistrationForm as BaseRegistrationForm;
use Yii;

class RegistrationForm extends BaseRegistrationForm
{
    public $repeat_password;

    public $first_name;

    public $last_name;

    public $country;

    public $verifyCodeReg;

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['repeat_password', 'required'],
                ['repeat_password', 'compare', 'compareAttribute' => 'password'],//, 'message' => 'Password.'],

                ['first_name', 'required'],
                ['first_name', 'string', 'max' => 255],

                ['last_name', 'required'],
                ['last_name', 'string', 'max' => 255],

                ['country', 'required'],
                ['country', 'string', 'max' => 2],

                // verifyCode needs to be entered correctly
                [
                    'verifyCodeReg', 'captcha',
                    'captchaAction' => '/user/registration/captcha',
                    'enableClientValidation' => false,
                ]
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'verifyCodeReg' => Yii::t('user', 'Verification Code'),
                'first_name' => Yii::t('user', 'First Name'),
                'last_name' => Yii::t('user', 'Last Name'),
                'country' => Yii::t('user', 'Country'),
            ]
        );
    }

}