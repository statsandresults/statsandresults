<?php

namespace app\modules\contact;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\contact\controllers';

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

    /** @var array The rules to be used in URL management. */
    public $urlRules = [
        'contact' => 'contact/default/index',
    ];

    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }

}
