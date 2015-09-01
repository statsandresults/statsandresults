<?php

namespace app\modules\user\models;

use Yii;
use yii\helpers\ArrayHelper;
use dektrium\user\helpers\Password;
use dektrium\user\models\Token;

class User extends \dektrium\user\models\User
{
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        // add field to scenarios
        $scenarios['register'] = ArrayHelper::merge($scenarios['register'], [
            'first_name', 'last_name', 'country', 'verifyCodeReg'
        ]);
        $scenarios['create'] = ArrayHelper::merge($scenarios['create'], [
            'first_name', 'last_name', 'country'
        ]);
        $scenarios['update'] = ArrayHelper::merge($scenarios['update'], [
            'first_name', 'last_name', 'country',
            'gender', 'region', 'address1', 'address2', 'zip', 'telephone', 'timezone'
        ]);
        return $scenarios;
    }

    public function rules()
    {
        $rules = parent::rules();
        // add some rules
        $rules['firstNameRequired'] = ['first_name', 'required'];
        $rules['firstNameLength'] = ['first_name', 'string', 'max' => 255];

        $rules['lastNameRequired'] = ['last_name', 'required'];
        $rules['lastNameLength'] = ['last_name', 'string', 'max' => 255];

        $rules['countryRequired'] = ['country', 'required'];
        $rules['countryLength'] = ['country', 'string', 'max' => 2];

        return $rules;
    }

}