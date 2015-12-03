/**
 * Created by shanli on 2015/9/11.
 */
Ext.define('DP.dp.base.form.HtmlEditorVideoFormWindow', {
    extend: 'DP.dp.base.window.Window',

    requires: [
        'Ext.button.Segmented',
        'Ext.container.Container',
        'Ext.form.FieldSet',
        'Ext.form.Panel',
        'Ext.form.field.Number',
        'Ext.form.field.Text',
        'Ext.layout.container.Column',
        'Ext.layout.container.VBox',
        'DP.dp.base.form.HtmlEditorVideoFormWindowController'
    ],

    viewModel: {
        data: {
            videoCode: ''
        }
    },

    controller: 'html-editor-video-form-window',

    title: '视频',
    width: 600,
    resizable: false,
    maximizable: false,
    defaultFocus: 'url',

    items: [{
        xtype: 'form',
        bodyPadding: 10,
        layout: {
            type: 'vbox',
            align: 'stretch'
        },
        fieldDefaults: {
            msgTarget: 'side',
            labelWidth: 60
        },
        items: [{
            xtype: 'textfield',
            fieldLabel: '视频网址',
            allowBlank: false,
            name: 'url',
            itemId: 'url',
            vtype: 'url',
            listeners: {
                change: 'onChange'
            }
        }, {
            xtype: 'container',
            layout: 'column',
            height: 280,
            items: [{
                xtype: 'container',
                layout: 'fit',
                columnWidth: 0.75,
                height: '100%',
                margin: '0 10 0 0',
                style: {
                    background: '#ddd'
                },
                bind: {
                    html: '{videoCode}'
                }
            }, {
                xtype: 'container',
                columnWidth: 0.25,
                layout: {
                    type: 'vbox',
                    align: 'stretch'
                },
                items: [{
                    xtype: 'fieldset',
                    title: '视频尺寸',
                    layout: {
                        type: 'vbox',
                        align: 'stretch'
                    },
                    fieldDefaults: {
                        msgTarget: 'side',
                        labelWidth: 40
                    },
                    items: [{
                        xtype: 'numberfield',
                        fieldLabel: '宽度',
                        minValue: 0,
                        value: 420,
                        name: 'width'
                    }, {
                        xtype: 'numberfield',
                        fieldLabel: '高度',
                        minValue: 0,
                        value: 280,
                        name: 'height'
                    }]
                }, {
                    xtype: 'fieldset',
                    title: '对齐方式',
                    items: [{
                        xtype: 'segmentedbutton',
                        margin: 10,
                        name: 'align',
                        itemId: 'align',
                        value: 'none',
                        items: [{
                            text: '无',
                            value: 'none'
                        }, {
                            text: '左',
                            value: 'left'
                        }, {
                            text: '中',
                            value: 'center'
                        }, {
                            text: '右',
                            value: 'right'
                        }]
                    }]
                }]
            }]
        }],
        buttons: [{
            text: '取消',
            handler: 'onFormCancel'
        }, {
            text: '保存',
            handler: 'onFormSubmit'
        }]
    }]
});