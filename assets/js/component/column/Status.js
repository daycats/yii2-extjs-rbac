/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.component.column.Status', {
    extend: 'Ext.grid.column.Column',
    xtype: 'mycolumnstatus',
    width: 60,
    align: 'center',
    renderer: function (value) {
        if (value) {
            return '<span data-qtip="启用" style="color: #080">√</span>';
        } else {
            return '<span data-qtip="禁用" style="color: #F00">×</span>';
        }
    }
});