/**
 * The main application class. An instance of this class is created by app.js when it calls
 * Ext.application(). This is the ideal place to handle application launch and initialization
 * details.
 */
Ext.tip.QuickTipManager.init();
Ext.define('DP.dp.Application', {
    extend: 'Ext.app.Application',

    name: 'DP',

    stores: [
        'DP.dp.store.Pagination'
    ],

    controllers: ['DP.dp.controller.Navigation'],

    defaultToken: 'main-tabs',

    launch: function () {
        var me = this,
            app = Ext.namespace('DP').getApplication();
        window['needActiveTab'] = true;
        Ext.get('loading').remove();

        // 注册事件

        // 监听错误监听事件
        app.on('error', function (msg, title) {
            me.alert(msg, title);
        });

        // 监听显示登录创建事件
        app.on('showLogin', function () {
            var container = Ext.ComponentQuery.query('container#main-body')[0];
            if (container) {
                container.hide();
                Ext.create('DP.dp.view.public.login.Login').show();
            }
        });

        // 监听注销事件
        app.on('logout', function () {
            Ext.MessageBox.show({
                msg: '注销中，请稍后...',
                progressText: '注销中...',
                width: 300,
                wait: true,
                progress: true,
                closable: true,
                waitConfig: {
                    interval: 200
                },
                icon: Ext.Msg.INFO
            });
            Ext.Ajax.request({
                url: getUrl('admin.public.logout'),
                success: function (response) {
                    Ext.MessageBox.hide();
                    try {
                        var data = Ext.JSON.decode(response.responseText);
                        me.showToast(data.msg, '注销成功');
                        app.fireEvent('showLogin');
                    } catch (e) {
                        me.alert(data.msg);
                    }
                },
                failure: function (response) {
                    Ext.MessageBox.hide();
                    try {
                        var data = Ext.JSON.decode(response.responseText);
                        me.showToast(data.msg, '失败');
                    } catch (e) {
                        me.alert(data.msg);
                    }
                }
            });
        });

        //Ext.Ajax.on('beforerequest', function(){
        //    console.info('beforerequest');
        //});
        Ext.Ajax.on('requestcomplete', function (conn, response, options, eOpts) {
            try {
                var data = Ext.decode(response.responseText);
                if (!data.success && 1 == data.code) {
                    Ext.Ajax.abortAll();
                    if (!Ext.ComponentQuery.query('login').length) {
                        app.fireEvent('showLogin');
                    }
                } else if (2 == data.code) {
                    me.alert('URL：' + options.url + '<br>消息：' + data.msg);
                }
            } catch (e) {
                me.alert(e, '数据解析失败');
            }
        });

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

function getViewClass(className) {
    if (className) {
        var viewPackage = className.split('.');
        var viewClassName = '';
        var tempArr = [],
            name = '';
        for (var i = 0, len = viewPackage.length; i < len; i++) {
            if (viewPackage[i]) {
                name = viewPackage[i];
                tempArr.push(name.toLowerCase());
                if ((i + 1) == len) {
                    tempArr.push(name.substr(0, 1).toUpperCase() + name.substr(1, name.length));
                }
            }
        }
        var classStr = tempArr.join('.');

        if (classStr.indexOf('dp.') === 0) {
            return 'DP.' + classStr;
        } else {
            return 'DP.view.' + classStr;
        }
    }
    return '';
}


// BUG resolved
Ext.override(Ext.selection.CheckboxModel, {
    privates: {
        selectWithEventMulti: function (record, e, isSelected) {
            var me = this;

            if (!e.shiftKey && !e.ctrlKey && e.getTarget(me.checkSelector)) {
                if (isSelected) {
                    me.doDeselect(record); // Second param here is suppress event, not "keep selection"
                } else {
                    me.doSelect(record, true);
                }
            } else {
                me.callParent([record, e, isSelected]);
            }
        }
    }
});

Ext.override(Ext.form.CheckboxGroup, {
    /**
     * @cfg {String} name The field's HTML name attribute (defaults to "").
     */

    /**
     * @cfg {string} separator String seperator between multiple values
     */
    separator: ';',

    // private
    afterRender: function () {
        this.items.each(function (i) {
            i.ownerGroup = this; // kind of lame hack
        }, this);
        Ext.form.CheckboxGroup.superclass.afterRender.call(this);
    },

    /**
     * @method initValue
     * @hide
     */
    initValue: function () {
        if (this.value !== undefined) {
            this.setValue(this.value);
        }
    },

    /**
     * @method getValue
     * @hide
     */
    getValue: function () {
        if (!this.rendered) {
            return this.value;
        }
        var v = [];
        this.items.each(function (i) {
            if (i.getValue()) v.push(i.inputValue);
        });
        return v.join(this.separator);
    },

    /**
     * @method setValue
     * @hide
     */
    setValue: function (v) {
        this.value = v;
        if (this.rendered) {
            v = v.split(this.separator);
            this.items.each(function (i) {
                i.setValue(v.indexOf(i.inputValue) >= 0);
            }, this);
            this.validate();
        }
    },

    /**
     * Returns the name attribute of the field if available
     * @return {String} name The field name
     */
    getName: function () {
        return this.name;
    }
});


Ext.override(Ext.form.Radio, {
    // private
    toggleValue: function () {
        if (!this.checked) {
            // notify owning group that value changed
            if (this.ownerGroup) {
                this.ownerGroup.setValue(this.inputValue);
            }
            else {
                var els = this.getParent().select('input[name=' + this.el.dom.name + ']');
                els.each(function (el) {
                    if (el.dom.id == this.id) {
                        this.setValue(true);
                    }
                    else {
                        Ext.getCmp(el.dom.id).setValue(false);
                    }
                }, this);
            }
        }
    }
});

/***
 * Formlayout fix (only add items to form if name set)
 */
Ext.override(Ext.FormPanel, {
    initFields: function () {
        var f = this.form;
        var formPanel = this;
        var fn = function (c) {
            if (c.isFormField && c.name) { // only use formfields with a name?
                f.add(c);
            } else if (c.doLayout && c != formPanel) {
                Ext.applyIf(c, {
                    labelAlign: c.ownerCt.labelAlign,
                    labelWidth: c.ownerCt.labelWidth,
                    itemCls: c.ownerCt.itemCls
                });
                if (c.items) {
                    c.items.each(fn);
                }
            }
        };
        this.items.each(fn);
    },

    onAdd: function (ct, c) {
        if (c.isFormField && c.name) {
            this.form.add(c);
        }
    }
});

/**
 * 解决Extjs5选择时调到顶部
 */
Ext.override(Ext.layout.container.VBox, {
    beginLayout: function (ownerContext) {
        var scrollable = this.owner.getScrollable();
        if (scrollable) {
            this.lastScrollPosition = scrollable.getPosition();
        }
        this.callParent(arguments);
    },
    completeLayout: function (ownerContext) {
        var scrollable = this.owner.getScrollable();
        this.callParent(arguments);
        if (scrollable) {
            scrollable.scrollTo(this.lastScrollPosition);
        }
    }
});