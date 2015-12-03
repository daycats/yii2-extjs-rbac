/**
 * Created by shanli on 2015/9/2.
 */
Ext.define('DP.dp.view.system.config.ConfigController', {
    extend: 'DP.dp.base.FormController',
    alias: 'controller.config',

    saveUrl: getUrl('setting.config.save'),
    dataUrl: getUrl('setting.config.options')
});