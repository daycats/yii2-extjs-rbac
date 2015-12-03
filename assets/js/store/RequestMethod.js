/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.store.RequestMethod', {
    extend: 'Ext.data.ArrayStore',
    alias: 'store.request-method',

    fields: ['name'],
    data: [
        ['GET'],
        ['HEAD'],
        ['POST'],
        ['PUT'],
        ['PATCH'],
        ['DELETE'],
        ['OPTIONS']
    ]
});