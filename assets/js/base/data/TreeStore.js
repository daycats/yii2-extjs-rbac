/**
 * Created by shanli on 2015/9/15.
 */
Ext.define('DP.dp.base.data.TreeStore', {
    extend: 'Ext.data.TreeStore',

    proxy: {
        type: 'ajax',
        reader: 'json'
    },
    root: {
        id: 'src',
        expanded: true
    },

    pageSize: 20,
    autoLoad: true,
    remoteSort: true
});