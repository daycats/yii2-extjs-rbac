/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.view.system.menuurllink.MenuEditFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'Ext.form.Panel',
        'Ext.form.RadioGroup',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Hidden',
        'Ext.form.field.Text',
        'Ext.form.field.TextArea',
        'Ext.layout.container.VBox',
        'DP.dp.store.RequestMethod',
        'DP.dp.view.system.menuurllink.MenuEditController'
    ],

    controller: 'menu-edit',

    width: 400,
    minWidth: 300,
    defaultFocus: 'name',

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
            labelWidth: 60
        },
        items: [{
            xtype: 'hidden',
            name: 'url_id'
        }, {
            xtype: 'hidden',
            name: 'menu_id'
        }, {
            xtype: 'textfield',
            fieldLabel: '名称',
            name: 'name',
            itemId: 'name',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '路由',
            name: 'route',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '别名',
            name: 'alias',
            emptyText: '留空自动解析路由作为别名'
        }, {
            xtype: 'combobox',
            fieldLabel: '请求方式',
            multiSelect: true,
            queryMode: 'local',
            displayField: 'name',
            valueField: 'name',
            name: 'method[]',
            store: {
                type: 'request-method'
            },
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '主机地址',
            name: 'host',
            emptyText: '限制在特定的主机上使用'
        }, {
            xtype: 'textareafield',
            fieldLabel: '备注',
            name: 'note'
        }, {
            fieldLabel: '启用规则',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'enable_rule'
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