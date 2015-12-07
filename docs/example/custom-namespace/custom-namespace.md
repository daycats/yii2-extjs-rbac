自定义命名空间
=========

配置`app.js`文件路径
------------

> 此处以网站根目录的`app.js`为例

配置`@app/config/main.php`文件

```
'controllerMap' => [
    'site' => [
        'class' => '\wsl\rbac\controllers\SiteController',
        'extJs' => [
            'appPath' => '/app.js',
        ],
    ],
    ...
],
```

`app.js`文件代码

> 此处以命名空间`MDP`为例

```javascript
Ext.Loader.setConfig({
    enable: true,
    paths: {
        'DP': '/dp/extjs-extend',
        'Ext.ux': '/dp/extjs/src/ux'
    }
});
Ext.application({
    name: 'MDP',

    extend: 'DP.Application'
});

```

**目录结构**

![](directory-structure.png)