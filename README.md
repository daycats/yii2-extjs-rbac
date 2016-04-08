Yii2 ExtJs5 RBAC
=========

支持ACL+RBAC

github: https://github.com/myweishanli/yii2-extjs-rbac

[![Latest Stable Version](https://poser.pugx.org/myweishanli/yii2-extjs-rbac/v/stable.png)](https://packagist.org/packages/myweishanli/yii2-extjs-rbac)
[![Total Downloads](https://poser.pugx.org/myweishanli/yii2-extjs-rbac/downloads.png)](https://packagist.org/packages/myweishanli/yii2-extjs-rbac)

> 注: 功能正在开发中...

> 更详细的配置说明文档正在编写中...

> QQ群: 137158108 验证信息: github

> 有任何疑问可以发邮件到 myweishanli@gmail.com

| Web | UI  | Preview  |
|:-------------:|:-------:|:-------:|
|加载页面|加载完成|管理员帐号|
|![加载页面](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/1.png?raw=true)|![加载完成](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/2.png?raw=true)|![管理员帐号](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/3.png?raw=true)|
|高级搜索|编辑帐号|用户组管理|
|![高级搜索](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/4.png?raw=true)|![编辑帐号](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/5.png?raw=true)|![用户组管理](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/6.png?raw=true)|
|编辑用户组管理|菜单管理|菜单管理URL管理|
|![编辑用户组管理](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/7.png?raw=true)|![菜单管理](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/8.png?raw=true)|![菜单管理URL管理](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/9.png?raw=true)|
|编辑菜单管理URL|编辑菜单管理URL规则|系统配置|
|![编辑菜单管理URL](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/10.png?raw=true)|![编辑菜单管理URL规则](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/11.png?raw=true)|![系统配置](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/12.png?raw=true)|

[更多预览图](docs/preview.md)

---
有任何建议或者需求欢迎来反馈 [issues](../../issues)

欢迎点击右上方的 star 收藏

fork 参与开发，欢迎提交 Pull Requests，然后 Pull Request

---

1、安装
------------

安装这个扩展的首选方式是通过 [composer](http://getcomposer.org/download/).

执行命令

```bash
composer global require "fxp/composer-asset-plugin:~1.1.0"
composer require --prefer-dist myweishanli/yii2-extjs-rbac
```
或添加

```json
"myweishanli/yii2-extjs-rbac": "~1.0.0"
```


如果无法通过`composer`安装可以下载离线包 [离线包安装向导](docs/offline-install.md)

2、配置
------------

`@app/config/main.php`

`@app`指你应用的目录 比如你访问的是`backend/web/index.php`那么你就配置`backend/config/main.php`即可
反之你访问的是`frontend/web/index.php`那么你就配置`frontend/config/main.php`即可

高级版是`main.php`

基础版是`web.php`

```php
'modules' => [
    // ...
    'admin' => '\wsl\rbac\modules\admin\Module',
],
'controllerMap' => [
    // ...
    'site' => '\wsl\rbac\controllers\SiteController',
],
'components' => [
    // ...
    'user' => [
        'identityClass' => 'wsl\rbac\models\DpAdminUser',
        // ...
    ],
]
```

配置预览
---------

![](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/config-preview.png?raw=true)

3、导入数据
------------

```bash
yii migrate --migrationPath=@wsl/rbac/migrations
```

完成`配置`和`导入数据`即可访问

> 如果提示目录创建失败请设置`@app/web`为777权限

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

```php
'controllerMap' => [
    'site' => [
        'class' => '\wsl\rbac\controllers\SiteController',
        // 'extJs' => [ // 按需配置,未配置的key会使用默认值
        //     'path' => '/dp/extjs', // ExtJs符号连接路径
        //     'extendPath' => '/dp/extjs-extend', // ExtJs扩展符号连接路径
        //     'appJsPath' => '/app.js', // app.js路径
        //     'bootstrapJsPath' => '/dp/extjs-extend/bootstrap.js', // bootstrap.js路径
        //     'bootstrapJsonPath' => '/dp/extjs-extend/bootstrap.json', // bootstrap.json路径
        //     'bootstrapCssPath' => '/dp/extjs/packages/ext-theme-crisp/build/resources/ext-theme-crisp-all.css', // bootstrap.css路径
        // ],
    ],
    // ...
],
```

> 注: 每次升级记住执行`yii migrate --migrationPath=@wsl/rbac/migrations`


## 捐赠

![微信支付](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/wechat-pay.png?raw=true)

或者

![支付宝支付](https://github.com/myweishanli/yii2-extjs-rbac/blob/master/docs/images/ali-pay.png?raw=true)

手机微信或者支付宝扫描上方二维码可向本项目捐款

所得捐赠将用于改善网站服务器、购买开发/调试设备&工具。

## 示例

> 更多示例正在添加中...

- [编写一个Hello World](docs/example/hello-world/hello-world.md)
- [自定义命名空间](docs/example/custom-namespace/custom-namespace.md)
- [自定义登录页面](docs/example/custom-login-page/custom-login-page.md)

建议反馈: https://www.drupecms.com/blog/yii2-extjs-rbac/article/141

**正在编写**

- 目录结构说明
- 使用已封装CRUD
- 扩展功能

正在开发新功能
------------
- ip访问限制
- 权限设置细化到每个url
- gii代码生成器

更新日志
------------

### Version Dev Master

- 静态文件默认符号连接修改到`assets`目录

### Version 1.0.5 (2016.1.12)

- 修复拖拽菜单排序
- 兼容非web目录访问

### Version 1.0.4 (2015.12.13)

- 修复Ubuntu下通过Windows共享目录无法建立符号连接
- 添加公共路由配置

### Version 1.0.3 (2015.12.08)

- 兼容PHP5.4+
- 新增自定义静态文件路径
- 新增底部工具栏添加debug调试按钮
- 修复高级搜索状态切换bug
