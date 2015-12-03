/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.view.system.menuurllink.MenuUrlLinkController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.menuurllink',

    requires: [
        'DP.dp.base.window.Window',
        'DP.dp.view.system.menuurllink.MenuEditFormWindow',
        'DP.dp.view.system.menuurllink.MenuUrlLinkFormWindow',
        'DP.dp.view.system.menuurlrule.MenuUrlRule'
    ],

    saveUrl: getUrl('admin.menu-url-link.save'),
    updateStatusUrl: getUrl('admin.menu-url-link.update-status'),
    deleteUrl: getUrl('admin.menu-url-link.del'),

    init: function () {
        this.editWindow = DP.dp.view.system.menuurllink.MenuUrlLinkFormWindow;
        this.callParent(arguments);
    },

    onAdd: function () {
        var me = this,
            formWindow = this.callParent(arguments);
        formWindow.down('form').getForm().setValues({
            menu_id: me.getView().params['menu_id']
        });
    },

    urlIdProperty: 'url.url_id',
    urlSaveUrl: getUrl('admin.menu-url.save'),
    urlUpdateStatusUrl: getUrl('admin.menu-url.update-status'),
    urlDeleteUrl: getUrl('admin.menu-url.del'),

    /**
     * 规则管理点击事件
     */
    onClickRuleManager: function (btn) {
        var me = this,
            rec = btn.getWidgetRecord();
        if (rec.get('url.url_id')) {
            var ruleWindow = Ext.create('DP.dp.base.window.Window', {
                title: 'URL规则管理 - ' + rec.get('url.name'),
                width: 900,
                height: 500,
                closeAction: 'destory',
                items: {
                    xtype: 'menuurlrule',
                    params: {
                        url_id: rec.get('url_id')
                    }
                }
            });
            me.getView().add(ruleWindow);
            ruleWindow.show();
        } else {
            this.alert('URL不存在');
        }
    },

    /**
     * URL添加事件
     */
    onAddUrl: function (btn) {
        var me = this,
            view = me.getView(),
            values = {},
            urlEditWindow = Ext.create('DP.dp.view.system.menuurllink.MenuEditFormWindow', {
                title: 'URL添加',
                scope: me.getView()
            });
        urlEditWindow.down('form').getForm().setValues({
            menu_id: view.params['menu_id']
        });
        view.add(urlEditWindow);
        urlEditWindow.show();
    },

    /**
     * URL删除事件
     */
    onDeleteUrl: function () {
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
                        if (item.get(me.urlIdProperty)) {
                            ids.push(item.get(me.urlIdProperty));
                            allowDelSelection.push(item);
                        }
                    });
                    Ext.MessageBox.wait('数据删除中...', '数据提交');
                    Ext.Ajax.request({
                        url: me.urlDeleteUrl,
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
     * URL编辑点击事件
     */
    onClickUrlEdit: function (btn) {
        this.editUrl(btn.getWidgetRecord());
    },

    /**
     * URL编辑事件
     */
    onEditUrl: function () {
        var me = this,
        selectionData = this.gridpanel.getSelectionModel().getSelection(),
        values = [];
        if (!selectionData[0]) {
            me.alert('请选择一条数据');
            return false;
        }
        var editWindow = this.editUrl(selectionData[0]);
    },

    /**
     * 编辑URL
     *
     * @param record
     */
    editUrl: function (record) {
        if (record.get('url.url_id')) {
            var me = this,
                values = {},
                urlEditWindow = Ext.create('DP.dp.view.system.menuurllink.MenuEditFormWindow', {
                    title: 'URL编辑 - ' + record.get('url.name'),
                    scope: me.getView()
                });
            for (var key in record.data) {
                if (key.indexOf('.') != -1) {
                    values[key.split('.')[1]] = record.data[key];
                }
            }
            urlEditWindow.down('form').getForm().setValues(values);
            this.getView().add(urlEditWindow);
            urlEditWindow.show();
            // 多选框赋值
            urlEditWindow.down('form').getForm().setValues({
                'method[]': record.get('url.method').split(',')
            });
            return urlEditWindow;
        } else {
            this.alert('URL不存在');
            return false;
        }
    },

    /**
     * URL启用事件
     */
    onStartUrl: function () {
        this.urlUpdateStatus(1);
    },

    /**
     * URL禁用事件
     */
    onDisableUrl: function () {
        this.urlUpdateStatus(0);
    },

    urlUpdateStatus: function (status) {
        var selectionData,
            ids = [],
            me = this;
        selectionData = this.gridpanel.getSelectionModel().getSelection();

        if (!selectionData.length) {
            me.alert('请选择一条数据');
        } else {
            Ext.each(selectionData, function (item) {
                ids.push(item.get(me.urlIdProperty));
            });
            Ext.MessageBox.wait('数据保存中...', '数据提交');
            Ext.Ajax.request({
                url: me.urlUpdateStatusUrl,
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
        var selectionData,
            view = this.gridpanel;
        selectionData = selected.getSelection();
        view.down('#editUrl').setDisabled(selectionData.length !== 1);
        view.down('#deleteUrl').setDisabled(selectionData.length === 0);
        view.down('#startUrl').setDisabled(selectionData.length === 0);
        view.down('#stopUrl').setDisabled(selectionData.length === 0);
        this.callParent(arguments);
    }

});