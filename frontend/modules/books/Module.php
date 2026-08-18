<?php

declare(strict_types=1);

namespace frontend\modules\books;

use Yii;
use yii\web\Application;

/**
 * books module definition class
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'frontend\modules\books\controllers';

    public $layout = 'main';

    public function init()
    {
        parent::init();
    }

// Если надо закастомить, то пишем свои
//    public function bootstrap($app)
//    {
//        if ($app instanceof Application) {
//            $app->getUrlManager()->addRules([
//                ['class' => 'yii\web\UrlRule', 'pattern' => $this->id, 'route' => $this->id . '/book/index'],
//                ['class' => 'yii\web\UrlRule', 'pattern' => $this->id . '<controller>/<action:[\w\-]+>', 'route' => $this->id . '/<controller>/<action>'],
//            ], false);
//        }
//    }
}
