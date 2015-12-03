/**
 * Created by shanli on 2015/9/3.
 */
Ext.define('DP.dp.view.public.login.Login', {
    extend: 'Ext.window.Window',
    xtype: 'login',
    reference: 'login',

    requires: [
        'Ext.form.field.Checkbox',
        'Ext.form.field.Text',
        'Ext.layout.container.VBox',
        'DP.dp.base.form.Panel',
        'DP.dp.view.public.login.LoginController',
        'DP.dp.view.public.login.LoginModel'
    ],

    viewModel: {
        type: 'login'
    },

    controller: 'login',

    bind: {
        title: '登录 - {name}'
    },

    width: 300,
    modal: true,
    closable: false,
    constrain: true,
    defaultFocus: 'username',

    items: {
        xtype: 'base-form-panel',
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

        items: [
            {
                xtype: 'textfield',
                fieldLabel: '用户名',
                name: 'username',
                itemId: 'username',
                emptyText: '请输入用户名',
                allowBlank: false
            },
            {
                xtype: 'textfield',
                fieldLabel: '密码',
                name: 'password',
                allowBlank: false,
                emptyText: '请输入密码',
                inputType: 'password'
            },
            {
                xtype: 'checkbox',
                fieldLabel: '记住登录',
                inputValue: 1,
                name: 'rememberMe',
                qtip: 'Text next to the box',
                tooltip: '为了您的信息安全，请不要在网吧或公用电脑上使用此功能！',
                listeners: {
                    afterrender: 'checkboxOnAfterrender',
                    destroy: 'checkboxOnDestroy'
                }
            }
        ],
        buttons: [
            {
                text: '登录',
                handler: 'onFormSubmit'
            }
        ]
    }

});