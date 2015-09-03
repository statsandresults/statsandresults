<?php

namespace app\modules\bet\models;

use Yii;
use yii\base\Model;
use app\modules\bet\Module;

class FilterPastResults extends Model
{

    public $loc = 'en_GB';

    public $action = 'GETRESULTS';

    public $sports = '';

    public $search = '';

    public $dateFrom = '';

    public $dateTo = '';

    public $categoriesPerPage = 50;

    public $page = 1;

    /**
     * @var Module
     */
    protected $module;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->module = Yii::$app->getModule('bet');
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'past-results-form';
    }

    /** @inheritdoc */
    public function scenarios()
    {
        return [
            'filter' => ['sports', 'search', 'dateFrom', 'dateTo', 'page']
        ];
    }


    public function rules()
    {
        return [
            'sports' => ['sports', 'required'],

            'searchTrim' => ['search', 'trim'],
            'searchSafe' => ['search', 'safe'],

            'dateFrom' => ['dateFrom', 'date', 'format' => 'yyyy-mm-dd'],
            'dateFromRequired' => ['dateFrom', 'required'],

            'dateTo' => ['dateTo', 'date', 'format' => 'yyyy-mm-dd'],
            'dateToRequired' => ['dateTo', 'required'],

            'page' => ['page', 'integer'],
            'pageRequired' => ['page', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
        ];
    }
}