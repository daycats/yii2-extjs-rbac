/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.model.admin.MenuUrlRule', {
    extend: 'Ext.data.Model',

    idProperty: 'rule_id',

    fields: [
        {name: 'rule_id', type: 'int'},
        {name: 'url_id', type: 'int'},
        {name: 'param_name', type: 'string'},
        {name: 'rule', type: 'string'},
        {name: 'note', type: 'string'},
        {name: 'status', type: 'int'}
    ]
});