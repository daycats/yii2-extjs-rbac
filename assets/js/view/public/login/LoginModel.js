/**
 * Created by shanli on 2015/9/3.
 */
Ext.define('DP.dp.view.public.login.LoginModel', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.login',

    stores: {
        /*
        A declaration of Ext.data.Store configurations that are first processed as binds to produce an effective
        store configuration. For example:

        users: {
            model: 'Login',
            autoLoad: true
        }
        */
    },

    data: {
        name: getConfig('system.name')
    }
});