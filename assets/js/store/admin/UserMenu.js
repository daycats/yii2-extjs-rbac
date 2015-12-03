/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.store.admin.UserMenu', {
    extend: 'DP.dp.base.data.TreeStore',
    alias: 'store.user-menu',

    requires: [
        'DP.dp.model.admin.Menu'
    ],

    model: 'DP.dp.model.admin.Menu',

    proxy: {
        url: getUrl('admin.user.menu')
    },

    sorters: {
        property: 'display_order',
        direction: 'ASC'
    }
});