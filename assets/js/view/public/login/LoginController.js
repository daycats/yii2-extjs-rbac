/**
 * Created by shanli on 2015/9/3.
 */
Ext.define('DP.dp.view.public.login.LoginController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.login',

    requires: [
        'Ext.tip.QuickTipManager'
    ],

    saveUrl: getUrl('admin.public.login'),
    waitTitle: '登录系统',
    waitMsg: '登录中...',

    /**
     * 数据加载
     *
     * @param view
     * @param eOpts
     */
    onAfterrender: function (view, eOpts) {
        var me = this;
        me.addFormEnterEvent(view, function (form) {
            me.submit(form);
        });
    },

    onSubmitSuccess: function (form, action) {
        var me = this,
            view = me.getView(),
            app = Ext.namespace('DP').getApplication(),
            viewModel = app.getMainView().getViewModel();
        try {
            if (action.result.success) {
                // 登录成功
                view.close();
                if (action.result.data && action.result.data.user) {
                    viewModel.setData({
                        user: action.result.data.user
                    });
                }
                var container = Ext.ComponentQuery.query('container#main-body')[0],
                    treepanel = Ext.ComponentQuery.query('navigation#main-navigation treepanel')[0];
                if (container) {
                    window['needActiveTab'] = true;
                    container.show();
                    if (treepanel.getRootNode().get('expanded')) {
                        treepanel.getStore().reload();
                    } else {
                        treepanel.expandAll();
                    }
                }
            } else {
                // 登录失败
                me.alert(action.result.msg);
            }
        } catch (e) {
            Ext.Msg.show({
                title: '数据解析失败',
                msg: e,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.YES
            });
        }
    },

    onSubmitFailure: function (form, action) {
        switch (action.failureType) {
            case Ext.form.action.Action.CLIENT_INVALID:
                Ext.Msg.alert('失败', '表单字段有非法值');
                break;
            case Ext.form.action.Action.CONNECT_FAILURE:
                Ext.Msg.alert('失败', '提交失败');
                break;
            case Ext.form.action.Action.SERVER_INVALID:
                Ext.Msg.alert('失败', action.result.msg);
        }
    },

    showToast: function (content, title) {
        Ext.toast({
            title: title,
            html: content,
            closable: true,
            align: 't',
            slideInDuration: 400,
            minWidth: 400,
            scope: this.getView()
        });
    },

    alert: function (msg, title) {
        Ext.Msg.show({
            title: title ? title : '系统提示',
            msg: msg,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.YES
        });
    },

    checkboxOnAfterrender: function (me) {
        Ext.tip.QuickTipManager.register({
            target: me.getId(),
            title: '安全提示',
            text: '为了您的信息安全，请不要在网吧或公用电脑上使用此功能！',
            dismissDelay: 10000 // Hide after 10 seconds hover
        });
    },

    checkboxOnDestroy: function (me) {
        Ext.tip.QuickTipManager.unregister(me.getId());
    }
});