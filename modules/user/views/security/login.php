<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use dektrium\user\widgets\Connect;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var dektrium\user\models\LoginForm $model
 * @var dektrium\user\Module $module
 */

$this->title = Yii::t('user', 'Login');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="user-registration-register">

    <article class="post entry" itemscope data-itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

        <header class="entry-header">
            <h1 class="entry-title" itemprop="headline"><?= Html::encode($this->title) ?></h1>
        </header>

        <div class="entry-content" itemprop="text">
            <div class="row">
                <div class="col-lg-6">
                    <? $form = ActiveForm::begin([
                        'id' => 'login-form',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                        'validateOnBlur' => false,
                        'validateOnType' => false,
                        'validateOnChange' => false,
                    ]);

                    echo $form->field($model, 'login', [
                                'inputOptions' => [
                                    'autofocus' => 'autofocus',
                                    'class' => 'form-control',
                                    'tabindex' => '1',
                                    'placeholder' => $model->getAttributeLabel('login'),
                                ]
                            ]
                        )->label(false) .
                        $form->field($model, 'password', [
                                'inputOptions' => [
                                    'class' => 'form-control',
                                    'tabindex' => '2',
                                    'placeholder' => $model->getAttributeLabel('password'),
                                ]]
                        )->passwordInput()->label(false) .

                        $form->field($model, 'rememberMe')->checkbox(['tabindex' => '4']);

                    ?>
                    <div class="form-group">
                        <?= Html::submitButton(
                            Yii::t('user', 'Login'),
                            ['class' => 'btn btn-project', 'tabindex' => '3']
                        ) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <?php if ($module->enablePasswordRecovery): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Forgot password?'), ['/user/recovery/request']) ?>
                </p>
            <?php endif ?>
            <?php if ($module->enableConfirmation): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Didn\'t receive confirmation message?'), ['/user/registration/resend']) ?>
                </p>
            <?php endif ?>
            <?php if ($module->enableRegistration): ?>
                <p class="text-center">
                    <?= Html::a(Yii::t('user', 'Don\'t have an account? Sign up!'), ['/user/registration/register']) ?>
                </p>
            <?php endif ?>
            <?= Connect::widget([
                'baseAuthUrl' => ['/user/security/auth'],
            ]) ?>
        </div>
        <div class="col-lg-6">
        </div>
</div>

</div>
</article>

</div>

