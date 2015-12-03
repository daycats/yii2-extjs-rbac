/**
 * Created by shanli on 2015/9/9.
 */
Ext.define('DP.dp.model.admin.MenuUrlLink', {
    extend: 'Ext.data.Model',

    idProperty: 'link_id',

    fields: [
        {name: 'link_id', type: 'int'},
        {name: 'menu_id', type: 'int'},
        {name: 'url_id', type: 'int'},
        {name: 'url.url_id', type: 'int'},
        {name: 'url.name', type: 'string'},
        {name: 'url.alias', type: 'string'},
        {name: 'url.route', type: 'string'},
        {name: 'url.method', type: 'string'},
        {name: 'url.host', type: 'string'},
        {name: 'url.enable_rule', type: 'int'},
        {name: 'url.note', type: 'string'},
        {name: 'url.status', type: 'int'}
    ]
});