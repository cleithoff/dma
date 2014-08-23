/*
 * File: app/view/LoginPanel.js
 *
 * This file was generated by Sencha Architect version 2.2.2.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 4.2.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 4.2.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('MyApp.view.LoginPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.loginpanel',

    singleton: true,

    itemId: 'LoginPanel',
    layout: {
        align: 'stretch',
        type: 'vbox'
    },
    header: false,
    title: 'My Panel',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'container',
                    flex: 1
                },
                {
                    xtype: 'container',
                    layout: {
                        align: 'stretch',
                        type: 'hbox'
                    },
                    items: [
                        {
                            xtype: 'container',
                            flex: 1
                        },
                        {
                            xtype: 'form',
                            itemId: 'LoginFormPanel',
                            width: 320,
                            bodyPadding: 10,
                            title: 'Login',
                            items: [
                                me.processUsername({
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Benutzername',
                                    name: 'username',
                                    listeners: {
                                        specialkey: {
                                            fn: me.onTextfieldSpecialkey,
                                            scope: me
                                        }
                                    }
                                }),
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Passwort',
                                    name: 'password',
                                    inputType: 'password',
                                    listeners: {
                                        specialkey: {
                                            fn: me.onTextfieldSpecialkey1,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'fieldcontainer',
                                    height: 24,
                                    layout: {
                                        align: 'stretch',
                                        type: 'hbox'
                                    },
                                    items: [
                                        {
                                            xtype: 'tbspacer',
                                            flex: 1
                                        },
                                        {
                                            xtype: 'button',
                                            itemId: 'LoginButton',
                                            text: 'Login',
                                            listeners: {
                                                click: {
                                                    fn: me.onButtonClick,
                                                    scope: me
                                                }
                                            }
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'container',
                            flex: 1
                        }
                    ]
                },
                {
                    xtype: 'container',
                    flex: 1
                }
            ]
        });

        me.callParent(arguments);
    },

    processUsername: function(config) {
        config.autocomplete = "on";
        return config;
    },

    onTextfieldSpecialkey: function(field, e, eOpts) {
        var me = this;

        if (e.getKey() == e.ENTER) {
            me.down('#LoginButton').fireEvent('click', me.down('#LoginButton'));
        }
    },

    onTextfieldSpecialkey1: function(field, e, eOpts) {
        var me = this;

        if (e.getKey() == e.ENTER) {
            me.down('#LoginButton').fireEvent('click', me.down('#LoginButton'));
        }
    },

    onButtonClick: function(button, e, eOpts) {
        var me = this;
        var record = button.up('form').getValues();

        Ext.Ajax.request({
            url: '/auth/index/login',
            headers: { 'Content-Type': 'application/json' },
            method: 'POST',
            jsonData: {
                username: record.username,
                password: record.password,
                format: 'json'
            },
            success: function(response){        
                response.json = JSON.parse(response.responseText);
                var store = Ext.getStore('ActiveUserResourcesJsonStore'); 
                store.removeAll(true);
                store.loadData(response.json.data);

                console.log(response);

                me.hide();

                MyApp.app.getMainViewportControllerController().getMainViewport().add(MyApp.app.getMainViewportControllerController().getMainPanel());
                MyApp.app.getRuleControllerController().ruleMainMenuItems();
            },
            failure: function(response) {
                response.json = JSON.parse(response.responseText);
                console.log(response);
                Ext.MessageBox.alert('Fehler', response.json.message);
            }
        });
    }

});