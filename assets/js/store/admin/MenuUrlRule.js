/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.store.admin.MenuUrlRule', {
    extend: 'DP.dp.base.data.Store',

    requires: [
        'DP.dp.model.admin.MenuUrlRule'
    ],

    model: 'DP.dp.model.admin.MenuUrlRule',

    proxy: {
        url: getUrl('admin.menu-url-rule.list')
    },

    sorters: {
        property: 'rule_id',
        direction: 'DESC'
    }
});