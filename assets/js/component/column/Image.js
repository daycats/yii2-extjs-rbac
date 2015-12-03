/**
 * Created by shanli on 2015/9/2.
 */
Ext.define('DP.dp.component.column.Image', {
    extend: 'Ext.grid.column.Column',
    xtype: 'imagecolumn',
    text: '图片',
    width: 120,
    renderer: function (value) {
        if (value) {
            var image = "<img src='" + value + "' style='max-width: 100%;max-height: 100%;'>";
            return "<img data-qtip=\"" + image + "\" style=\"max-width: 100px;max-height: 100px;\" src=\"" + value + "\">";
        } else {
            return '无图';
        }
    }
});