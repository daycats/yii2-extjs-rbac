/**
 * Created by shanli on 2015/9/7.
 */
Ext.define('DP.dp.base.tree.TextColumn', {
    extend: 'Ext.tree.Column',
    xtype: 'treetextcolumn',

    renderer: function (value) {
        return '<span data-qtip="' + value + '">' + value + '</span>';
    }
});