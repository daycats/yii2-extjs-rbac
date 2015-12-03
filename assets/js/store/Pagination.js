/**
 * Created by shanli on 2015/8/23.
 */
(function () {
    var data = [[5], [10], [25], [50], [75], [100], [125], [150], [175], [200]],
        limitData = getConfig('system.limitData');
    if (!Ext.isEmpty(limitData)) {
        data = [];
        Ext.each(limitData.split(','), function (limit) {
            data.push([limit * 1]);
        });
    }
    Ext.define('DP.dp.store.Pagination', {
        extend: 'Ext.data.ArrayStore',
        fields: ['pageSize'],
        data: data
    });
})();