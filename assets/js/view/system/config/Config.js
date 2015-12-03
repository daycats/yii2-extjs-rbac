/**
 * Created by shanli on 2015/9/2.
 */
Ext.define('DP.dp.view.system.config.Config', {
    extend: 'DP.dp.base.form.Panel',

    requires: [
        'DP.dp.view.system.config.ConfigController',
        'DP.dp.view.system.config.ConfigModel',
        'Ext.form.FieldSet',
        'Ext.form.RadioGroup',
        'Ext.form.field.Checkbox',
        'Ext.form.field.Number',
        'Ext.form.field.Text'
    ],

    viewModel: {
        type: 'config'
    },

    controller: 'config',


    defaults: {
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
        defaults: {
            defaults: {
                msgTarget: 'side',
                labelWidth: 50
            }
        }
    },

    items: [{
        xtype: 'fieldset',
        title: '系统设置',
        padding: 10,
        items: [{
            xtype: 'textfield',
            fieldLabel: '系统名称',
            name: 'config[system.name]',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '加载提示语',
            name: 'config[system.loading_text]',
            allowBlank: false
        }, {
            xtype: 'textfield',
            fieldLabel: '版本号',
            name: 'config[system.version]',
            allowBlank: false
        }]
    }, {
        xtype: 'fieldset',
        title: '表格设置',
        padding: 10,
        items: [{
            xtype: 'numberfield',
            fieldLabel: '默认分页条数',
            name: 'config[system.limit]',
            minValue: 0,
            allowDecimals:false,
            emptyText: '默认25条'
        }, {
            xtype: 'textfield',
            fieldLabel: '默认分页条数',
            name: 'config[system.limitData]',
            emptyText: '多个用英文逗号隔开'
        }]
    }, {
        xtype: 'fieldset',
        title: '菜单设置',
        padding: 10,
        items: [{
            xtype: 'numberfield',
            fieldLabel: '宽度',
            name: 'config[system.menu.width]',
            minValue: 0,
            allowDecimals:false,
            emptyText: '默认250'
        }, {
            xtype: 'numberfield',
            fieldLabel: '最小宽度',
            name: 'config[system.menu.minWidth]',
            minValue: 0,
            allowDecimals:false,
            emptyText: '默认100'
        }, {
            fieldLabel: '位置',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'config[system.menu.region]'
            },
            items: [{
                boxLabel: '左',
                inputValue: 'west',
                checked: true
            }, {
                boxLabel: '右',
                inputValue: 'east'
            }]
        }]
    }, {
        xtype: 'fieldset',
        title: '窗口设置',
        padding: 10,
        items: [{
            fieldLabel: '阴影',
            xtype: 'radiogroup',
            columns: 2,
            vertical: true,
            defaults: {
                name: 'config[system.window.shadow]'
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
            xtype: 'checkbox',
            fieldLabel: '保存后关闭窗口',
            name: 'config[system.window.saveClose]',
            inputValue: 1,
            uncheckedValue: 0
        }]
    }]
});