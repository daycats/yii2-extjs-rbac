自定义命名空间
=========

配置`app.js`文件路径
------------

`@app/config/main.php`

`@app`指你应用的目录 比如你访问的是`backend/web/index.php`那么你就配置`backend/config/main.php`即可
反之你访问的是`frontend/web/index.php`那么你就配置`frontend/config/main.php`即可

高级版是`main.php`

基础版是`web.php`

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

`app.js`文件代码

> 此处以命名空间`MDP`为例

```javascript
Ext.Loader.setConfig({
    enable: true,
    paths: {
        'DP': extJsConfig['extendPath']
    }
});
Ext.application({
    name: 'MDP',

    extend: 'DP.Application'
});

```

**目录结构**

![](directory-structure.png)