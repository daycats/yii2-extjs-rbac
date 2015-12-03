
/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.store.admin.GroupAll', {
    extend: 'DP.dp.base.data.Store',
    alias: 'store.group-all',

    requires: [
        'DP.dp.model.admin.Group'
    ],

    model: 'DP.dp.model.admin.Group',

    proxy: {
        url: getUrl('admin.group.all')
    },

    sorters: {
        property: 'group_id',
        direction: 'DESC'
    }
});