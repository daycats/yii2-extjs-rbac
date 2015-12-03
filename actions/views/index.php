<?php

/* @var $config array */
/* @var $user array */
/* @var $isGuest boolean */
/* @var $loading_text string */

use \yii\helpers\Html;
?>
<!DOCTYPE HTML>
<html manifest="">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="UTF-8">
    <title><?= Html::encode($this->title); ?></title>
    <script id="microloader" type="text/javascript" src="bootstrap.js"></script>
    <style>
        .main-body {
            background-color: #f1f1f1;
        }

        #loading {
            display: block;
            position: absolute;
            top: 50%;
            right: 0;
            bottom: 0;
            left: 0;
            margin: -15px 0 0 0;
            text-align: center;
            font-size: 25px;
            color: #666;
            text-shadow: 0 1px 1px #999;
        }
    </style>
    <script>
        var namespace = 'DP',
            config = <?= json_encode($config);?>,
            urls = <?= json_encode($urls)?>,
            user = <?= json_encode($user);?>,
            isGuest = <?= $isGuest ? 'true' : 'false';?>;
        /**
         * 获取配置信息
         *
         * @param {string} name 配置名
         * @returns {*}
         */
        function getConfig(name) {
            if ('system.limit' == name) {
                if (config[name]) {
                    config[name] *= 1;
                } else {
                    config[name] = 25;
                }
            } else if ('system.name' == name) {
                if (!config[name]) {
                    config[name] = 'DrupeCms';
                }
            } else if ('system.menu.width' == name) {
                if (!config[name]) {
                    config[name] = 250;
                } else {
                    config[name] *= 1;
                }
            } else if ('system.menu.minWidth' == name) {
                if (!config[name]) {
                    config[name] = 100;
                } else {
                    config[name] *= 1;
                }
            } else if ('system.menu.region' == name) {
                if (!config[name]) {
                    config[name] = 'west';
                } else {
                    if (config[name] != 'west') {
                        config[name] = 'east';
                    }
                }
            } else if ('system.window.saveClose' == name) {
                if (!config[name]) {
                    config[name] = '0';
                } else {
                    if (config[name] != '1') {
                        config[name] = '0';
                    }
                }
            }

            return config[name] ? config[name] : null;
        }
        /**
         * 获取url
         *
         * @param {string} alias 别名
         * @returns {*}
         */
        function getUrl(alias) {
            if (undefined === urls[alias]) {
                Ext.namespace(namespace).getApplication().fireEvent('error', 'URL别名不存在或者权限不足<br>alias:' + alias);
                return undefined;
            } else {
                return urls[alias];
            }
        }
    </script>
</head>
<body class="main-body">

<div id="loading"><?php echo $loading_text ?></div>

</body>
</html>