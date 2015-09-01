<?php

namespace app\modules\user\models;

use Yii;

/**
 * This is the model class for table "app_login_history".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property string $user_email
 * @property string $user_name
 * @property string $user_ip
 * @property string $user_host
 * @property string $user_agent
 */
class LoginHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_login_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at', 'user_id'], 'integer'],
            [['user_email', 'user_name', 'user_ip', 'user_host', 'user_agent'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'created_at' => Yii::t('user', 'Created At'),
            'updated_at' => Yii::t('user', 'Updated At'),
            'user_id' => Yii::t('user', 'User ID'),
            'user_email' => Yii::t('user', 'User Email'),
            'user_name' => Yii::t('user', 'User Name'),
            'user_ip' => Yii::t('user', 'User Ip'),
            'user_host' => Yii::t('user', 'User Host'),
            'user_agent' => Yii::t('user', 'User Agent'),
        ];
    }
}
