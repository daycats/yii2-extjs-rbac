/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.view.system.menu.MenuController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.menu',

    requires: [
        'DP.dp.base.window.Window',
        'DP.dp.view.system.menu.MenuFormWindow',
        'DP.dp.view.system.menuurllink.MenuUrlLink'
    ],

    idProperty: 'menu_id',
    saveUrl: getUrl('admin.menu.save'),
    updateStatusUrl: getUrl('admin.menu.update-status'),
    deleteUrl: getUrl('admin.menu.del'),

    init: function () {
        this.editWindow = DP.dp.view.system.menu.MenuFormWindow;
        this.callParent(arguments);
    },

    onAdd: function () {
        this.callParent(arguments);
        var selectionData = this.gridpanel.getSelectionModel().getSelection();
        if (1 == selectionData.length) {
            this._addWindow.down('#parent_id').setValue(selectionData[0].get('menu_id'));
        }
    },

    onItemdblclick: function () {
        var selectionData = this.gridpanel.getSelectionModel().getSelection();
        if (selectionData[0] && selectionData[0].get('leaf')) {
            this.onEdit();
        }
    },

    onLoad: function () {
        var editWindow = this.lookupReference('menu-form-window');
        if (editWindow) {
            editWindow.down('#parent_id').getStore().reload();
        }
        var count = this.gridpanel.getStore().getData().getCount();
        if (!count) {
            count = this.gridpanel.getStore().lastRemoveIndexPlusOne;
        }
        this.getView().getViewModel().set('itemCount', count);
    },

    onSelectionchange: function (selected) {
        this.getView().getViewModel().set('selectItemCount', selected.getSelection().length);
        this.callParent(arguments);
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
     * URL管理点击事件
     *
     * @param btn
     */
    onClickUrlManager: function (btn) {
        var rec = btn.getWidgetRecord(),
            urlWindow = Ext.create('DP.dp.base.window.Window', {
                title: 'URL管理 - ' + rec.get('text'),
                width: 900,
                height: 500,
                maximized: true,
                closeAction: 'destory',
                items: {
                    xtype: 'menuurllink',
                    params: {
                        menu_id: rec.get('menu_id')
                    }
                }
            });
        this.getView().add(urlWindow);
        urlWindow.show();
    },

    /**
     * 菜单拖拽排序
     *
     * @param node
     * @param data
     * @param overModel
     * @param dragPosition
     * @param eOpts
     */
    onDrop: function (node, data, overModel, dragPosition, eOpts) {
        var me = this,
            ids = [];
        if (data.records.length) {
            Ext.each(data.records, function (item) {
                ids.push(item.get('menu_id'));
            });
            var params = {
                menu_ids: ids.join(','),
                position: dragPosition,
                target_menu_id: overModel.get('menu_id')
            };

            Ext.MessageBox.show({
                msg: '排序调整，数据同步中...',
                progressText: '数据提交中...',
                width: 300,
                wait: true,
                progress: true,
                closable: true,
                waitConfig: {
                    interval: 200
                },
                icon: Ext.Msg.INFO
            });
            Ext.Ajax.request({
                url: getUrl('admin.menu.drop'),
                params: params,
                success: function (response) {
                    Ext.MessageBox.hide();
                    try {
                        var data = Ext.JSON.decode(response.responseText);
                        me.showToast(data.msg, '系统提示');
                    } catch (e) {
                        me.alert(e);
                    }
                },
                failure: function (response) {
                    Ext.MessageBox.hide();
                    try {
                        var data = Ext.JSON.decode(response.responseText);
                        me.showToast(data.msg, '系统提示');
                    } catch (e) {
                        me.alert(e);
                    }
                }
            });
        }
    }

});