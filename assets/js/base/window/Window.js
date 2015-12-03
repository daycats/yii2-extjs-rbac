/**
 * Created by shanli on 2015/9/3.
 */
Ext.define('DP.dp.base.window.Window', {
    extend: 'Ext.window.Window',

    requires: [
        'Ext.layout.container.Fit'
    ],

    layout: 'fit',
    resizable: true,
    modal: true,
    defaultFocus: 'name',
    closeAction: 'hide',
    constrainHeader:  true,
    maximizable: true,
    //maxHeight: 500,
    shadow: '0',
    defaults: {
        autoScroll: true
    }
});