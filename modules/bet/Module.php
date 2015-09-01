<?php

namespace app\modules\bet;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\bet\controllers';

    /**
     * @var string The prefix for user module URL.
     *
     * @See [[GroupUrlRule::prefix]]
     */
    public $urlPrefix = null;

    /**
     * @var string The prefix for the route part of every rule declared in $rules. The prefix and the route will be separated
     * with a slash. If this property is not set, it will take the value of $prefix..
     *
     * @See [[GroupUrlRule::routePrefix]]
     */
    public $routePrefix = null;

    public $icons = [
        'icons' => [
            'AmericanFootball',
            'Bandy',
            'Baseball',
            'Basketball',
            'Boxing',
            'Chess',
            'Cricket',
            'Darts',
            'e-Sports',
            'Floorball',
            'Football',
            'GaelicFootball',
            'Golf',
            'Greyhounds',
            'Handball',
            'Hurling',
            'IceHockey',
            'Lottery',
            'MMA',
            'Motorsport',
            'OtherSports',
            'Pool',
            'RugbyLeague',
            'RugbyUnion',
            'Snooker',
            'Specials',
            'Tennis',
            'Timer',
            'Volleyball',
            'WinterSports'
        ],
        'cssPrefix' => 'icon-sport',
        'default' => 'Timer'
    ];

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        'bet/live-results' => 'bet/default/index',
        'bet/live-results/rest' => 'bet/default/rest',

        'bet/past-results' => 'bet/past/index',
        'bet/past-results/rest' => 'bet/past/rest',

        'bet/statistics' => 'bet/statistics/index',

        'bet/predictions' => 'bet/predictions/index'
    ];

    public function buildIcon($code)
    {
        return $this->icons['cssPrefix'].(!in_array($code, $this->icons['icons'])
            ?$this->icons['default']
            :$code
        );
    }

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
