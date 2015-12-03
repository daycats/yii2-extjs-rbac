/**
 * This class is the main view for the application. It is specified in app.js as the
 * "autoCreateViewport" property. That setting automatically applies the "viewport"
 * plugin to promote that instance of this class to the body element.
 */
Ext.define('DP.dp.view.main.Main', {
    extend: 'Ext.container.Container',
    requires: [
        'Ext.container.Container',
        'Ext.layout.container.Border',
        'Ext.layout.container.Fit',
        'Ext.tab.Panel',
        'Ext.toolbar.Fill',
        'Ext.toolbar.Toolbar',
        'Ext.util.Cookies',
        'Ext.ux.TabCloseMenu',
        'Ext.ux.TabReorderer',
        'DP.dp.view.main.MainController',
        'DP.dp.view.main.MainModel',
        'DP.dp.view.navigation.Navigation'
    ],

    xtype: 'app-main',

    controller: 'main',
    viewModel: {
        type: 'main'
    },

    layout: 'fit',

    items: {
        xtype: 'container',
        layout: {
            type: 'border'
        },
        itemId: 'main-body',
        hidden: isGuest,
        items: [
            {
                xtype: 'navigation',
                itemId: 'main-navigation',
                bind: {
                    title: '{name}'
                },
                region: getConfig('system.menu.region'),
                width: getConfig('system.menu.width'),
                minWidth: getConfig('system.menu.minWidth'),
                split: true,
                collapsible: true,
                collapsed: 'true' === Ext.util.Cookies.get('navigation.collapsed'),
                listeners: {
                    collapse: 'onCollapse',
                    expand: 'onExpand'
                }
            },
            {
                region: 'center',
                xtype: 'tabpanel',
                itemId: 'main-tabs',
                activeTab: 0,
                items: [{
                    id: 'tab_0',
                    title: '欢迎使用',
                    iconCls: 'fa fa-home',
                    closable: false,
                    bind: {
                        html: '<div style="font-size: 25px;font-weight: bold;position: absolute;top:0;right:0;bottom:0;left:0;margin:auto;height:10px;text-align: center">欢迎使用{name}管理系统</div>',
                    }
                }],
                plugins: [
                    Ext.create('Ext.ux.TabReorderer'), Ext.create('Ext.ux.TabCloseMenu', {
                        closeTabText: '关闭当前',
                        closeOthersTabsText: '关闭其他',
                        closeAllTabsText: '关闭所有',
                        extraItemsTail: [
                            '-', {
                                text: '可关闭',
                                checked: true,
                                hideOnClick: true,
                                handler: function (item) {
                                    return currentItem.tab.setClosable(item.checked);
                                }
                            }
                        ],
                        listeners: {
                            beforemenu: function (menu, item) {
                                var menuitem;
                                menuitem = menu.child('*[text="可关闭"]');
                                currentItem = item;
                                return menuitem.setChecked(item.closable);
                            }
                        }
                    })
                ]
            },
            {
                region: 'south',
                style: {
                    marginTop: '5px'
                },
                dockedItems: [
                    {
                        xtype: 'toolbar',
                        dock: 'bottom',
                        itemId: 'bbar',
                        items: [
                            {
                                xtype: 'container',
                                bind: {
                                    html: '<span data-title="用户组信息" data-qtip="用户名：{user.username}<br>昵称：{user.nickname}<br>用户组：{user.groupName}">您好: {user.username}({user.nickname})！</span>'
                                }
                            },
                            {
                                xtype: 'container',
                                html: '身份：<span style="color: #00F">超级管理员</span>',
                                bind: {
                                    hidden: '{!user.isSuper}'
                                }
                            },
                            {
                                xtype: 'container',
                                html: '身份：普通管理',
                                bind: {
                                    hidden: '{user.isSuper}'
                                }
                            },
                            '->',
                            {
                                itemId: 'switchTopToolbar',
                                iconCls: (Ext.util.Cookies.get('switchTopToolbar') === 'true' ? 'my_yes' : 'my_no'),
                                text: (Ext.util.Cookies.get('switchTopToolbar') === 'true' ? '开启顶部工具条' : '关闭顶部工具条'),
                                hidden: true
                            },
                            {
                                itemId: 'editPasswd',
                                iconCls: 'fa fa-key',
                                text: '修改密码',
                                hidden: true
                            },
                            {
                                text: '退出',
                                iconCls: 'fa fa-sign-out',
                                handler: 'onLogout'
                            }
                        ]
                    }
                ]
            }
        ]
    }
});
