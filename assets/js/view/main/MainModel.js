/**
 * This class is the view model for the Main view of the application.
 */
Ext.define('DP.dp.view.main.MainModel', {
    extend: 'Ext.app.ViewModel',

    alias: 'viewmodel.main',

    data: {
        name: getConfig('system.name'),
        user: user
    }
});