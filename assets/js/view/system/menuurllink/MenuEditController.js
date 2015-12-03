/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.view.system.menuurllink.MenuEditController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.menu-edit',

    idProperty: 'url_id',
    saveUrl: getUrl('admin.menu-url.save'),
    updateStatusUrl: getUrl('admin.menu-url.update-status'),
    deleteUrl: getUrl('admin.menu-url.del'),

    init: function () {
        var me = this,
            form = me.getView().down('form');
        this.callParent(arguments);
        this.addFormEnterEvent(form, function (form) {
            me.submit(form);
        });
    },

    onSubmitSuccess: function () {
        this.getView().scope.down('gridpanel').getStore().reload();
    }
});