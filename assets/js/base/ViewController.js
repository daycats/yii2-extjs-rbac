/**
 * Created by shanli on 2015/8/26.
 */
Ext.define('DP.dp.base.ViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.view',

    requires: [
        'Ext.form.action.Action',
        'Ext.window.Window'
    ],

    // 主键id字段
    idProperty: null,
    // 保存url
    saveUrl: '',
    // 更新状态url
    updateStatusUrl: '',
    // 移动垃圾箱url
    deleteUrl: '',
    // 表单提交等待标题
    waitTitle: '数据提交',
    // 表单提交等待信息
    waitMsg: '数据保存中...',
    editWindow: Ext.window.Window,
    gridpanel: null,

    init: function () {
        var view = this.getView();
        if (!this.gridpanel) {
            this.gridpanel = view.down('gridpanel');
        }
        if (!this.gridpanel) {
            this.gridpanel = view.down('treepanel');
        }
        if (!this.gridpanel) {
            this.gridpanel = view;
        }
        if (!this.idProperty) {
            if (this.gridpanel && this.gridpanel.getStore) {
                var store = this.gridpanel.getStore();
                if (store) {
                    this.idProperty = store.getModel().idProperty;
                }
            }
        }
    },

    /**
     * 刷新
     */
    onRefresh: function () {
        if (this.gridpanel && this.gridpanel.getStore) {
            var store = this.gridpanel.getStore();
            if (store) {
                store.reload();
            }
        }
    },

    _addWindow: null,

    /**
     * 添加窗口
     *
     * @returns {*}
     */
    onAdd: function () {
        return this._addWindow = this.showWindow(this._addWindow, '添加' + this.gridpanel.up('panel').getTitle());
    },

    /**
     * 删除
     */
    onDelete: function () {
        var allowDelSelection, ids, selectionData,
            me = this,
            view = this.gridpanel;
        selectionData = view.getSelectionModel().getSelection();
        if (!selectionData.length) {
            me.alert('请选择一条数据');
        } else {
            Ext.Msg.confirm('确认窗口', '是否真的要删除？', function (choice) {
                if (choice === 'yes') {
                    ids = [];
                    allowDelSelection = [];
                    Ext.each(selectionData, function (item) {
                        if (item.get(me.idProperty)) {
                            ids.push(item.get(me.idProperty));
                            allowDelSelection.push(item);
                        }
                    });
                    Ext.MessageBox.wait('数据删除中...', '数据提交');
                    Ext.Ajax.request({
                        url: me.deleteUrl,
                        params: {
                            ids: ids.join(',')
                        },
                        success: function (response) {
                            Ext.MessageBox.hide();
                            var data = Ext.JSON.decode(response.responseText);
                            me.showToast(data.msg, data.success ? '成功' : '失败');
                            if (data.success) {
                                view.getStore().remove(allowDelSelection);
                            }
                            me.onRefresh();
                        },
                        failure: function (response) {
                            Ext.MessageBox.hide();
                            var data = Ext.JSON.decode(response.responseText);
                            me.alert(data.msg);
                        }
                    });
                }
            }, this);
        }
    },

    /**
     * 修改
     */
    _editWindow: null,
    onEdit: function () {
        var me = this,
            selectionData = this.gridpanel.getSelectionModel().getSelection(),
            values = [];
        if (!selectionData[0]) {
            me.alert('请选择一条数据');
            return false;
        }
        for (var key in selectionData[0].data) {
            values.push({
                id: key,
                value: selectionData[0].data[key]
            });
        }
        this._editWindow = this.showWindow(this._editWindow, '修改' + this.gridpanel.up('panel').getTitle());
        if (this._editWindow) {
            var form = this._editWindow.down('#form');
            if (form) {
                form.getForm().setValues(values);
            }
            this.loadFormComboboxData(form);
        }

        return this._editWindow;
    },

    /**
     * 加载表单的下拉框数据
     *
     * @param form
     */
    loadFormComboboxData: function (form) {
        Ext.each(form.items.items, function (item) {
            if ('combobox' == item.xtype) {
                var store = item.getStore();
                if (store) {
                    var params = {},
                        value = item.getValue();
                    if (value) {
                        params[item.valueField] = value;
                        store.load({
                            params: params
                        });
                    }
                }
            }
        });
    },

    /**
     * 启用
     */
    onStart: function () {
        this.updateStatus(1);
    },

    /**
     * 禁用
     */
    onDisable: function () {
        this.updateStatus(0);
    },

    /**
     * 树形展开
     *
     * @returns {*}
     */
    onExpand: function() {
        this.gridpanel.expandAll();
    },

    /**
     * 树形收起
     */
    onCollapse: function() {
        this.gridpanel.collapseAll();
    },

    /**
     * 更新状态
     * @param {Number} status 状态
     */
    updateStatus: function (status) {
        var selectionData,
            ids = [],
            me = this;
        selectionData = this.gridpanel.getSelectionModel().getSelection();

        if (!selectionData.length) {
            me.alert('请选择一条数据');
        } else {
            Ext.each(selectionData, function (item) {
                ids.push(item.get(me.idProperty));
            });
            Ext.MessageBox.wait('数据保存中...', '数据提交');
            Ext.Ajax.request({
                url: me.updateStatusUrl,
                params: {
                    ids: ids.join(','),
                    status: status
                },
                success: function (response) {
                    try {
                        Ext.Msg.hide();
                        var data = Ext.JSON.decode(response.responseText);
                        me.showToast(data.msg, data.success ? '成功' : '失败');
                        me.onRefresh();
                    } catch (e) {
                        me.alert(e);
                    }
                },
                failure: function (response) {
                    try {
                        var data = Ext.JSON.decode(response.responseText);
                        me.alert(data.msg);
                    } catch (e) {
                        me.alert(e);
                    }
                }
            });
        }
    },

    /**
     * 列表选择事件改变按钮状态
     */
    onSelectionchange: function (selected) {
        var view = this.gridpanel,
            selectionData = selected.getSelection(),
            edit = view.down('#edit'),
            del = view.down('#delete'),
            start = view.down('#start'),
            stop = view.down('#stop');
        if (edit) {
            edit.setDisabled(selectionData.length !== 1);
        }
        if (del) {
            del.setDisabled(selectionData.length === 0);
        }
        if (start) {
            start.setDisabled(selectionData.length === 0);
        }
        if (stop) {
            stop.setDisabled(selectionData.length === 0);
        }
    },

    /**
     * 表格双击事件
     */
    onItemdblclick: function () {
        this.onEdit();
    },

    /**
     * 表单取消事件
     *
     * @param view
     * @param e
     * @param eOpts
     */
    onFormCancel: function (view, e, eOpts) {
        view.up('form').getForm().reset();
        view.up('window').hide();
    },

    /**
     * 提交
     *
     * @param view
     * @param e
     * @param eOpts
     */
    onFormSubmit: function (view, e, eOpts) {
        var form,
            i = 0;
        do {
            i++;
            if (i > 10) {
                break;
            }
            form = view.up('form', i);
        } while(!form);

        if (form) {
            this.submit(form.getForm());
        }
    },

    /**
     * 提交成功事件
     */
    onSubmitSuccess: function (form, action) {},
    /**
     * 提交失败事件
     */
    onSubmitFailure: function (form, action) {},

    submit: function (form, params) {
        var me = this;
        if (form.isValid()) {
            form.submit({
                url: me.saveUrl,
                waitMsgTarget: true,
                waitTitle: me.waitTitle,
                waitMsg: me.waitMsg,
                submitEmptyText: false,
                params: params,
                success: function (form, action) {
                    try {
                        me.showToast(action.result.msg, '成功');
                        me.onRefresh();
                        if ('1' == getConfig('system.window.saveClose')) {
                            form.reset();
                            form.owner.ownerCt.hide();
                        }
                    } catch (e) {
                        Ext.Msg.show({
                            title: '数据解析失败',
                            msg: e,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.YES
                        });
                    }
                    if (Ext.isFunction(me.onSubmitSuccess)) {
                        me.onSubmitSuccess(form, action);
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
                    if (Ext.isFunction(me.onSubmitFailure)) {
                        me.onSubmitFailure(form, action);
                    }
                }
            });
        }
    },

    /**
     * 表单添加回车事件
     *
     * @param form
     * @param callback
     */
    addFormEnterEvent: function (form, callback) {
        var me = this;
        if (form.items) {
            Ext.each(form.items.items, function (item) {
                var xtype = item.xtype;
                if ('textfield' == xtype || 'datefield' == xtype || 'numberfield' == xtype || 'timefield' == xtype || 'spinnerfield' == xtype) {
                    item.addListener('specialkey', function (field, e) {
                        if (13 === e.keyCode) {
                            callback(form);
                        }
                    });
                }
                me.addFormEnterEvent(item, callback);
            });
        }
    },

    showWindow: function (win, title) {
        var me = this;
        if (me.editWindow) {
            if (!win || 'destroy' == win.closeAction) {
                if (win && 'destroy' == win.closeAction) {
                    win.close();
                }
                win = Ext.create(me.editWindow);
                me.getView().add(win);
                me.addFormEnterEvent(win.down('form'), function (form) {
                    me.submit(form);
                });
            }
            if ('window' != win.xtype) {
                me.alert('属性必须是扩展extend: Ext.window.Window或子类 当前xtype为: ' + win.xtype);

                return null;
            }
            win.setTitle(title);
            win.show();

            return win;
        } else {
            return null;
        }
    },

    showToast: function (content, title) {
        this.getView().add(Ext.toast({
            title: title,
            html: content,
            closable: true,
            align: 't',
            slideInDuration: 400,
            minWidth: 400
        }));
    },

    alert: function (msg, title) {
        this.getView().add(Ext.Msg.show({
            title: title ? title : '系统提示',
            msg: msg,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.YES
        }));
    }
});