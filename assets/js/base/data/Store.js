/**
 * Created by shanli on 2015/9/14.
 */
Ext.define('DP.dp.base.data.Store', {
    extend: 'Ext.data.Store',

    proxy: {
        type: 'ajax',
        reader: {
            type: 'json',
            rootProperty: 'list'
        }
    },

    pageSize: 20,
    autoLoad: true,
    remoteSort: true
});