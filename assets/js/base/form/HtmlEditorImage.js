/**
 * Created by shanli on 2015/9/6.
 */
Ext.namespace('Ext.ux.form.HtmlEditor');
Ext.define('DP.dp.base.form.HtmlEditorImage', {
    requires: [
        'DP.dp.base.form.HtmlEditorImageFormWindow'
    ],

    expend: 'Ext.util.Observable',

    constructor: function(config) {
        this.config = {};
        if (config) {
            for (var key in config) {
                this.config[key] = config[key];
            }
        }

        return this.callParent(arguments);
    },

    init: function (cmp) {
        this.cmp = cmp;
        this.cmp.on('render', this.onRender, this);
    },

    onRender: function () {
        var me = this;
        me.config['cmp'] = me.cmp;
        me.cmp.getToolbar().add({
            iconCls: 'fa fa-picture-o',
            scope: this,
            tooltip: '<b>图片上传</b><br />使用支持HTML5浏览器可以多文件上传',
            handler: function (btn) {
                var editWindow = Ext.create('DP.dp.base.form.HtmlEditorImageFormWindow', me.config);
                btn.up('htmleditor').add(editWindow);
                editWindow.show();
            }
        });
    }
});