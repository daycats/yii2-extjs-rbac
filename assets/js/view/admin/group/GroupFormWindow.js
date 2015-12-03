/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.view.admin.group.GroupFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'Ext.form.Panel',
        'Ext.form.RadioGroup',
        'Ext.form.field.Hidden',
        'Ext.form.field.Text',
        'Ext.form.field.TextArea',
        'Ext.layout.container.Column',
        'Ext.layout.container.VBox',
        'Ext.panel.Panel',
        'Ext.tree.Panel',
        'DP.dp.component.form.field.TreeSearch',
        'DP.dp.store.admin.GroupMenu'
    ],

    width: 800,
    minWidth: 300,
    closeAction: 'destroy',
    maximized: true,
    maximizable: false,

    initComponent: function () {
        var store = Ext.create('DP.dp.store.admin.GroupMenu', {
            root: {
                id: 'src',
                expanded: false
            },
            autoLoad: false
        });
        this.items = [{
            xtype: 'form',
            itemId: 'form',
            border: false,
            bodyPadding: 10,
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            fieldDefaults: {
                msgTarget: 'side',
                labelWidth: 80
            },
            items: [{
                xtype: 'hidden',
                name: 'group_id',
                itemId: 'group_id'
            }, {
                xtype: 'textfield',
                fieldLabel: '名称',
                name: 'name',
                itemId: 'name',
                allowBlank: false
            }, {
                xtype: 'textareafield',
                fieldLabel: '备注',
                name: 'note'
            }, {
                fieldLabel: '系统用户组',
                xtype: 'radiogroup',
                columns: 2,
                vertical: true,
                defaults: {
                    name: 'is_system'
                },
                items: [
                    {
                        boxLabel: '是',
                        inputValue: 1
                    }, {
                        boxLabel: '否',
                        inputValue: 0,
                        checked: true
                    }
                ]
            }, {
                fieldLabel: '状态',
                xtype: 'radiogroup',
                columns: 2,
                vertical: true,
                defaults: {
                    name: 'status'
                },
                items: [
                    {
                        boxLabel: '启用',
                        inputValue: 1,
                        checked: true
                    }, {
                        boxLabel: '禁用',
                        inputValue: 0
                    }
                ]
            }, {
                xtype: 'panel',
                bodyCls: 'x-window-body-default',
                border: false,
                layout: 'column',
                items: [
                    {
                        border: false,
                        bodyCls: 'x-window-body-default',
                        width: 80,
                        columnWidth: 0,
                        html: '权限：'
                    }, {
                        xtype: 'treepanel',
                        itemId: 'menu',
                        rootVisible: false,
                        height: '100%',
                        minHeight: 250,
                        columnWidth: 1,
                        store: store,
                        viewConfig: {
                            markDirty: false,
                            enableTextSelection: true,
                            loadMask: true
                        },
                        tbar: [
                            {
                                text: '展开',
                                iconCls: 'fa fa-expand',
                                handler: 'onMenuExpand'
                            }, {
                                text: '收起',
                                iconCls: 'fa fa-compress',
                                handler: 'onMenuCollapse'
                            }, {
                                text: '全选',
                                iconCls: 'fa fa-check-square-o',
                                handler: 'onMenuAllSelection'
                            }, {
                                text: '取消全选',
                                iconCls: 'fa fa-check-square',
                                handler: 'onMenuCancelSelection'
                            }, {
                                text: '刷新',
                                iconCls: 'fa fa-refresh',
                                handler: 'onMenuRrefresh'
                            }
                        ],
                        dockedItems: [{
                            xtype: 'treesearch',
                            store: store
                        }],
                        listeners: {
                            load: 'onLoad',
                            checkchange: 'onCheckchange'
                        }
                    }
                ]
            }],
            buttons: [{
                text: '取消',
                handler: 'onFormCancel'
            }, {
                text: '保存',
                handler: 'onFormSubmit'
            }]
        }];
        this.callParent(arguments);
    }
});