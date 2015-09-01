<?

use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\web\View;

/* @var $this yii\web\View */
$this->title = 'Past Results';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/js/moment-with-locales.js',
    ['position' => View::POS_BEGIN]
);
$this->registerJsFile('@web/js/js.cookie.js',
    ['position' => View::POS_BEGIN]
);

$this->registerJsFile('@web/js/past-results.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

$path_sports = Yii::$app->getRuntimePath() .
    DIRECTORY_SEPARATOR . 'cron' .
    DIRECTORY_SEPARATOR . 'sports';

$files = scandir($path_sports, SCANDIR_SORT_DESCENDING);
$latest_sports_file = $files[0];
$lsf_path = $path_sports . DIRECTORY_SEPARATOR . $latest_sports_file;

if (!file_exists($lsf_path)) {
    throw new NotFoundHttpException('Sports file not found');
}
if (!is_array($sports = @include_once($lsf_path))) {
    throw new NotFoundHttpException('Error reading sport file. Unknown format.');
}

$firstFilter = ['Football', 'Tennis', 'Basketball', 'IceHockey',
    'Volleyball', 'Golf', 'Boxing', 'Pool', 'Baseball', 'Handball'];

$showFilter = $moreFilter = $ownerFilter = $all = [];
foreach ($sports['RSport'] as $ind => $data) {
    $attr = $data['@attributes'];
    $code = str_replace(" ", "", $attr['code']);
    $id = (int)$attr['id'];
    if (($ak = array_search($code, $firstFilter)) !== false) {
        $showFilter[] = $attr;
        $all[$id] = true;
    } elseif ((int)$data['@attributes']['ownerid'] == 0) {
        $moreFilter[] = $attr;
        $all[$id] = false;
    } else {
        $ownerFilter[] = $attr;
        $all[$id] = false;
    }
}

$s1 = json_encode($showFilter);
$s2 = json_encode($moreFilter);
$s3 = json_encode($ownerFilter);
$s4 = json_encode($all);
$js = <<<JS
(function() {
    window.objectSports = {
        'first': $s1,
        'more': $s2,
        'owners': $s3,
        'all': $s4
    };
})();
JS;
unset($s1, $s2, $s3, $a4);
$this->registerJs($js, View::POS_BEGIN, __CLASS__);

/**
 * @var $betModule app\modules\bet\Module
 */
$betModule = Yii::$app->getModule('bet');
?>
<div class="bet-past-index">

    <div class="pr-page-filter pr-f-1">
        <div class="pr-filter-title"><span class="pr-filter-current">
            <button type="button" class="btn btn-project pr-filter-show-all"><?=
                Yii::t('bet', 'All Results') ?></button>
        </div>
        <ul class="pr-filter-items"><?
            foreach ($showFilter as $sport) {
                $code = str_replace(" ", "", $sport['code']);
                echo '<li class="pr-filter-item item-sport-' . Html::encode($code) .
                    '" data-code="' . Html::encode($code) .
                    '" data-oid="' . Html::encode($sport['ownerid']) .
                    '" data-sid="' . Html::encode($sport['id']) . '">' .
                    '<span class="pr-filter-icon ' . Html::encode($betModule->buildIcon($code)) . '"></span>' .
                    '<span class="pr-filter-name">' . Html::encode($sport['name']) . '</span></li>';
            }

            ?></ul>
        <div class="pr-filter-more">
            <button
                type="button" class="btn btn-project pr-filter-more-bth"><?=
                Yii::t('bet', 'More') ?> <span class="icon-prjExpand"></span></button>
        </div>
    </div>
    <div class="pr-filter-separator pr-f-1"></div>

    <div class="pr-filter-all-sports"></div>

    <?
    $today = date('Y-m-d');
    $date = date_create();
    date_add($date, date_interval_create_from_date_string("-7 days"));
    ?>
    <div class="pr-filter-calendar">

        <nav class="pr-filter-inline-calendar" data-from="<?= date_format($date, 'Y-m-d') ?>"
             data-to="<?= $today ?>" data-selected="">
            <ul>
                <li class="nev-c-left"><a href="#" class=" glyphicon glyphicon-chevron-left"></a></li>
                <?
                for ($x = 0; $x < 7; $x++) {
                    date_add($date, date_interval_create_from_date_string("1 day"));
                    ?>
                    <li data-date="<?= date_format($date, 'Y-m-d') ?>"><a
                            href="#" onclick="return false;"><?=
                            date_format($date, 'j F') . '<br>' .
                            date_format($date, 'l');
                            ?></a></li>
                    <?
                }
                ?>
                <li class="nev-c-right"><a href="#" class="glyphicon glyphicon-chevron-right"></a></li>
            </ul>
            <div class="nev-c-disabled-block"></div>
        </nav>
        <form class="form-inline pr-filter-date-range" id="drForm">
            <div class="checkbox pr-form-dr-select">
                <label>
                    <input type="checkbox" id="drSelect""> Choose a date range:
                </label>
            </div>
            <div class="form-group pr-form-dr-from">
                <label for="drFrom">From</label>
                <input type="date" class="form-control icon-prjCalendar"
                       id="drFrom" value="<?= $today ?>" max="<?= $today ?>" disabled="disabled">
            </div>
            <div class="form-group pr-form-dr-to">
                <label for="drTo">to</label>
                <input type="date" class="form-control icon-prjCalendar"
                       id="drTo" value="<?= $today ?>" max="<?= $today ?>" disabled="disabled">
            </div>
        </form>
    </div>
    <div class="pr-filter-separator"></div>


    <div class="pr-filter-search">
        <form class="form-inline  pr-filter-form-search">
            <div class="form-group pr-form-s-input">
                <label for="ffsrInput">Include a keyword in search:</label>
                <input type="text" class="form-control ffsr-input" id="ffsrInput"
                       placeholder="eg. team name, league or competition">
            </div>
            <button type="reset" class="btn btn-project ffsr-btn-clear">Clear selections</button>
            <button type="submit" class="btn btn-project ffsr-btn-submit">Search</button>
        </form>
    </div>
    <div class="pr-filter-separator"></div>


    <div class="pr-filter-content">
        <?

        /*                    include_once(__DIR__.DIRECTORY_SEPARATOR.'template.php');
                            echo filterTemplate('Rugby Union', 'Rugby Union');*/

        ?>
    </div>

</div>
