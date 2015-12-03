Yii2 ExtJs5 RBAC
=========

> 正在完善中...
> 还有些问题请不要使用

安装
------------

安装这个扩展的首选方式是通过 [composer](http://getcomposer.org/download/).

执行

```
composer require myweishanli/yii2-extjs-rbac:dev-master
```
或添加
```
"myweishanli/yii2-extjs-rbac": "dev-master"
```

配置
------------

`@app/config/main.php`
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


导入数据
------------

```
yii migrate --migrationPath=@wsl/rbac/migrations
```

控制器
------------
新建的`Controller`需要继承`\wsl\rbac\base\Controller`

```php
class ExampleController extends \wsl\rbac\base\Controller
{
}
```