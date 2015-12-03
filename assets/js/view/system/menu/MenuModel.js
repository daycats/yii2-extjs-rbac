/**
 * Created by shanli on 2015/8/31.
 */
Ext.define('DP.dp.view.system.menu.MenuModel', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.menu',

    stores: {
        /*
        A declaration of Ext.data.Store configurations that are first processed as binds to produce an effective
        store configuration. For example:

        users: {
            model: 'Menu',
            autoLoad: true
        }
        */
    },

    data: {
        itemCount: 0,
        selectItemCount: 0
    }
});