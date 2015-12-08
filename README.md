Yii2 ExtJs5 RBAC
=========
支持ACL+RBAC

> 注: 功能正在开发中...

> 更详细的配置说明文档正在编写中...

> QQ群: 137158108 验证信息: github

> 有任何疑问可以发邮件到 myweishanli@gmail.com

安装
------------

安装这个扩展的首选方式是通过 [composer](http://getcomposer.org/download/).

执行

```
composer require --prefer-dist myweishanli/yii2-extjs-rbac
```
或添加

```
"myweishanli/yii2-extjs-rbac": "~1.0.0"
```


如果无法通过`composer`安装可以下载离线包 百度网盘 http://pan.baidu.com/s/1bonLAph#path=/yii2-extjs-rbac

安装包下载到项目根目录解压到当前文件夹即可

配置
------------

`@app/config/main.php`

`@app`指你应用的目录 比如你访问的是`backend/web/index.php`那么你就配置`backend/config/main.php`即可
反之你访问的是`frontend/web/index.php`那么你就配置`frontend/config/main.php`即可

高级版是`main.php`

基础版是`web.php`

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

完成`配置`和`导入数据`即可访问

超级管理员帐号和密码
------------

```
username: drupecms
password: drupecms
```

控制器
------------
新建的`Controller`需要继承`\wsl\rbac\base\Controller`

```php
class ExampleController extends \wsl\rbac\base\Controller
{
}
```

自定义配置
------------

```
'controllerMap' => [
    'site' => [
        'class' => '\wsl\rbac\controllers\SiteController',
        'extJs' => [ // 按需配置,未配置的key会使用默认值
            'path' => '/dp/extjs', // ExtJs符号连接路径
            'extendPath' => '/dp/extjs-extend', // ExtJs扩展符号连接路径
            'appJsPath' => '/app.js', // app.js路径
            'bootstrapJsPath' => '/dp/extjs-extend/bootstrap.js', // bootstrap.js路径
            'bootstrapJsonPath' => '/dp/extjs-extend/bootstrap.json', // bootstrap.json路径
            'bootstrapCssPath' => '/dp/extjs/packages/ext-theme-crisp/build/resources/ext-theme-crisp-all.css', // bootstrap.css路径
        ],
    ],
    ...
],
```

[预览图](docs/preview.md)

> 注: 每次升级记住执行`yii migrate --migrationPath=@wsl/rbac/migrations`

示例:
------------
> 更多示例正在添加中...

- [编写一个Hello World](docs/example/hello-world/hello-world.md)
- [自定义命名空间](docs/example/custom-namespace/custom-namespace.md)

**正在编写**

- 目录结构说明
- 使用已封装CRUD
- 扩展功能

更新日志
------------

### Version 1.0.3 (2015.12.08)

- 兼容PHP5.4+
- 新增自定义静态文件路径
- 新增底部工具栏添加debug调试按钮
- 修复高级搜索状态切换bug



