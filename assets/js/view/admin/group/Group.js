/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.view.admin.group.Group', {
    extend: 'DP.dp.component.container.Container',

    requires: [
        'Ext.button.Button',
        'Ext.form.RadioGroup',
        'Ext.form.field.Text',
        'Ext.layout.container.Form',
        'Ext.layout.container.HBox',
        'Ext.toolbar.Separator',
        'DP.dp.base.grid.Panel',
        'DP.dp.base.grid.column.Text',
        'DP.dp.component.form.GridSimpleForm',
        'DP.dp.component.grid.column.Status',
        'DP.dp.store.admin.Group',
        'DP.dp.view.admin.group.GroupController',
        'DP.dp.view.admin.group.GroupModel'
    ],

    viewModel: {
        type: 'group'
    },

    controller: 'group',

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
                        fieldLabel: '用户组ID',
                        name: 'group_id'
                    }, {
                        fieldLabel: '名称',
                        name: 'name'
                    }, {
                        fieldLabel: '备注',
                        name: 'note'
                    }, {
                        fieldLabel: '系统用户',
                        xtype: 'radiogroup',
                        columns: 3,
                        vertical: true,
                        defaults: {
                            name: 'is_system'
                        },
                        items: [
                            {
                                boxLabel: '全部',
                                inputValue: undefined,
                                checked: true
                            }, {
                                boxLabel: '是',
                                inputValue: 1
                            }, {
                                boxLabel: '否',
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
                    text: '用户组ID',
                    dataIndex: 'group_id'
                },
                {
                    xtype: 'textcolumn',
                    text: '名称',
                    dataIndex: 'name'
                },
                {
                    xtype: 'textcolumn',
                    text: '备注',
                    dataIndex: 'note',
                    flex: true
                },
                {
                    xtype: 'statuscolumn',
                    text: '系统用户组',
                    dataIndex: 'is_system',
                    width: 100
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
        this.items[0].store = Ext.create('DP.dp.store.admin.Group');
        this.callParent(arguments);
    }
});