Yii2 ExtJs5 RBAC
=========

> 正在完善中...
> 还有些问题请不要使用

安装步骤

```
    ...
    "require": {
        ...
        "myweishanli/yii2-extjs-rbac": "dev-master"
    }
    ...
```

然后
```
$ composer install
```

Yii`@app/config/main.php`配置
```php
    'modules' => [
        'admin' => '\wsl\rbac\modules\admin\Module',
        ...
    ],
```

```php
    'controllerMap' => [
        'site' => '\wsl\rbac\controllers\SiteController',
        ...
    ],
```

```php
    'components' => [
        'user' => [
            'identityClass' => 'wsl\rbac\models\DpAdminUser',
            ...
        ],
        ...
    ]
```

执行数据迁移脚本
```
$ yii migrate --migrationPath=@wsl/rbac/migrations
```