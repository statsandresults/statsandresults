<?php

namespace app\modules\bet\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use app\modules\bet\models\FilterPastResults;

class PastController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['?', '@']],
                    ['allow' => true, 'actions' => ['rest'], 'roles' => ['?', '@']]
                ],
            ],
        ];
    }

    public function actionRest()
    {


        if (Yii::$app->request->isAjax) {

            Yii::$app->response->format = Response::FORMAT_JSON;
            echo json_encode($this->prepareRest());
            Yii::$app->end();
        }

        throw new NotFoundHttpException('Unknown ajax request.');
    }

    public function prepareRest()
    {
        /** @var FilterPastResults $model */
        $model = Yii::createObject([
            'class' => FilterPastResults::className(),
            'scenario' => 'filter',
        ]);

        $result = [
            'content' => [],
            'total' => [],
            'errors' => []
        ];

        include_once(Yii::$app->getBasePath() . DIRECTORY_SEPARATOR . 'crons' . DIRECTORY_SEPARATOR . 'api.php');

        if($model->load(Yii::$app->request->post()) && $model->validate()) {

            $data = [
                'loc' => $model->loc,
                'action' => $model->action,
                'DF' => strtotime(date($model->dateFrom.' 00:00:00')) * 1000,
                'DT' => strtotime(date($model->dateTo.' 23:59:59')) * 1000,
                'Sport' => $model->sports,
                'collapsedSports' => false,
                'substr' => $model->search,
                'categoriesPerPage' => $model->categoriesPerPage,
                'page' => $model->page
            ];


            $url = 'http://212.38.167.37/resultsproxy/getresultsxml3.aspx';

            $xml = apiReadUrlXml($url, $data);

            $array = apiParseXml($xml);


            if ($array['status'] === true) {

                $array = obj2array($array['result']);
                $result['total'] = $array['@attributes'];

                if($array['@attributes']['page']> $array['@attributes']['totalpages']) {
                    $result['status'] = 3;
                    return $result;
                }

                if ($array['@attributes']['count'] == 0) {
                    $result['status'] = 2;
                    $result['errors'][] = Yii::t('bet', 'No results were found that met all the given criteria');
                } else {

                    include_once($this->getViewPath() . DIRECTORY_SEPARATOR . 'template.php');

                    $array = $array['sports']['PSport'];

                    if (sizeof($array) == 2 &&
                        isset($array['@attributes']) &&
                        isset($array['Groups'])
                    ) {
                        $array = [$array];
                    }

                    if($model->page == 1) {
                        $result['content'][] = '<table role="presentation" cellpadding="0" cellspacing="0">';
                        $result['content'][] = '<tbody>';
                    }
                    foreach($array as $sport) {

                        if (is_array($sport['@attributes'])) {

                            sportBlock(
                                $result['content'],
                                $sport['@attributes']['id'],
                                $sport['@attributes']['code'],
                                $sport['@attributes']['name'],
                                $sport
                            );
                        }
                    }
                    if($model->page == 1) {
                        $result['content'][] = '</tbody>';
                        $result['content'][] = '</table>';
                    }

                    //$result['content'] = $array;
                }

            } else {

                $result['errors'] = $array['errors'];
            }
        } else {
            $result['status'] = false;
            $result['errors'] = array_values($model->getErrors());
        }



        //?loc=ru-RU&action=GETRESULTS&DF=1436043600000&DT=1436129999999&Sport=1763111,1763108,1763113,1763081,1763099,1762805,
        //1763102,1763049,1763104,1763048,1763105,1763114,1763082,1763068,1763057,1762821,1763040,1763059,1763077,1763085,1763116,
        //1763096,1763070,1763061,1781423,1763112,1763117,1763115,1763109,1763110,1763052,1781422,1763054,1763090,1762785,1763037,
        //1763091,1763072,1781414,1763045,1763044,1763056,1762822,1763106,1763080,1763079,1763060,2576760,1763051,1763053,1763075,
        //1763076,1763062,1763083,1763046,1762818,1762803,1763101,1763118,1762823,1763064,1763100,1763107,1762801,1763043,1763066,
        //1763094,1763067,1763073,1763078,1762798,1762824,1763074,1763038,1763058,1763036,1763097,1763063,1763050,1763069,1762800,
        //1763042,1781407,1763086,1763088,1763103,1763093&collapsedSports=false&page=1

        return $result;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string visible|hidden
     */
    public function visibleStatus()
    {
        return 'visible';
    }

}
