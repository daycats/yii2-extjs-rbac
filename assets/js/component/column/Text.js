/**
 * Created by shanli on 2015/5/25.
 */
Ext.define('DP.dp.component.column.Text', {
    extend: 'Ext.grid.column.Column',
    xtype: 'mycolumntext',
    renderer: function (value) {
        value = value.replace(/<.*?>/g, '');
        value = '<div data-qtip=\"' + value + '\">' + value + '</div>';

        return value;
    }
});