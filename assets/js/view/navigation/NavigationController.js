/**
 * Created by shanli on 2015/9/3.
 */
Ext.define('DP.dp.view.navigation.NavigationController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.navigation',

    requires: [
        'Ext.layout.container.Fit',
        'Ext.panel.Panel'
    ],

    init: function () {
        var me = this,
            app = Ext.namespace('DP').getApplication();
        // 监听添加tab事件
        app.on('addTab', function (data) {
            me.activeTab(data);
        });
        app.on('closeTab', function (tabId) {
            me.closeTab(tabId);
        });
    },

    /**
     * 关闭tab
     *
     * @param tabId
     */
    closeTab: function (tabId) {
        var tab = Ext.getCmp(tabId);
        if (tab) {
            tab.close();
        }
    },

    /**
     * 监听刷新按钮点击事件
     *
     * @param event
     * @param toolEl
     * @param panelHeader
     */
    onRefresh: function (event, toolEl, panelHeader) {
        panelHeader.ownerCt.getStore().reload();
    },

    onExpandAllClick: function (event, toolEl, panelHeader) {

        var view = this.getView(),
            treepanel = view.down('treepanel'),
            down = view.down('#down');

        treepanel.getEl().mask('展开中...');
        down.disable();

        treepanel.expandAll(function () {
            treepanel.getEl().unmask();
            down.enable();
        });
    },

    onCollapseAllClick: function (event, toolEl, panelHeader) {
        var view = this.getView(),
            treepanel = view.down('treepanel'),
            up = view.down('#up');

        up.disable();
        treepanel.collapseAll(function () {
            up.enable();
        });
    },

    /**
     * 监听节点点击事件
     *
     * @param view
     * @param record
     * @param item
     * @param index
     * @param e
     * @param eOpts
     */
    onItemclick: function (view, record, item, index, e, eOpts) {
        var text = record.data['origin_text'],
            isOpenUrl = record.data['is_open_url'],
            viewPackage = record.data['view_package'];
        if (record.data.leaf) {
            if (viewPackage || isOpenUrl) {
                this.activeTab(record.data);
            } else {
                Ext.Msg.show({
                    title: '系统提示',
                    msg: '菜单：' + text + ' 属性：view_package未设置值',
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    scope: view
                });
            }
        }
    },

    /**
     * 加载界面事件
     *
     * @param me
     * @param records
     * @param successful
     * @param eOpts
     */
    onLoad: function (me, records, successful, eOpts) {
        var me = this;
        Ext.Ajax.request({
            url: getUrl('admin.common.urls'),
            success: function (response) {
                try {
                    var data = Ext.JSON.decode(response.responseText);
                    if (data.data) {
                        urls = data.data;
                    }
                    if (window['needActiveTab']) {
                        window['needActiveTab'] = false;
                        var defaultToken = Ext.namespace('DP').getApplication().getConfig('defaultToken');
                        if (defaultToken) {
                            var node = me.getByTabId(records, defaultToken);
                            if (node) {
                                me.activeTab(node.data);
                            }
                        }
                    }
                } catch (e) {
                    me.alert(data.msg);
                }
            },
            failure: function (response) {
                try {
                    var data = Ext.JSON.decode(response.responseText);
                    me.showToast(data.msg, '失败');
                } catch (e) {
                    me.alert(data.msg);
                }
            }
        });
    },

    onShow: function () {
        console.log('onShow');
    },

    getByTabId: function (children, tabId) {
        for (var i = 0, len = children.length; i < len; i++) {
            if (tabId == children[i].data['tab_id']) {
                return children[i];
            } else if (children[i].childNodes.length) {
                var node = this.getByTabId(children[i].childNodes, tabId);
                if (node) {
                    return node;
                }
            }
        }

        return null;
    },

    /**
     * 设置活动tab
     *
     * @param data
     */
    activeTab: function (data) {
        var me = this,
            view = me.getView(),
            text = data['origin_text'] ? data['origin_text'] : data['text'],
            title = data['title'] ? data['title'] : text,
            closable = data['closable'],
            leaf = data['leaf'],
            url = data['url'],
            isOpenUrl = data['is_open_url'],
            isOpenTarget = data['is_open_target'],
            isEveryOpen = data['is_every_open'],
            tabId = data['tab_id'],
            viewPackage = data['view_package'],
            params = data['params'];

        var item = {};
        if (params) {
            try {
                item['params'] = eval('(' + params + ')');
            } catch (e) {
                var message = '名称:' + text + '<br>' + '视图名:' + viewPackage + '<br>参数错误信息:<br><b style="color: #f00">' + e.message + '</b>';
                this.alert(message, 'params设置有误');
            }
        }

        if (!text) {
            me.alert('菜单：属性：text未设置值');
            return false;
        }
        if (!tabId) {
            me.alert('菜单：' + text + ' 属性：tab_id未设置值');
            return false;
        }
        if (isOpenUrl) {
            if (Ext.isEmpty(url)) {
                me.alert('菜单：' + text + ' 属性：url未设置值');
                return false;
            }
        } else if (!viewPackage) {
            me.alert('菜单：' + text + ' 属性：view_package未设置值');
            return false;
        }

        if (leaf) {
            if (isOpenUrl && isOpenTarget) {
                if (!Ext.isEmpty(url)) {
                    window.open(url);
                } else {
                    Ext.Msg.show({
                        title: '系统提示',
                        msg: '菜单：' + text + ' 属性：url未设置值',
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK,
                        scope: view
                    });
                }
            } else if (isOpenUrl && Ext.isEmpty(url)) {
                Ext.Msg.show({
                    title: '系统提示',
                    msg: '菜单：' + text + ' 属性：url未设置值',
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    scope: view
                });
            } else {
                var tabpanel = view.findParentByType('app-main').down('#main-tabs'),
                    tab = Ext.getCmp(tabId);
                if (tab) {
                    // 每次重新打开tab
                    if (isEveryOpen) {
                        tab.close();
                        tab = null;
                    } else {
                        tabpanel.setActiveItem(tab);
                    }
                }
                if (!tab) {
                    tab = tabpanel.add({
                        id: tabId,
                        title: title,
                        closable: closable,
                        xtype: 'panel',
                        layout: 'fit'
                    });
                    tabpanel.setActiveItem(tab);
                    tab.mask('拼命加载中，请耐心等待...');

                    if (isOpenUrl && Ext.isEmpty(url)) {
                        Ext.Msg.show({
                            title: '系统提示',
                            msg: '菜单：' + text + ' 属性：url未设置值',
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            scope: view
                        });
                    }
                    if (isOpenUrl) {
                        // tab中打开url
                        var eles = Ext.query('[id=' + tabId + '] [class=tab-url]');
                        if (!eles.length) {
                            tab.add({
                                html: '<iframe class="tab-url" height="100%" width="100%" frameborder="0" src="' + url + '"/>'
                            });
                            eles = Ext.query('[id=' + tabId + '] [class=tab-url]');
                            if (eles[0]) {
                                eles[0].addEventListener('load', (function (itemTabPanel) {
                                    return function () {
                                        itemTabPanel.getEl().unmask();
                                    };
                                })(tab));
                            }
                        }
                    } else {
                        // tab中添加新的panel
                        var className = getViewClass(viewPackage);
                        Ext.require(className, function (optional) {
                            tab.unmask();
                            if (optional) {
                                tab.add(optional.create(item));
                            } else {
                                Ext.Msg.show({
                                    title: '系统提示',
                                    msg: '菜单：' + text + ' 功能加载失败，请重试！',
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.YESNOCANCEL,
                                    scope: view,
                                    fn: function (btn) {
                                        if (btn === 'yes') {
                                            Ext.Msg.hide();
                                            me.activeTab(data);
                                        } else if (btn === 'no') {
                                        } else {
                                            tab.close();
                                        }
                                    }
                                });
                            }
                        });
                    }
                }
            }
        } else if (leaf !== false) {
            me.alert('菜单：' + text + ' 属性：leaf未设置值');
        }
    },

    showToast: function (content, title) {
        Ext.toast({
            title: title,
            html: content,
            closable: true,
            align: 't',
            slideInDuration: 400,
            minWidth: 400
        });
    },

    alert: function (msg, title) {
        Ext.Msg.show({
            title: title ? title : '系统提示',
            msg: msg,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.YES
        });
    }

});