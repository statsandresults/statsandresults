<?
use yii\web\View;

/* @var $this yii\web\View */
$this->title = 'Live Results';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/live-results.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJsFile('@web/js/js.cookie.js',
    ['position' => View::POS_BEGIN]
);

/**
 * app\modules\bet\controllers\DefaultController
 */
$result = Yii::$app->controller->prepareRest();


?>
<div class="bet-default-index">
    <div class="wpmc-page-filter">
        <div class="wpmcpf-title"><span class="wpmcpf-current"><?=
                Yii::t('bet', 'Current sports:') ?></span>
            <button type="button" class="btn btn-project wpmcpf-show-all"><?=
                Yii::t('bet', 'Show All') ?></button>
        </div>
        <ul class="wpmcpf-items"><?=implode("\r\n",$result['filter'])?></ul>
    </div>
    <div class="wpmc-page-separator"></div>
    <div class="wpmc-page-content"><?=implode("\r\n",$result['content'])?></div>
</div>
