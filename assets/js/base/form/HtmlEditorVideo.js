/**
 * Created by shanli on 2015/9/6.
 */
Ext.namespace('Ext.ux.form.HtmlEditor');
Ext.define('DP.dp.base.form.HtmlEditorVideo', {
    requires: [
        'DP.dp.base.form.HtmlEditorVideoFormWindow'
    ],

    expend: 'Ext.util.Observable',
    init: function (cmp) {
        this.cmp = cmp;
        this.cmp.on('render', this.onRender, this);
    },
    onRender: function () {
        var me = this;
        me.cmp.getToolbar().add({
            iconCls: 'fa fa-video-camera',
            scope: this,
            tooltip: '<b>视频插入</b><br />支持：flash地址、html代码、通用代码',
            handler: function (btn) {
                var editWindow = Ext.create('DP.dp.base.form.HtmlEditorVideoFormWindow', {
                    cmp: me.cmp
                });
                btn.up('htmleditor').add(editWindow);
                editWindow.show();
            }
        });
    }
});