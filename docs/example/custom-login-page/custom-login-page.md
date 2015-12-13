自定义登录页面
=========

修改`@app/config/main.php`配置

```php
'controllerMap' => [
    ...
    'site' => '\backend\controllers\SiteController', // 自定义的控制器完整类名
],
```

```php
'components' => [
    'user' => [
        ...
        'loginUrl' => 'index.php?r=site/login', // 登录地址
    ],
]
```


`backend/controllers/SiteController.php`

```php
<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class SiteController extends \wsl\rbac\controllers\SiteController
{
    public $publicRoute = [
        'site/login',
    ];

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login'],
                        'allow' => true,
                    ],
                ],
            ],
        ];
    }

    public function actionLogin()
    {
        // 登录代码...
    }

}

```