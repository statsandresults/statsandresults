<?
use app\assets\AppAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<? $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="57x57" href="/ico/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/ico/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/ico/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/ico/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/ico/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/ico/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/ico/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/ico/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/ico/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/ico/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/ico/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/ico/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/ico/favicon-16x16.png">
    <link rel="manifest" href="/ico/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ico/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <? $this->head() ?>
    <script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "WebSite",
  "name" : <?= \yii\helpers\Json::encode(Yii::$app->name) ?>,
  "url" : <?= \yii\helpers\Json::encode(Yii::$app->getHomeUrl()) ?>
}


    </script>
</head>
<?
$current_module_id = Yii::$app->controller->module->id;
$current_controller_id = Yii::$app->controller->id;
$current_action_id = Yii::$app->controller->action->id;

$body_class = 'bv-' . $current_module_id . '-' . $current_controller_id . '-' . $current_action_id;
?>
<body itemscope itemtype="http://schema.org/webPage"
      class="<?= $body_class . ' bvm-' . $current_module_id .
      ' bvc-' . $current_controller_id . ' bva-' . $current_action_id ?>">
<?

?>
<? $this->beginBody() ?>
<?
NavBar::begin([
    'brandLabel' => '<img src="/images/template/logo_statsandresults.png" width="258"'.
        ' height="37" alt="STATSANDRESULTS.COM">',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar navbar-default navbar-fixed-top web-page-header',
        'tag' => 'header',
        'itemscope' => '',
        'itemtype' => 'http://schema.org/WPHeader'
    ],
]);

echo Nav::widget([
    'options' => [
        'class' => 'wph-menu-items navbar-nav navbar-right',
        'itemscope' => '',
        'role' => "navigation",
        'itemtype' => 'http://schema.org/SiteNavigationElement'
    ],

    'items' => [
        Yii::$app->user->isGuest ?
            ['label' => Yii::t('main', 'Login'),
                'url' => ['/user/security/login'],
                'visible' => Yii::$app->hasModule('user'),
                'options' => [
                    'class'=>'icon-prjLogin'
                ]
            ] :
            ['label' => Yii::t('main', 'Logout') . ' (' . Html::encode(Yii::$app->user->identity->username) . ')',
                'url' => ['/user/security/logout'],
                'linkOptions' => ['data-method' => 'post'],
                'visible' => Yii::$app->hasModule('user'),
                'options' => [
                    'class'=>'icon-prjLogout'
                ]
            ],

        Html::tag('li', Html::tag('div', ''), [
            'class' => 'wph-menu-separator'
        ]),

        //<span class="icon-prjRegister"></span>
        ['label' => Yii::t('main', 'Register'),
            'url' => ['/user/registration/register'],
            'visible' => Yii::$app->user->isGuest && Yii::$app->hasModule('user'),
            'options' => [
                'class'=>'icon-prjRegister'
            ]
        ]
    ],
]);

NavBar::end();
?>
<main class="web-page-main" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

    <div class="container">
        <div class="web-page-fixed-background"></div>
        <div class="web-page-main-area">
            <div class="web-page-main-navBar">
                <?
                NavBar::begin([
                    'options' => [
                        'class' => 'navbar navbar-default web-page-main-betNavBar',
                        'itemscope' => '',
                        'role' => "navigation",
                        'itemtype' => 'http://schema.org/SiteNavigationElement'
                    ]
                ]);

                echo Nav::widget([
                    'options' => ['class' => 'nav nav-tabs web-page-main_betNavItems '],

                    'items' => [
                        ['label' => Yii::t('main', 'Live Results'),
                            'url' => ['/bet/default/index'],
                            'visible' => Yii::$app->hasModule('bet')],
                        ['label' => Yii::t('main', 'Past Results'),
                            'url' => ['/bet/past/index'],
                            'visible' => Yii::$app->hasModule('bet')],
                        ['label' => Yii::t('main', 'Predictions'),
                            'url' => ['/bet/predictions/index'],
                            'visible' => Yii::$app->hasModule('bet')],
                        ['label' => Yii::t('main', 'Statistics'),
                            'url' => ['/bet/statistics/index'],
                            'visible' => Yii::$app->hasModule('bet')],
                    ]
                ]);

                NavBar::end();
                ?>
            </div>
            <div class="web-page-main-content-area">
                <div class="web-page-main-content">
                    <? /*echo Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ])*/ ?>
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>
</main>
<?
NavBar::begin([
    'options' => [
        'class' => 'navbar navbar-default web-page-footer',
        'tag' => 'footer',
        'itemscope' => '',
        'role' => "contentinfo",
        'itemtype' => 'http://schema.org/WPFooter'
    ]
]);

echo Nav::widget([
    'options' => [
        'class' => 'nav nav-pills nav-justified web-page-footer-navBar ',
        'itemscope' => '',
        'role' => "navigation",
        'itemtype' => 'http://schema.org/SiteNavigationElement'
    ],

    'items' => [
        ['label' => 'Contact Us',
            'url' => ['/contact/default/index'],
            'visible' => Yii::$app->hasModule('contact')],
        ['label' => 'About Us',
            'url' => ['/main/about/index'],
            'visible' => Yii::$app->hasModule('main')],
        ['label' => 'Terms and Conditions',
            'url' => ['/main/terms/index'],
            'visible' => Yii::$app->hasModule('main')],
        ['label' => 'Privacy Policy',
            'url' => ['/main/privacy/index'],
            'visible' => Yii::$app->hasModule('main')],
        ['label' => 'Security',
            'url' => ['/main/security/index'],
            'visible' => Yii::$app->hasModule('main')],
        ['label' => 'Payments',
            'url' => ['/main/payments/index'],
            'visible' => Yii::$app->hasModule('main')],
        Html::tag('li', Html::tag('img', '', [
            'src' => '/images/template/footer_cards.png',
            'width' => '112',
            'height' => '29',
            'alt' => '',
            'border' => 0
        ]))
    ]
]);

NavBar::end();
?>

<? $this->endBody() ?>
</body>
</html>
<? $this->endPage() ?>
