/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.view.system.menuurlrule.MenuUrlRuleFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'Ext.form.Panel',
        'Ext.form.RadioGroup',
        'Ext.form.field.Hidden',
        'Ext.form.field.Text',
        'Ext.form.field.TextArea',
        'Ext.layout.container.VBox'
    ],

    width: 400,
    minWidth: 300,
    defaultFocus: 'param_name',

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
            name: 'rule_id'
        }, {
            xtype: 'hidden',
            name: 'url_id'
        }, {
            xtype: 'textfield',
            fieldLabel: '参数名',
            name: 'param_name',
            itemId: 'param_name',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '规则',
            name: 'rule',
            emptyText: '支持正则',
            allowBlank: false
        }, {
            xtype: 'textareafield',
            fieldLabel: '备注',
            name: 'note'
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