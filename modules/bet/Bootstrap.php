<?php

namespace app\modules\bet;

use Yii;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;
use yii\web\GroupUrlRule;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 */
class Bootstrap implements BootstrapInterface
{

    /** @inheritdoc */
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('bet') && ($module = $app->getModule('bet')) instanceof Module) {

            if ($app instanceof ConsoleApplication) {

            } else {

                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'routePrefix' => $module->routePrefix,
                    'rules'  => $module->urlRules,
                ];

                $app->urlManager->addRules([new GroupUrlRule($configUrlRule)], false);
            }

            if (!isset($app->get('i18n')->translations['bet*'])) {
                $app->get('i18n')->translations['bet*'] = [
                    'class'    => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                ];
            }

        }
    }
}
