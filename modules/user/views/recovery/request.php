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

/*
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\RecoveryForm $model
 */

$this->title = Yii::t('user', 'Forgot password?');
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

                    <p>If you can’t remember your password. please type below your email and press “Reset”.
                        You will need to use the same email you registered with us opened your account</p>

                    <?php $form = ActiveForm::begin([
                        'id' => 'password-recovery-form',
                        'enableAjaxValidation' => true,
                        'enableClientValidation' => false,
                    ]);

                    echo $form->field($model, 'email', [
                        'inputOptions' => [
                            'placeholder' => $model->getAttributeLabel('email'),
                            'class' => 'form-control'
                        ]])->textInput(['autofocus' => true])->label(false);
                    ?>
                    <div class="form-group">
                        <?= Html::submitButton(Yii::t('user', 'Reset'), ['class' => 'btn btn-project']) ?>
                        <br>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-lg-6">

                </div>
            </div>
        </div>
    </article>
    <img src="/images/template/backgrounds/images/restore_pass_03.png" width="237" height="224"
         border="0" alt="<?= Html::encode($this->title) ?>"
        style="position: absolute; top: 50px; right: 90px;">
</div>

