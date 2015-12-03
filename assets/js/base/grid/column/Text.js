/**
 * Created by shanli on 2015/9/7.
 */
Ext.define('DP.dp.base.grid.column.Text', {
    extend: 'Ext.grid.column.Column',
    xtype: 'textcolumn',

    renderer: function (value) {
        if (value) {
            var newValue =  value.replace(/<.*?>/g, '');
            value ='<span data-qtip="' + value + '">' + newValue + '</span>';
        }

        return value;
    }
});