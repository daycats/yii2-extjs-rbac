/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.view.system.menuurllink.MenuUrlLinkFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'Ext.form.Panel',
        'Ext.form.RadioGroup',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Hidden',
        'Ext.layout.container.VBox',
        'DP.dp.store.admin.MenuUrlAll'
    ],

    width: 400,
    minWidth: 300,
    defaultFocus: 'url_id',

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
            name: 'link_id'
        }, {
            xtype: 'hidden',
            name: 'menu_id'
        }, {
            xtype: 'combobox',
            fieldLabel: '关联URL',
            displayField: 'name',
            valueField: 'url_id',
            name: 'url_id',
            itemId: 'url_id',
            queryMode: 'local',
            store: {
                type: 'menu-url-all'
            },
            tpl: new Ext.XTemplate('<tpl for="."><div class="x-boundlist-item" >{name}_{url_id}<br>alias: {alias}<br>route: {route}</div></tpl>'),
            allowBlank: false,
            emptyText: '请选择'
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