/**
 * Created by shanli on 2015/8/23.
 */
Ext.define('DP.dp.store.admin.User', {
    extend: 'DP.dp.base.data.Store',

    requires: [
        'DP.dp.model.admin.User'
    ],

    model: 'DP.dp.model.admin.User',

    proxy: {
        url: getUrl('admin.user.list')
    },

    sorters: {
        property: 'user_id',
        direction: 'DESC'
    }
});