<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;
use app\modules\country\models\Country;
use yii\helpers\ArrayHelper;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\User $user
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Registration');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-registration-register">

    <article class="post entry" itemscope data-itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

        <header class="entry-header">
            <h1 class="entry-title" itemprop="headline"><?= Html::encode($this->title) ?></h1>
        </header>

        <div class="entry-content" itemprop="text">
            <div class="row">
                <div class="col-lg-6">
                    <?

                    $form = ActiveForm::begin([
                        'id' => 'registration-form',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => true,
                    ]);

                    echo $form->field($model, 'email', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('email'),
                                'class' => 'form-control'
                            ],
                        ])->label(false) .

                        $form->field($model, 'username', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('username'),
                                'class' => 'form-control'
                            ],
                        ])->label(false);

                    if ($module->enableGeneratingPassword == false) {
                        echo $form->field($model, 'password', [
                                'inputOptions' => [
                                    'placeholder' => $model->getAttributeLabel('password'),
                                    'class' => 'form-control'
                                ],
                            ])->label(false)->passwordInput() .
                            $form->field($model, 'repeat_password', [
                                'inputOptions' => [
                                    'placeholder' => $model->getAttributeLabel('repeat_password'),
                                    'class' => 'form-control'
                                ],
                            ])->label(false)->passwordInput();
                    }

                    echo $form->field($model, 'first_name', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('first_name'),
                                'class' => 'form-control'
                            ],
                        ])->label(false) .

                        $form->field($model, 'last_name', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('last_name'),
                                'class' => 'form-control'
                            ],
                        ])->label(false) .

                        $form->field($model, 'country', [
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('country'),
                                'class' => 'form-control'
                            ],
                        ])->label(false)->dropDownList(
                            ArrayHelper::map(Country::find()->all(), 'iso2', 'short_name'),
                            ['prompt' => $model->getAttributeLabel('country')]) .

                        $form->field($model, 'verifyCodeReg', [
                            /**
                             * Note that once CAPTCHA validation succeeds, a new CAPTCHA will be generated automatically.
                             * As a result, CAPTCHA validation should not be used in AJAX validation mode because it may
                             * fail the validation even if a user enters the same code as shown in the CAPTCHA image
                             * which is actually different from the latest CAPTCHA code.
                             */
                            'enableAjaxValidation' => false,
                            'inputOptions' => [
                                'placeholder' => $model->getAttributeLabel('verifyCodeReg')
                            ],
                        ])->label(false)->widget(Captcha::className(), [
                            'captchaAction' => '/user/registration/captcha',
                            'template' => '<div class="row"><div class="col-lg-8">{input}</div><div class="col-lg-4">{image}</div></div>',
                        ]);

                    ?>
                    <div class="form-group"><?= Html::submitButton(
                            Yii::t('user', 'Register'), ['class' => 'btn btn-project']
                        ); ?></div>
                    <? ActiveForm::end(); ?>
                    <p class="text-center">
                        <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
                    </p>
                </div>
                <div class="col-lg-6">
                    <h2 class="urr-h2">why register with stats and results:</h2>
                    <table class="table">
                        <tr>
                            <td class="icon-prjGears"></td>
                            <td>Our own unique prediction tool</td>
                        </tr>
                        <tr>
                            <td class="icon-prjProgress"></td>
                            <td>Most accurate and up-to-date stats and results</td>
                        </tr>
                        <tr>
                            <td class="icon-prjKing"></td>
                            <td>Get a premium content</td>
                        </tr>
                        <tr>
                            <td class="icon-prjCreditCard"></td>
                            <td>Faster payment options for exclusive content</td>
                        </tr>
                        <tr>
                            <td class="icon-prjDiscount"></td>
                            <td>Special offers from our partners</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </article>

</div>

