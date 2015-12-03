/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.model.admin.Group', {
    extend: 'Ext.data.Model',

    idProperty: 'group_id',

    fields: [
        {name: 'group_id', type: 'int'},
        {name: 'name', type: 'string'},
        {name: 'is_system', type: 'int'},
        {name: 'note', type: 'string'},
        {name: 'status', type: 'int'}
    ]
});