/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.store.admin.MenuUrl', {
    extend: 'DP.dp.base.data.Store',

    requires: [
        'DP.dp.model.admin.MenuUrl'
    ],

    model: 'DP.dp.model.admin.MenuUrl',

    proxy: {
        url: getUrl('admin.menu-url.list')
    },

    sorters: {
        property: 'url_id',
        direction: 'DESC'
    }
});