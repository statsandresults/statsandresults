<?
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \app\modules\contact\models\Contact */

$this->title = 'Contact Us';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="contact-default-index">

    <header class="entry-header">
        <h1 class="entry-title" itemprop="headline">
            <?= Html::encode($this->title) ?>
        </h1>
    </header>

    <? if (Yii::$app->session->hasFlash('contactFormSubmitted')) { ?>

        <div class="alert alert-success">
            Thank you for contacting us. We will respond to you as soon as possible.
        </div>

    <? } else { ?>

        <p>
            For your convinienoe, please use the icontact form below, to contact us<br/>
            We'll get back to you as soon as poaeible.
        </p>

        <div class="row">
            <div class="col-lg-12">
                <?

                $user_info = Yii::$app->get('user', false);
                $user_id = $user_info && !$user_info->isGuest ? $user_info->id : false;
                //                var_dump($user_id);

                $form = ActiveForm::begin([
                    'id' => 'contact-form',
                    'enableClientValidation'=>true,
                    'enableAjaxValidation'=>true
                ]);

                echo ($user_id === false
                        ? $form->field($model, 'user_name', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('user_name')
                            ],
                        ])->label(false) .

                        $form->field($model, 'user_email', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('user_email')
                            ],
                        ])->label(false)
                        : ''
                    ) .

                    $form->field($model, 'user_message', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('user_message')
                        ],
                    ])->label(false)->textArea(['rows' => 7]) .

                    ($user_id === false
                        ? $form->field($model, 'verifyCode', [
                            /**
                             * Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically.
                             * As a result, CAPTCHA validation should not be used in AJAX validation mode because it may
                             * fail the validation even if a user enters the same code as shown in the CAPTCHA image
                             * which is actually different from the latest CAPTCHA code.
                             */
                            'enableAjaxValidation' => false,
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('verifyCode')
                            ],
                        ])->label(false)->widget(Captcha::className(), [
                            'captchaAction' => '/contact/default/captcha',
                            'template' => '<div class="row"><div class="col-lg-8">{input}</div><div class="col-lg-4">{image}</div></div>',
                        ])
                        : ''
                    );

                ?>
                <div class="form-group">
                    <?= Html::submitButton('Send', [
                        'class' => 'btn btn-project',
                        'name' => 'contact-button'
                    ]) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <? } ?>
</div>
