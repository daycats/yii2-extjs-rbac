/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.store.admin.GroupMenu', {
    extend: 'DP.dp.base.data.TreeStore',
    alias: 'store.admin-menu',

    requires: [
        'DP.dp.model.admin.Menu'
    ],

    model: 'DP.dp.model.admin.Menu',

    proxy: {
        url: getUrl('admin.group.menu')
    },

    sorters: {
        property: 'display_order',
        direction: 'ASC'
    }
});