/**
 * Created by shanli on 2015/9/1.
 */
Ext.define('DP.dp.component.form.field.TreeSearch', {
    extend: 'Ext.form.field.Text',
    xtype: 'treesearch',

    emptyText: 'Search',
    enableKeyEvents: true,

    triggers: {
        clear: {
            cls: 'x-form-clear-trigger',
            handler: 'onClearTriggerClick',
            hidden: true,
            scope: 'this'
        },
        search: {
            cls: 'x-form-search-trigger',
            weight: 1,
            handler: 'onSearchTriggerClick',
            scope: 'this'
        }
    },

    onClearTriggerClick: function () {
        this.setValue();
        if (this.store) {
            this.store.clearFilter();
            this.getTrigger('clear').hide();
            this.filterStore(this.store, this.getValue());
        }
    },

    onSearchTriggerClick: function () {
        if (this.store) {
            this.filterStore(this.store, this.getValue())
        }
    },

    listeners: {
        keyup: {
            fn: function (view, e, eOpts) {
                var searchValue = view.getValue();
                view.getTrigger('clear')[(searchValue.length > 0) ? 'show' : 'hide']();
                if (this.store) {
                    this.filterStore(this.store, searchValue);
                }
            },
            buffer: 50
        },
        render: function (view) {
            this.searchField = view
        }
    },
    filterStore: function(h, l) {
        var me = this,
            name = l.toLowerCase(),
            fn = function(record) {
                var child = record.childNodes,
                    len = child && child.length,
                    a = j.test(record.get('origin_text')),
                    d,
                    result = a,
                    isResult = false;
                record.set('text', me.strMarkRedPlus(name, record.get('origin_text')));
                if (!a) {
                    for (d = 0; d < len; d++) {
                        if (child[d].isLeaf()) {
                            a = child[d].get('visible')
                        } else {
                            a = fn(child[d]);
                        }
                        child[d].set('text', me.strMarkRedPlus(name, child[d].get('origin_text')));
                        if (a && !isResult) {
                            result = a;
                            isResult = true;
                        }
                    }
                } else {
                    for (d = 0; d < len; d++) {
                        child[d].set('text', me.strMarkRedPlus(name, child[d].get('origin_text')));
                        child[d].set('visible', true);
                    }
                }
                return result;
            }, j;
        if (name.length < 1) {
            h.clearFilter()
        }
        j = new RegExp(name, 'i');
        h.getFilters().replaceAll({
            filterFn: fn
        });
    },
    strMarkRedPlus: function(c, d) {
        return d.replace(new RegExp('(' + c + ')', 'gi'), '<span style="color: red;"><b>$1</b></span>')
    }
});