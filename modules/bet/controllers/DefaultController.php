<?php

namespace app\modules\bet\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class DefaultController extends Controller
{

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['?', '@']],
                    ['allow' => true, 'actions' => ['rest'], 'roles' => ['?', '@']],
                ],
            ],
        ];
    }

    public function prepareRest()
    {
        $result = [
            'filter' => [],
            'content' => [],
            'errors' => []
        ];

        $path_sports = Yii::$app->getRuntimePath() .
            DIRECTORY_SEPARATOR . 'cron' .
            DIRECTORY_SEPARATOR . 'sports';

        $path_live = Yii::$app->getRuntimePath() .
            DIRECTORY_SEPARATOR . 'cron' .
            DIRECTORY_SEPARATOR . 'live';

        $files = scandir($path_sports, SCANDIR_SORT_DESCENDING);
        $latest_sports_file = $files[0];
        $lsf_path = $path_sports . DIRECTORY_SEPARATOR . $latest_sports_file;

        $files = scandir($path_live, SCANDIR_SORT_DESCENDING);
        $latest_live_file = $files[0];
        $llf_path = $path_live . DIRECTORY_SEPARATOR . $latest_live_file;

        $files = 0;
        unset($files);

        if (!file_exists($lsf_path)) {
            $result['errors'][] = 'Sports file not found';
        } elseif (!file_exists($llf_path)) {
            $result['errors'][] = 'Live results file not found';
        }
        if (!is_array($sports = @include_once($lsf_path))) {
            $result['errors'][] = 'Error reading sport file. Unknown format.';
        } elseif (!is_array($live = @include_once($llf_path))) {
            $result['errors'][] = 'Error reading live file. Unknown format.';
        } elseif (!isset($live['Sports']) || !isset($live['Sports']['XSport']) || !is_array($live['Sports']['XSport'])) {
            $result['errors'][] = 'Unknown live file structure.';
        } elseif (!empty($live['Sports']['XSport'])) {
            include_once($this->getViewPath() . DIRECTORY_SEPARATOR . 'template.php');
            $result['filter'] = [];
            while (list(, $sport) = each($live['Sports']['XSport'])) {
                if (isset($sport['@attributes'])) {
                    $result['filter'][] = filterTemplate(
                        $sport['@attributes']['Code'],
                        $sport['@attributes']['Name']
                    );

                    $groups = [];
                    if (isset($sport['Groups']) &&
                        isset($sport['Groups']['XGroup']) &&
                        is_array($sport['Groups']['XGroup'])
                    ) {
                        $groups = $sport['Groups']['XGroup'];
                        if (sizeof($groups) == 2 &&
                            isset($groups['@attributes']) &&
                            isset($groups['Events'])
                        ) {
                            $groups = [$groups];
                        }
                    }
                    $result['content'][] = sportBlock(
                        $sport['@attributes']['Code'],
                        $sport['@attributes']['Name'],
                        $groups
                    );
                    /*
                                            var_dump($groups);

                                            break;*/

                }
            }
        }

        return $result;
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

    public function actionIndex()
    {
        return $this->render('index');
    }

}
