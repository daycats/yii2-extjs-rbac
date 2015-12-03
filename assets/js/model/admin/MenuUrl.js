/**
 * Created by shanli on 2015/9/8.
 */
Ext.define('DP.dp.model.admin.MenuUrl', {
    extend: 'Ext.data.Model',

    idProperty: 'url_id',

    fields: [
        {name: 'url_id', type: 'int'},
        {name: 'name', type: 'string'},
        {name: 'alias', type: 'string'},
        {name: 'route', type: 'string'},
        {name: 'method', type: 'string'},
        {name: 'host', type: 'string'},
        {name: 'enable_rule', type: 'int'},
        {name: 'note', type: 'string'},
        {name: 'status', type: 'int'}
    ]
});