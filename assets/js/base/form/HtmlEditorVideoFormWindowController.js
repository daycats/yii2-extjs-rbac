/**
 * Created by shanli on 2015/9/11.
 */
Ext.define('DP.dp.base.form.HtmlEditorVideoFormWindowController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.html-editor-video-form-window',

    saveUrl: '/article/article/upload-image',

    onSubmitSuccess: function (form, action) {
        var me = this,
            view = me.getView(),
            cmp = me.getView().cmp;
        if (action.result['urls']) {
            Ext.each(action.result['urls'], function (url) {
                cmp.insertAtCursor('<img src="' + url + '"/>');
            });
        }
        view.close();
    },

    onFormSubmit: function (view, e, eOpts) {
        var me = this,
            form,
            i = 0;
        do {
            i++;
            if (i > 10) {
                break;
            }
            form = view.up('form', i);
        } while (!form);

        var values = form.getValues(),
            align = form.down('#align').getValue();

        var url = values.url,
            width = values.width,
            height = values.height,
            alignStyle = 'float:none';


        if (url && url.match(/\.swf$/)) {
            if ('left' == align) {
                alignStyle = 'float:left';
            } else if ('center' == align) {
                alignStyle = 'margin: 0 auto;display: block';
            } else if ('right' == align) {
                alignStyle = 'float:right';
            }

            var videoCode = '<embed type="application/x-shockwave-flash" class="edui-faked-video" pluginspage="http://www.macromedia.com/go/getflashplayer" src="' + url + '" width="' + width + '" height="' + height + '" style="' + alignStyle + '" wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true"/>';
            this.getView().cmp.insertAtCursor(videoCode);
            this.getView().close();
        } else {
            this.alert('视频地址无效');
        }
    },

    /**
     * 视频地址改变事件
     */
    onChange: function (field, newValue, oldValue, eOpts) {
        var me = this;
        if (newValue && newValue.match(/\.swf$/)) {
            var videoCode = '<embed type="application/x-shockwave-flash" class="edui-faked-video" pluginspage="http://www.macromedia.com/go/getflashplayer" src="' + newValue + '" width="420" height="280" style="float:none" wmode="transparent" play="true" loop="false" menu="false" allowscriptaccess="never" allowfullscreen="true"/>';
            me.getView().getViewModel().set('videoCode', videoCode);
        }
    }
});