/**
 * Created by shanli on 2015/5/26.
 */
Ext.define('DP.dp.component.toolbar.Pagination', {
    extend: 'Ext.toolbar.Paging',
    alias: 'widget.pagination',

    requires: [
        'Ext.data.ArrayStore',
        'Ext.form.field.ComboBox'
    ],

    dock: 'bottom',
    displayInfo: true,
    items: [
        '-', '每页显示', {
            xtype: 'combobox',
            value: 25,
            width: 80,
            displayField: 'pageSize',
            editable: false,
            mode: 'local',
            listeners: {
                change: function (comobox, value) {
                    var store = this.ownerCt.store;
                    store.pageSize = value;
                    store.load();
                }
            },
            store: Ext.create('Ext.data.ArrayStore', {
                fields: ['pageSize'],
                data: [
                    [5],
                    [10],
                    [25],
                    [50],
                    [75],
                    [100],
                    [125],
                    [150],
                    [175],
                    [200]
                ]
            })
        }, '条'
    ]
});
