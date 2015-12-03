/**
 * Created by shanli on 2015/9/7.
 */
Ext.define('DP.dp.base.grid.column.Url', {
    extend: 'Ext.grid.column.Column',
    xtype: 'urlcolumn',

    renderer: function (value) {
        return '<a data-qtip="' + value + '" href="' + value + '" target="_blank">' + value + '</a>';
    }
});