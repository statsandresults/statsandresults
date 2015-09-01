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
 * @var yii\web\View                    $this
 * @var dektrium\user\models\ResendForm $model
 */

$this->title = Yii::t('user', 'Request new confirmation message');
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
                    <? $form = ActiveForm::begin([
                        'id' => 'resend-form',
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
                        <?= Html::submitButton(Yii::t('user', 'Continue'), ['class' => 'btn btn-project']) ?>
                    </div>

                    <? ActiveForm::end(); ?>
                </div>
                <div class="col-lg-6">
                </div>
            </div>
        </div>
    </article>
</div>
