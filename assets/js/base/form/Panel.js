/**
 * Created by shanli on 2015/9/2.
 */
Ext.define('DP.dp.base.form.Panel', {
    extend: 'Ext.form.Panel',
    alias: 'widget.base-form-panel',

    requires: [
        'Ext.layout.container.VBox'
    ],

    border: false,
    bodyPadding: 10,
    scrollable: true,

    layout: {
        type: 'vbox',
        align: 'stretch'
    },

    fieldDefaults: {
        msgTarget: 'side',
        labelWidth: 110
    },

    listeners: {
        afterrender: 'onAfterrender'
    },

    buttons: [{
        text: '重置',
        handler: 'onFormReset'
    }, {
        text: '保存',
        handler: 'onFormSubmit'
    }]
});