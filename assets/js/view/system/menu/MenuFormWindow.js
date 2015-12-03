/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.view.system.menu.MenuFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'DP.dp.store.admin.MenuAll',
        'Ext.form.Panel',
        'Ext.form.RadioGroup',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Hidden',
        'Ext.form.field.Number',
        'Ext.form.field.Text',
        'Ext.form.field.TextArea',
        'Ext.layout.container.VBox'
    ],

    width: 500,
    minWidth: 300,
    defaultFocus: 'text',
    reference: 'menu-form-window',

    items: [{
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
            labelWidth: 100
        },
        items: [{
            xtype: 'hidden',
            name: 'menu_id'
        }, {
            xtype: 'combobox',
            fieldLabel: '父级',
            name: 'parent_id',
            itemId: 'parent_id',
            store: {
                type: 'admin-menu-all'
            },
            queryMode: 'local',
            displayField: 'text',
            valueField: 'menu_id',
            tpl: new Ext.XTemplate('<tpl for="."><div class="x-boundlist-item" >{text}_{menu_id}</div></tpl>')
        }, {
            xtype: 'textfield',
            fieldLabel: '名称',
            name: 'origin_text',
            itemId: 'text',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '视图名',
            name: 'view_package'
        }, {
            xtype: 'textfield',
            fieldLabel: '标题',
            name: 'title'
        }, {
            xtype: 'textfield',
            fieldLabel: '跳转URL',
            name: 'url',
            vtype: 'url'
        }, {
            xtype: 'textareafield',
            fieldLabel: '参数',
            name: 'params',
            emptyText: "view获取方法: this.params\nviewController获取方法: this.getView().params"
        }, {
            xtype: 'textareafield',
            fieldLabel: '备注',
            name: 'note'
        }, {
            fieldLabel: '展开',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'is_expand'
            },
            items: [
                {
                    boxLabel: '是',
                    inputValue: 1,
                    checked: true
                }, {
                    boxLabel: '否',
                    inputValue: 0
                }
            ]
        }, {
            fieldLabel: '允许关闭',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'closable'
            },
            items: [
                {
                    boxLabel: '是',
                    inputValue: 1,
                    checked: true
                }, {
                    boxLabel: '否',
                    inputValue: 0
                }
            ]
        }, {
            fieldLabel: '打开URL',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'is_open_url'
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
            fieldLabel: '新窗口',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'is_open_target'
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
            fieldLabel: '每次打开',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'is_every_open'
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
            xtype: 'numberfield',
            fieldLabel: '显示排序号',
            name: 'display_order',
            emptyText: '留空自动靠后，越大越靠后'
        }, {
            fieldLabel: '隐藏',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'is_hide'
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
        }],
        buttons: [{
            text: '取消',
            handler: 'onFormCancel'
        }, {
            text: '保存',
            handler: 'onFormSubmit'
        }]
    }]
});