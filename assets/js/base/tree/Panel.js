/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.base.tree.Panel', {
    extend: 'Ext.tree.Panel',
    xtype: 'base-treepanel',

    viewConfig: {
        enableTextSelection: true,
        markDirty: false
    },

    selModel: {
        selType: 'checkboxmodel'
    },

    expanded: true,
    //lines: true,
    //useArrows: true,
    //hideHeaders: true,
    //collapseFirst: false,
    //stateful: true,
    rootVisible: false,

    listeners: {
        selectionchange: 'onSelectionchange',
        itemdblclick: 'onItemdblclick'
    }
});