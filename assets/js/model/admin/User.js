/**
 * Created by shanli on 2015/8/23.
 */
Ext.define('DP.dp.model.admin.User', {
    extend: 'Ext.data.Model',

    idProperty: 'user_id',

    fields: [
        {name: 'user_id', type: 'int'},
        {name: 'username', type: 'string'},
        {name: 'nickname', type: 'string'},
        {name: 'group_ids', type: 'string'},
        {name: 'group_names', type: 'string'},
        {name: 'is_group_access', type: 'int'},
        {name: 'is_user_access', type: 'int'},
        {name: 'is_system', type: 'int'},
        {name: 'note', type: 'string'},
        {name: 'status', type: 'int'}
    ]
});