/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.store.admin.MenuAll', {
    extend: 'DP.dp.base.data.Store',
    alias: 'store.admin-menu-all',

    requires: [
        'DP.dp.model.admin.Menu'
    ],

    model: 'DP.dp.model.admin.Menu',

    proxy: {
        url: getUrl('admin.menu.all')
    },
    root: {
        id: 'src',
        expanded: true
    },

    pageSize: 20,
    autoLoad: true
});