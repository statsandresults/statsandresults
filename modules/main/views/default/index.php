<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Stats And Results';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="main-default-index">

    <article class="post entry" itemscope data-itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

        <header class="entry-header">
            <h1 class="entry-title"
                itemprop="headline">We elevate your game with the solutions to revolutionize sport in your arena</h1>
        </header>

        <div class="entry-content" itemprop="text">
            <p><span style="color: #eb122b">STATSANDRESULT.COM</span> is the world’s leading sports technology, data and content company. We provide next
                generation sports content and are continuously at the forefront in the sports technology sector.</p>

            <p>From premium data feeds to customizable turnkey solutions,
                <span style="color: #eb122b">STATSANDRESULTS.COM</span> has the sports content
                your audience craves.</p>

            <?= (Yii::$app->user->isGuest ?
                Html::tag('a', Yii::t('main', 'REGISTRATION'), [
                    'href' => '/user/registration/register',
                    'class' => 'btn btn-default btn-registration',
                    'role' => 'button'
                ]) : '') ?>
        </div>
    </article>

</div>
