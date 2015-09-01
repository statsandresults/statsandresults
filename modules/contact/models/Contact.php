<?php

namespace app\modules\contact\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * This is the model class for table "{{%contact}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $user_id
 * @property string $user_email
 * @property string $user_name
 * @property string $user_message
 * @property string $user_ip
 * @property string $user_host
 * @property string $user_agent
 * @property integer $status
 */
class Contact extends \yii\db\ActiveRecord
{
    public $verifyCode;

    const STATUS_NEW = 0;
    const STATUS_UPDATED = 3;
    const STATUS_ANSWERED = 5;
    const STATUS_CLOSED = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contact}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /*            [['created_at', 'updated_at', 'user_message'], 'required'],
                        [['created_at', 'updated_at', 'user_id', 'status'], 'integer'],
                        [['user_message'], 'string'],
                        [['user_email', 'user_name', 'user_ip', 'user_host', 'user_agent'], 'string', 'max' => 255]*/

            ['user_name', 'required'],
            ['user_name', 'match', 'pattern' => '#^[\w_-]+$#i'],
            ['user_name', 'string', 'min' => 2, 'max' => 255],

            ['user_email', 'required'],
            ['user_email', 'email'],
            ['user_email', 'string', 'max' => 255],

            ['user_message', 'required'],
            ['user_message', 'string', 'min' => 50],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_NEW],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],

            // verifyCode needs to be entered correctly
            [
                'verifyCode', 'captcha',
                'captchaAction' => '/contact/default/captcha',
                'enableClientValidation' => false,
            ],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['user_name', 'user_email', 'user_message', 'verifyCode']
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'user_name' => 'Name',
            'user_email' => 'Email',
            'user_message' => 'Message',
            'verifyCode' => 'Verification Code',
        ];
    }

    public function getStatusName()
    {
        $statuses = self::getStatusesArray();
        return isset($statuses[$this->status]) ? $statuses[$this->status] : '';
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_NEW => 'New',
            self::STATUS_UPDATED => 'Updated',
            self::STATUS_ANSWERED => 'Answered',
            self::STATUS_CLOSED => 'Closed'
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_id']
                ],
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_ip']
                ],
                'value' => function ($event) {
                    return Yii::$app->getRequest()->getUserIP();
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_host']
                ],
                'value' => function ($event) {
                    return Yii::$app->getRequest()->getUserHost();
                },
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_agent']
                ],
                'value' => function ($event) {
                    return Yii::$app->getRequest()->getUserAgent();
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_NEW]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string $email the target email address
     * @return boolean whether the model passes validation
     */
    public function contact($email)
    {
        if (Yii::$app->request->isAjax && $this->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode(ActiveForm::validate($this));
            Yii::$app->end();
        }

        if ($this->validate()) {

            $user_info = Yii::$app->get('user', false);
            $user_id = $user_info && !$user_info->isGuest ? $user_info->id : null;

            $contact = new Contact();
            $contact->user_id = $user_id;
            $contact->user_email = is_null($user_id) ? $this->user_email : $user_info->identity->email;
            $contact->user_name = is_null($user_id) ? $this->user_name : $user_info->identity->username;
            $contact->user_message = $this->user_message;
            $contact->status = self::STATUS_NEW;
            $contact->verifyCode = $this->verifyCode;

            if ($contact->save()) {
                Yii::$app->mailer->compose()
                    ->setTo($email)
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    /*                ->setSubject($this->subject)*/
                    ->setTextBody($this->user_message)
                    ->send();
            } else {
                /*                var_dump($contact->getErrors());
                                die();*/

                return false;
            }

            return true;
        } else {
            return false;
        }
    }
}
