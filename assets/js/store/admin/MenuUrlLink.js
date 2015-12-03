/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.store.admin.MenuUrlLink', {
    extend: 'DP.dp.base.data.Store',

    requires: [
        'DP.dp.model.admin.MenuUrlLink'
    ],

    model: 'DP.dp.model.admin.MenuUrlLink',

    proxy: {
        url: getUrl('admin.menu-url-link.list')
    },

    sorters: {
        property: 'link_id',
        direction: 'DESC'
    }
});