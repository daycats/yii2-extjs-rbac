/**
 * Created by shanli on 2015/8/30.
 */
Ext.define('DP.dp.model.admin.Menu', {
    extend: 'Ext.data.TreeModel',

    fields: [
        {name: 'menu_id', type: 'int'},
        {name: 'parent_id', type: 'int'},
        {name: 'tab_id', type: 'string'},
        {name: 'text', type: 'string'},
        {name: 'origin_text', type: 'string'},
        {name: 'title', type: 'string'},
        {name: 'url', type: 'string'},
        {name: 'view_package', type: 'string'},
        {name: 'icon_cls', type: 'string'},
        {name: 'expanded', type: 'boolean'},
        {name: 'closable', type: 'boolean'},
        {name: 'is_folder', type: 'boolean'},
        {name: 'is_expand', type: 'boolean'},
        {name: 'is_open_url', type: 'boolean'},
        {name: 'is_open_target', type: 'boolean'},
        {name: 'is_every_open', type: 'boolean'},
        {name: 'display_order', type: 'int'},
        {name: 'params', type: 'string'},
        {name: 'note', type: 'string'},
        {name: 'leaf', type: 'boolean'},
        {name: 'is_hide', type: 'boolean'},
        {name: 'status', type: 'int'}
    ]
});