/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.view.system.menuurl.MenuUrl', {
    extend: 'DP.dp.component.container.Container',
    xtype: 'menuurl',

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
        'DP.dp.store.admin.MenuUrl',
        'DP.dp.view.system.menuurl.MenuUrlController',
        'DP.dp.view.system.menuurl.MenuUrlModel'
    ],

    viewModel: {
        type: 'menuurl'
    },

    controller: 'menuurl',

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
            }, '-', {
                xtype: 'gridsimpleform',
                layout: 'hbox',
                items: [{
                    xtype: 'textfield',
                    fieldLabel: '名称',
                    labelWidth: 50,
                    name: 'name',
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
                        fieldLabel: '名称',
                        name: 'name'
                    }, {
                        fieldLabel: '别名',
                        name: 'alias'
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
                    text: 'URL ID',
                    dataIndex: 'url_id'
                },
                {
                    xtype: 'textcolumn',
                    text: '名称',
                    dataIndex: 'name',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: '别名',
                    dataIndex: 'alias',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: '路由',
                    dataIndex: 'route',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: '请求方式',
                    dataIndex: 'method',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: '主机地址',
                    dataIndex: 'host',
                    flex: true
                },
                {
                    xtype: 'textcolumn',
                    text: '备注',
                    dataIndex: 'note',
                    flex: true
                },
                {
                    xtype: 'widgetcolumn',
                    text: '规则管理',
                    widget: {
                        xtype: 'button',
                        itemId: 'urlManager',
                        text: '管理',
                        handler: 'onClickRuleManager'
                    }
                },
                {
                    xtype: 'statuscolumn',
                    text: '启用规则',
                    dataIndex: 'enable_rule'
                },
                {
                    xtype: 'statuscolumn',
                    text: '状态',
                    dataIndex: 'status'
                }
            ]
        }
    ],

    initComponent: function () {
        this.items[0].store = Ext.create('DP.dp.store.admin.MenuUrl');
        this.callParent(arguments);
    }
});