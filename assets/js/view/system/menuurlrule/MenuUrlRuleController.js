/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.view.system.menuurlrule.MenuUrlRuleController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.menuurlrule',

    requires: [
        'DP.dp.view.system.menuurlrule.MenuUrlRuleFormWindow'
    ],

    saveUrl: getUrl('admin.menu-url-rule.save'),
    updateStatusUrl: getUrl('admin.menu-url-rule.update-status'),
    deleteUrl: getUrl('admin.menu-url-rule.del'),

    init: function () {
        this.editWindow = DP.dp.view.system.menuurlrule.MenuUrlRuleFormWindow;
        this.callParent(arguments);
    },

    onAdd: function () {
        var me = this,
            formWindow = this.callParent(arguments);
        formWindow.down('form').getForm().setValues({
            url_id: me.getView().params['url_id']
        });
    }

});