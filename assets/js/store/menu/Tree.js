/**
 * Created by shanli on 2015/8/30.
 */
Ext.define('DP.dp.store.menu.Tree', {
    extend: 'DP.dp.base.data.TreeStore',

    requires: [
        'DP.dp.model.admin.Menu'
    ],

    model: 'DP.dp.model.admin.Menu',

    proxy: {
        url: getUrl('admin.common.tree')
    },

    sorters: {
        property: 'display_order',
        direction: 'ASC'
    }
});