/**
 * Created by shanli on 2015/9/11.
 */
Ext.define('DP.dp.base.form.HtmlEditorImageFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'Ext.form.Panel',
        'Ext.form.field.File',
        'Ext.layout.container.Form',
        'DP.dp.base.form.HtmlEditorImageFormWindowController'
    ],

    controller: 'html-editor-image-form-window',

    title: '图片上传',
    width: 400,
    resizable: false,
    maximizable: false,

    initComponent: function () {
        var me = this;
        if (!me.fieldName) {
            me.fieldName = 'image_file';
        }
        me.items = [{
            xtype: 'form',
            layout: 'form',
            items: [{
                xtype: 'filefield',
                fieldLabel: '图片',
                name: me.fieldName,
                blankText: '请选择要上传的图片',
                emptyText: '请选择要上传的图片',
                tooltip: '支持HTML5浏览器支持多文件上传'
            }],
            buttons: [{
                text: '取消',
                handler: 'onFormCancel'
            }, {
                text: '上传',
                handler: 'onFormSubmit'
            }]
        }];

        me.callParent(arguments);
    }
});