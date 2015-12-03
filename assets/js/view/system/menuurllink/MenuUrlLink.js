/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.view.system.menuurllink.MenuUrlLink', {
    extend: 'DP.dp.component.container.Container',
    xtype: 'menuurllink',

    requires: [
        'Ext.button.Button',
        'Ext.form.RadioGroup',
        'Ext.form.field.Text',
        'Ext.grid.column.Widget',
        'Ext.layout.container.Form',
        'Ext.layout.container.HBox',
        'Ext.toolbar.Separator',
        'DP.dp.base.grid.Panel',
        'DP.dp.base.grid.column.Text',
        'DP.dp.component.form.GridSimpleForm',
        'DP.dp.component.grid.column.Status',
        'DP.dp.store.admin.MenuUrlLink',
        'DP.dp.view.system.menuurllink.MenuUrlLinkController',
        'DP.dp.view.system.menuurllink.MenuUrlLinkModel'
    ],

    viewModel: {
        type: 'menuurllink'
    },

    controller: 'menuurllink',

    items: [
        {
            xtype: 'base-gridpanel',
            tbar: [{
                text: '刷新',
                iconCls: 'fa fa-refresh',
                handler: 'onRefresh'
            }, {
                text: '添加',
                iconCls: 'fa fa-plus',
                handler: 'onAdd'
            }, {
                text: '删除',
                iconCls: 'fa fa-trash-o',
                handler: 'onDelete',
                itemId: 'delete',
                disabled: true
            }, {
                text: '修改',
                iconCls: 'fa fa-pencil',
                handler: 'onEdit',
                itemId: 'edit',
                disabled: true
            }, '-', {
                text: '启用',
                iconCls: 'fa fa-play',
                handler: 'onStart',
                itemId: 'start',
                disabled: true
            }, {
                text: '禁用',
                iconCls: 'fa fa-pause',
                handler: 'onDisable',
                itemId: 'stop',
                disabled: true
            }, '-', 'URL:', {
                text: '添加',
                iconCls: 'fa fa-plus',
                handler: 'onAddUrl'
            }, {
                text: '删除',
                iconCls: 'fa fa-trash-o',
                handler: 'onDeleteUrl',
                itemId: 'deleteUrl',
                disabled: true
            }, {
                text: '修改',
                iconCls: 'fa fa-pencil',
                handler: 'onEditUrl',
                itemId: 'editUrl',
                disabled: true
            }, '-', {
                text: '启用',
                iconCls: 'fa fa-play',
                handler: 'onStartUrl',
                itemId: 'startUrl',
                disabled: true
            }, {
                text: '禁用',
                iconCls: 'fa fa-pause',
                handler: 'onDisableUrl',
                itemId: 'stopUrl',
                disabled: true
            }, '-', {
                xtype: 'gridsimpleform',
                layout: 'hbox',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: '路由',
                    labelWidth: 50,
                    name: 'route',
                    margin: '0 10 0 0'
                }, {
                    xtype: 'button',
                    text: '搜索',
                    iconCls: 'fa fa-search',
                    buttonType: 'submit'
                }]
            }, '-', {
                xtype: 'button',
                text: '高级搜索',
                iconCls: 'fa fa-search',
                menu: [{
                    xtype: 'gridsimpleform',
                    defaults: {
                        width: 450,
                        xtype: 'textfield',
                        labelWidth: 50
                    },
                    iconCls: 'fa fa-search',
                    title: '高级搜索',
                    layout: 'form',
                    bodyPadding: 5,
                    defaultType: 'textfield',
                    items: [{
                        fieldLabel: 'URL ID',
                        name: 'url_id'
                    }, {
                        fieldLabel: '路由',
                        name: 'route'
                    }, {
                        fieldLabel: '请求方式',
                        name: 'method'
                    }, {
                        fieldLabel: '主机地址',
                        name: 'host'
                    }, {
                        fieldLabel: '备注',
                        name: 'note'
                    }, {
                        fieldLabel: '启用规则',
                        xtype: 'radiogroup',
                        columns: 3,
                        vertical: true,
                        defaults: {
                            name: 'enable_rule'
                        },
                        items: [
                            {
                                boxLabel: '全部',
                                inputValue: null,
                                checked: true
                            }, {
                                boxLabel: '启用',
                                inputValue: 1
                            }, {
                                boxLabel: '禁用',
                                inputValue: 0
                            }
                        ]
                    }, {
                        fieldLabel: '状态',
                        xtype: 'radiogroup',
                        columns: 3,
                        vertical: true,
                        defaults: {
                            name: 'status'
                        },
                        items: [
                            {
                                boxLabel: '全部',
                                inputValue: null,
                                checked: true
                            }, {
                                boxLabel: '启用',
                                inputValue: 1
                            }, {
                                boxLabel: '禁用',
                                inputValue: 0
                            }
                        ]
                    }],
                    buttons: [{
                        text: '重置',
                        buttonType: 'reset'
                    }, {
                        text: '搜索',
                        buttonType: 'submit'
                    }]
                }]
            }],

            columns: [
                {
                    text: '关联ID',
                    dataIndex: 'link_id'
                },
                {
                    text: 'URL ID',
                    dataIndex: 'url_id',
                    hidden: true
                },
                {
                    text: '菜单ID',
                    dataIndex: 'menu_id',
                    hidden: true
                },
                {
                    xtype: 'statuscolumn',
                    text: '状态',
                    dataIndex: 'status'
                },
                {
                    xtype: 'textcolumn',
                    text: 'URL名称',
                    dataIndex: 'url.name',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: 'URL别名',
                    dataIndex: 'url.alias',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: 'URL路由',
                    dataIndex: 'url.route',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: 'URL请求方式',
                    dataIndex: 'url.method',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: 'URL主机地址',
                    dataIndex: 'url.host',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: 'URL备注',
                    dataIndex: 'url.note',
                    flex: true
                },
                {
                    xtype: 'widgetcolumn',
                    text: 'URL编辑',
                    widget: {
                        xtype: 'button',
                        itemId: 'urlManager',
                        text: '编辑',
                        handler: 'onClickUrlEdit'
                    }
                },
                {
                    xtype: 'widgetcolumn',
                    text: 'URL规则管理',
                    widget: {
                        xtype: 'button',
                        itemId: 'urlManager',
                        text: '管理',
                        handler: 'onClickRuleManager'
                    }
                },
                {
                    xtype: 'statuscolumn',
                    text: 'URL启用规则',
                    dataIndex: 'url.enable_rule',
                    width: 100
                },
                {
                    xtype: 'statuscolumn',
                    text: 'URL状态',
                    dataIndex: 'url.status',
                    width: 100
                }
            ]
        }
    ],

    initComponent: function () {
        var me = this;
        this.items[0].store = Ext.create('DP.dp.store.admin.MenuUrlLink', {
            proxy: {
                extraParams: {
                    menu_id: me.params['menu_id']
                }
            }
        });
        this.callParent(arguments);
    }
});