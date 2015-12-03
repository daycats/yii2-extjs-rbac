/**
 * Created by shanli on 2015/8/23.
 */
Ext.define('DP.dp.component.grid.column.Status', {
    extend: 'Ext.grid.column.Column',
    alias: 'widget.statuscolumn',
    width: 80,
    align: 'center',
    renderer: function (value) {
        if (0 == value) {
            return '<span style="color:#F00">×</span>';
        } else {
            return '<span style="color:#008000">√</span>';
        }
    }
});