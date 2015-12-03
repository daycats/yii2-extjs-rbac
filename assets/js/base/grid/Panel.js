/**
 * Created by shanli on 2015/8/30.
 */
Ext.define('DP.dp.base.grid.Panel', {
    extend: 'Ext.grid.Panel',
    xtype: 'base-gridpanel',

    requires: [
        'Ext.form.field.ComboBox',
        'Ext.toolbar.Paging',
        'Ext.toolbar.Separator'
    ],

    viewConfig: {
        stripeRows: true,
        enableTextSelection: true
    },
    selModel: {
        selType: 'checkboxmodel'
    },
    initComponent: function () {
        var me = this;
        this.store.pageSize = getConfig('system.limit');
        this.store.loadPage(1);
        this.dockedItems = [
            {
                xtype: 'pagingtoolbar',
                dock: 'bottom',
                displayInfo: true,
                store: this.store,
                items: [
                    '-', '每页显示', {
                        xtype: 'combobox',
                        value: getConfig('system.limit'),
                        width: 80,
                        displayField: 'pageSize',
                        editable: false,
                        mode: 'local',
                        listeners: {
                            change: function (view, value) {
                                me.store.pageSize = value;
                                me.store.loadPage(1);
                            }
                        },
                        store: 'DP.dp.store.Pagination'
                    }, '条'
                ]
            }
        ];
        this.callParent(arguments);
    },

    listeners: {
        selectionchange: 'onSelectionchange',
        itemdblclick: 'onItemdblclick'
    }
});