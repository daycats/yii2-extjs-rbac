/**
 * Created by shanli on 2015/9/3.
 */
Ext.define('DP.dp.view.navigation.Navigation', {
    extend: 'Ext.panel.Panel',

    requires: [
        'Ext.layout.container.Accordion',
        'Ext.tree.Panel',
        'DP.dp.component.form.field.TreeSearch',
        'DP.dp.store.menu.Tree',
        'DP.dp.view.navigation.NavigationController',
        'DP.dp.view.navigation.NavigationModel'
    ],

    xtype: 'navigation',

    viewModel: {
        type: 'navigation'
    },

    controller: 'navigation',

    layout: 'accordion',
    initComponent: function () {
        var me = this,
            store = Ext.create('DP.dp.store.menu.Tree', {
                root: {
                    id: 'src',
                    expanded: isGuest ? false : true
                },
                autoLoad: isGuest ? false : true
            });
        this.items = [
            {
                title: '站点管理',
                xtype: 'treepanel',
                //lines: true,
                //useArrows: true,
                //hideHeaders: true,
                //collapseFirst: false,
                //stateful: true,
                rootVisible: false,
                viewConfig: {
                    markDirty: false
                },
                tools: [
                    {
                        type: 'refresh',
                        tooltip: '刷新',
                        handler: 'onRefresh'
                    }, {
                        type: 'down',
                        itemId: 'down',
                        tooltip: '全部展开',
                        handler: 'onExpandAllClick'
                    }, {
                        type: 'up',
                        itemId: 'up',
                        tooltip: '全部收起',
                        handler: 'onCollapseAllClick'
                    }
                ],
                store: store,
                dockedItems: [{
                    xtype: 'treesearch',
                    store: store
                }],
                listeners: {
                    itemclick: 'onItemclick',
                    load: 'onLoad'
                }
            }
        ];
        this.callParent(arguments);
    }
});