/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.view.system.menu.Menu', {
    extend: 'DP.dp.component.container.Container',

    requires: [
        'DP.dp.base.grid.column.Text',
        'DP.dp.base.tree.Panel',
        'DP.dp.component.column.Status',
        'DP.dp.component.column.Text',
        'DP.dp.component.form.field.TreeSearch',
        'DP.dp.store.admin.Menu',
        'DP.dp.view.system.menu.MenuController',
        'DP.dp.view.system.menu.MenuModel',
        'Ext.button.Button',
        'Ext.grid.column.Widget',
        'Ext.tree.Column',
        'Ext.tree.plugin.TreeViewDragDrop'
    ],

    viewModel: {
        type: 'menu'
    },

    controller: 'menu',

    itemId: 'system-menu',


    items: [
        {
            xtype: 'base-treepanel',

            viewConfig: {
                markDirty: false,
                plugins: {
                    ptype: 'treeviewdragdrop',
                    containerScroll: true
                },
                listeners: {
                    drop: 'onDrop'
                }
            },

            //bbar: [{
            //    xtype: 'panel',
            //    bind: {
            //        html: '{itemCount}个项目'
            //    },
            //    hidden: true
            //}, {
            //    xtype: 'panel',
            //    bind: {
            //        html: ' 选中 {selectItemCount} 个项目',
            //        hidden: '{!selectItemCount}'
            //    }
            //}],

            columns: [
                {
                    xtype: 'treecolumn',
                    text: '名称',
                    dataIndex: 'text',
                    minWidth: 250,
                    flex: true
                },
                {
                    text: '菜单id',
                    dataIndex: 'menu_id',
                    width: 80,
                    hidden: true
                },
                {
                    xtype: 'textcolumn',
                    text: '标题',
                    dataIndex: 'title',
                    width: 200
                },
                {
                    text: '显示排序号',
                    dataIndex: 'display_order'
                },
                {
                    xtype: 'textcolumn',
                    text: '跳转URL',
                    dataIndex: 'url',
                    width: 200
                },
                {
                    xtype: 'textcolumn',
                    text: '视图名',
                    dataIndex: 'view_package',
                    width: 200
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '展开',
                    dataIndex: 'is_expand'
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '允许关闭',
                    dataIndex: 'closable',
                    width: 80
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '打开URL',
                    dataIndex: 'is_open_url',
                    width: 80
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '新窗口',
                    dataIndex: 'is_open_target',
                    width: 70
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '每次打开',
                    dataIndex: 'is_every_open',
                    width: 90
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '隐藏',
                    dataIndex: 'is_hide'
                },
                {
                    xtype: 'mycolumntext',
                    text: '备注',
                    dataIndex: 'note',
                    flex: true
                },
                {
                    xtype: 'widgetcolumn',
                    text: 'URL管理',
                    widget: {
                        xtype: 'button',
                        itemId: 'urlManager',
                        text: '管理',
                        handler: 'onClickUrlManager'
                    }
                },
                {
                    xtype: 'mycolumnstatus',
                    text: '状态',
                    dataIndex: 'status'
                }
            ],

            listeners: {
                load: 'onLoad'
            }
        }
    ],

    initComponent: function () {
        var me = this,
            store = Ext.create('DP.dp.store.admin.Menu');
        this.items[0].store = store;
        this.items[0].tbar = [{
            text: '刷新',
            iconCls: 'fa fa-refresh',
            handler: 'onRefresh'
        }, {
            text: '添加',
            iconCls: 'fa fa-plus',
            handler: 'onAdd'
        }, {
            text: '删除',
            iconCls: 'fa fa-trash-o',
            handler: 'onDelete',
            itemId: 'delete',
            disabled: true
        }, {
            text: '修改',
            iconCls: 'fa fa-pencil',
            handler: 'onEdit',
            itemId: 'edit',
            disabled: true
        }, '-', {
            text: '启用',
            iconCls: 'fa fa-play',
            handler: 'onStart',
            itemId: 'start',
            disabled: true
        }, {
            text: '禁用',
            iconCls: 'fa fa-pause',
            handler: 'onDisable',
            itemId: 'stop',
            disabled: true
        }, '-', {
            text: '展开',
            iconCls: 'fa fa-expand',
            handler: 'onExpand'
        }, {
            text: '收起',
            iconCls: 'fa fa-compress',
            handler: 'onCollapse'
        }, '-', '名称', {
            xtype: 'treesearch',
            store: store
        }];
        this.callParent(arguments);
    }

});