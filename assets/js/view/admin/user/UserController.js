/**
 * Created by shanli on 2015/8/23.
 */
Ext.define('DP.dp.view.admin.user.UserController', {
    extend: 'DP.dp.base.ViewController',
    alias: 'controller.user',

    requires: [
        'DP.dp.view.admin.user.UserFormWindow'
    ],

    saveUrl: getUrl('admin.user.save'),
    updateStatusUrl: getUrl('admin.user.update-status'),
    deleteUrl: getUrl('admin.user.del'),

    init: function () {
        this.editWindow = DP.dp.view.admin.user.UserFormWindow;
        this.callParent(arguments);
    },

    onFormCancel: function (view, e, eOpts) {
        view.up('form').getForm().reset();
        view.up('window').close();
    },

    getMenu: function () {
        return this.getView().down('#menu');
    },

    onCheckchange: function (node, check) {
        var me = this,
            data = node.getData(),
            isChecked = false;
        if (data.leaf === false) {
            me.treeItemChecked(node, check);
            me.treeParentChecked(node, check);
        } else {
            isChecked = false;
            me.treeParentChecked(node);
        }
    },

    /**
     * 窗口树形全选
     */
    onMenuAllSelection: function() {
        this.treeItemChecked(this.getMenu().getRootNode(), true);
    },

    /**
     * 窗口树形取消全选
     */
    onMenuCancelSelection: function() {
        this.treeItemChecked(this.getMenu().getRootNode(), false);
    },

    /**
     * 窗口的树形菜单展开
     *
     * @returns {*}
     */
    onMenuExpand: function() {
        this.getMenu().expandAll();
    },

    /**
     * 窗口的树形菜单收起
     */
    onMenuCollapse: function() {
        this.getMenu().collapseAll();
    },

    /**
     * 刷新
     */
    onMenuRrefresh: function() {
        this.getMenu().getStore().reload();
    },

    /**
     * 选择子项父级随着选择取消
     *
     * @param child
     * @returns {*}
     */
    treeParentChecked: function(child) {
        var isChecked = false;
        if (child.parentNode) {
            if (child.parentNode.getData().text !== 'Root') {
                child.parentNode.eachChild(function(nodeChild) {
                    if (nodeChild.get('checked') === true) {
                        isChecked = true;
                    }
                });
                if (isChecked === true) {
                    child.parentNode.set('checked', true);
                } else {
                    child.parentNode.set('checked', false);
                }
                this.treeParentChecked(child.parentNode);
            }
        }
    },

    /**
     * 选择父级子项随着父级选择取消
     *
     * @param node
     * @param checked
     */
    treeItemChecked: function(node, checked) {
        var me = this;
        node.eachChild(function(child) {
            child.set('checked', checked);
            me.treeItemChecked(child, checked);
        });
    },

    onAdd: function () {
        this.callParent(arguments);

        var view = this.getView(),
            treepanel = view.down('treepanel'),
            store = treepanel.getStore();
        store.reload();
    },

    onEdit: function () {
        var editWindow = this.callParent(arguments),
            view = this.getView(),
            treepanel = view.down('treepanel'),
            store = treepanel.getStore();
        store.getProxy().extraParams['user_id'] = view.down('#user_id').getValue();
        store.reload();

        var selectionData = this.gridpanel.getSelectionModel().getSelection(),
            form = editWindow.down('#form');
        if (selectionData[0]) {
            var groupIds = selectionData[0].get('group_ids');
            if (form && groupIds) {
                form.getForm().setValues({
                    'group_ids[]': groupIds.split(',')
                });
            }
        }
    },

    onLoad: function () {
        this.getView().down('treepanel').expandAll();
    },

    submit: function (form, params) {
        var menuIds = [],
            treeSelected = this.getView().down('treepanel').getChecked();
        menuIds = [];
        Ext.each(treeSelected, function(selected) {
            menuIds.push(selected.get('menu_id'));
        });
        this.callParent([
            form,
            {
                menu_ids: menuIds.join(',')
            }
        ]);
    }

});