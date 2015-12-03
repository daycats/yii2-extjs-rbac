/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.controller.Navigation', {
    extend: 'Ext.app.Controller',

    config: {
        control: {
            '#main-tabs': {
                tabchange: 'onTabChange'
            },
            '#tab6': {
                tabchange: 'onChildTabChange'
            }
        },
        refs: {
            tabPanel: '#main-tabs'
        },
        routes: {
            'main-tabs:id:subid': {
                action: 'showTab',
                before: 'beforeShowTab',
                conditions: {
                    //take control of the :id & :subid parameters, make them optional but delimited by colon
                    ':id': '(?:(?::){1}([%a-zA-Z0-9\-\_\s,]+))?',
                    ':subid': '(?:(?::){1}([%a-zA-Z0-9\-\_\s,]+))?'
                }
            }
        }
    },

    onTabChange: function(tabPanel, newItem) {
        var id = newItem.getId(),
            child = newItem.child('tabpanel'),
            subid = '',
            hash = 'main-tabs:' + id;

        if (child) {
            newItem = child.getActiveTab();
            subid = ':' + newItem.getId();

            hash += subid;
        }

        this.redirectTo(hash);
    },

    onChildTabChange: function(tabPanel, newItem) {
        var parentTab = tabPanel.up(),
            parentId = parentTab.getId(),
            hash = 'main-tabs:' + parentId + ':' + newItem.getId();

        this.redirectTo(hash);
    },

    beforeShowTab: function(id, subid, action) {
        var tabPanel = this.getTabPanel(),
            child;

        if (!id) {
            //no id was specified, use 0 index to resolve child
            id = 0;
        }

        child = tabPanel.getComponent(id);

        if (child) {
            //tab found, resume the action
            action.resume();
        } else {
            //Ext.Msg.alert('Tab Not Found', 'Tab with id or index "<b>' + id + '</b>" was not found!');
            Ext.namespace('DP').getApplication().setConfig('defaultToken', id);

            //child not found, stop action
            action.stop();
        }
    },

    showTab: function(id, subid) {
        var tabPanel = this.getTabPanel(),
            child, childTabPanel;

        if (!id) {
            //no id was specified, use 0 index to resolve child
            id = 0;
        }

        child = tabPanel.getComponent(id);

        childTabPanel = child.child('tabpanel');

        tabPanel.setActiveTab(child);

        if (childTabPanel) {
            if (!subid) {
                subid = 0;
            }

            childTabPanel.setActiveTab(subid);
        }
    }
});