/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.store.admin.MenuUrlAll', {
    extend: 'DP.dp.base.data.Store',
    alias: 'store.menu-url-all',

    requires: [
        'DP.dp.model.admin.MenuUrl'
    ],

    model: 'DP.dp.model.admin.MenuUrl',

    proxy: {
        url: getUrl('admin.menu-url.all')
    },

    sorters: {
        property: 'url_id',
        direction: 'DESC'
    }
});