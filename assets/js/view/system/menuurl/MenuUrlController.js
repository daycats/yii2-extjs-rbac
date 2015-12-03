/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.view.system.menuurl.MenuUrlController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.menuurl',

    requires: [
        'DP.dp.base.window.Window',
        'DP.dp.view.system.menuurl.MenuUrlFormWindow',
        'DP.dp.view.system.menuurlrule.MenuUrlRule'
    ],

    idProperty: 'url_id',
    saveUrl: getUrl('admin.menu-url.save'),
    updateStatusUrl: getUrl('admin.menu-url.update-status'),
    deleteUrl: getUrl('admin.menu-url.del'),

    init: function () {
        this.editWindow = DP.dp.view.system.menuurl.MenuUrlFormWindow;
        this.callParent(arguments);
    },

    /**
     * 规则管理点击事件
     */
    onClickRuleManager: function (btn) {
        var me = this,
            rec = btn.getWidgetRecord();
        var ruleWindow = Ext.create('DP.dp.base.window.Window', {
            title: 'URL规则管理 - ' + rec.get('name'),
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
    },

    onEdit: function () {
        var editWindow = this.callParent(),
            me = this,
            selectionData = this.gridpanel.getSelectionModel().getSelection(),
            values = [];
        if (!selectionData[0]) {
            me.alert('请选择一条数据');
            return false;
        }
        editWindow.down('form').getForm().setValues({
            'method[]': selectionData[0].get('method').split(',')
        });
    }

});