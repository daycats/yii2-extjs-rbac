/**
 * Created by shanli on 2015/9/2.
 */
Ext.define('DP.dp.base.FormController', {
    extend: 'Ext.app.ViewController',

    requires: [
        'Ext.form.action.Action'
    ],

    // 保存url
    saveUrl: null,
    // 数据url
    dataUrl: null,

    /**
     * 数据加载
     *
     * @param view
     * @param eOpts
     */
    onAfterrender: function (view, eOpts) {
        var me = this;
        me.loadData();
        me.addFormEnterEvent(view, view, function (form) {
            me.submit(form);
        });
    },


    /**
     * 表单添加回车事件
     *
     * @param form
     * @param comp
     * @param callback
     */
    addFormEnterEvent: function (form, comp, callback) {
        var me = this;
        Ext.each(form.getForm().getFields().items, function (item) {
            var xtype = item.xtype;
            if ('textfield' == xtype || 'datefield' == xtype || 'numberfield' == xtype || 'timefield' == xtype || 'spinnerfield' == xtype) {
                item.addListener('specialkey', function (field, e) {
                    if (13 === e.keyCode) {
                        callback(form);
                    }
                });
            }
        });
        //var me = this;
        //if (comp.items) {
        //    Ext.each(comp.items.items, function (item) {
        //        console.log(item);
        //        var xtype = item.xtype;
        //        if ('textfield' == xtype || 'datefield' == xtype || 'numberfield' == xtype || 'timefield' == xtype || 'spinnerfield' == xtype) {
        //            item.addListener('specialkey', function (field, e) {
        //                if (13 === e.keyCode) {
        //                    callback(form);
        //                }
        //            });
        //        }
        //        me.addFormEnterEvent(form, item, callback);
        //    });
        //}
    },

    /**
     * 加载数据
     */
    loadData: function () {
        var me = this,
            view = me.getView();
        if (me.dataUrl) {
            view.mask('数据加载中...');
            Ext.Ajax.request({
                url: me.dataUrl,
                success: function (response) {
                    view.unmask();
                    var data = Ext.JSON.decode(response.responseText);
                    if (data) {
                        view.getForm().setValues(data.data);
                        Ext.each(view.getForm().getFields().items, function (item) {
                            for (var k in data.data) {
                                if (item.name == k) {
                                    item.originalValue = data.data[k];
                                    return ;
                                }
                            }
                        });
                    }
                },
                failure: function (response) {
                    view.unmask();
                    var data = Ext.JSON.decode(response.responseText);
                    me.showToast(data.msg, '失败');
                }
            });
        } else {
            me.alert('请设置属性dataUrl');
        }
    },

    /**
     * 表单重置
     *
     * @param view
     * @param e
     * @param eOpts
     */
    onFormReset: function (view, e, eOpts) {
        view.up('form').getForm().reset();
    },

    /**
     * 表单提交事件监听
     *
     * @param view
     * @param e
     * @param eOpts
     */
    onFormSubmit: function (view, e, eOpts) {
        this.submit(view.up('form'));
    },

    /**
     * 表单提交
     *
     * @param form
     */
    submit: function (form) {
        var me = this;
        if (me.saveUrl) {
            if (form.isValid()) {
                form.submit({
                    url: me.saveUrl,
                    waitMsgTarget: true,
                    waitTitle: '数据提交',
                    waitMsg: '数据保存中...',
                    submitEmptyText: false,
                    success: function (form, action) {
                        try {
                            me.showToast(action.result.msg, '成功');
                            me.loadData();
                        } catch (e) {
                            console.log(e);
                            Ext.Msg.show({
                                title: '数据解析失败',
                                msg: e,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.YES
                            });
                        }
                    },
                    failure: function (form, action) {
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
                    }
                });
            }
        } else {
            me.alert('请设置属性saveUrl');
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
    }

});