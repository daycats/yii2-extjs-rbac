/**
 * This class is the main view for the application. It is specified in app.js as the
 * "autoCreateViewport" property. That setting automatically applies the "viewport"
 * plugin to promote that instance of this class to the body element.
 */
Ext.define('DP.dp.view.main.MainController', {
    extend: 'Ext.app.ViewController',

    alias: 'controller.main',

    requires: [
        'Ext.util.Cookies'
    ],

    init: function () {
        this.callParent(arguments);

        if (isGuest) {
            Ext.create('DP.dp.view.public.login.Login').show();
        }
    },

    /**
     * 折叠导航状态记录到cookie中
     *
     * @param p
     * @param eOpts
     */
    onCollapse: function(p, eOpts) {
        Ext.util.Cookies.set('navigation.collapsed', true, Ext.Date.add(new Date(), Ext.Date.YEAR, 1));
    },

    /**
     * 展开导航状态记录到cookie中
     */
    onExpand: function () {
        Ext.util.Cookies.set('navigation.collapsed', false, Ext.Date.add(new Date(), Ext.Date.YEAR, 1));
    },

    /**
     * 注销
     */
    onLogout: function () {
        Ext.namespace('DP').getApplication().fireEvent('logout');
    }
});
