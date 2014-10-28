/*
 * File: app/view/UserResourcePanel.js
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

Ext.define('MyApp.view.UserResourcePanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.userresourcepanel',

    itemId: 'UserRolePanel',
    layout: {
        type: 'border'
    },
    closable: true,
    title: 'Resourcen',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                me.processGridPanel({
                    xtype: 'gridpanel',
                    region: 'west',
                    split: true,
                    itemId: 'GridPanel',
                    width: 400,
                    header: false,
                    title: 'My Grid Panel',
                    store: 'UserResourceJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'name',
                            text: 'Bezeichnung',
                            flex: 1
                        }
                    ],
                    dockedItems: [
                        me.processMyPagingToolbar3({
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'UserResourceJsonStore'
                        })
                    ],
                    listeners: {
                        select: {
                            fn: me.onGridPanelSelect,
                            scope: me
                        }
                    }
                }),
                {
                    xtype: 'container',
                    region: 'center',
                    itemId: 'Container',
                    layout: {
                        type: 'border'
                    },
                    items: [
                        {
                            xtype: 'toolbar',
                            region: 'north',
                            itemId: 'ActionToolbar',
                            items: [
                                {
                                    xtype: 'button',
                                    itemId: 'NewButton',
                                    text: 'Neu',
                                    listeners: {
                                        click: {
                                            fn: me.onNewButtonClick,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    disabled: true,
                                    itemId: 'EditButton',
                                    text: 'Bearbeiten',
                                    listeners: {
                                        click: {
                                            fn: me.onEditButtonClick,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    disabled: true,
                                    itemId: 'SaveButton',
                                    text: 'Speichern',
                                    listeners: {
                                        click: {
                                            fn: me.onSaveButtonClick,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    disabled: true,
                                    itemId: 'CancelButton',
                                    text: 'Abbrechen',
                                    listeners: {
                                        click: {
                                            fn: me.onCancelButtonClick,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    disabled: true,
                                    itemId: 'DeleteButton',
                                    text: 'Löschen',
                                    listeners: {
                                        click: {
                                            fn: me.onDeleteButtonClick,
                                            scope: me
                                        }
                                    }
                                }
                            ]
                        },
                        {
                            xtype: 'form',
                            region: 'west',
                            split: true,
                            disabled: true,
                            itemId: 'FormPanel',
                            width: 480,
                            bodyPadding: 10,
                            header: false,
                            title: 'My Form',
                            trackResetOnLoad: true,
                            items: [
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Bezeichnung',
                                    name: 'name'
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Resource',
                                    name: 'slug'
                                }
                            ]
                        },
                        {
                            xtype: 'panel',
                            region: 'center',
                            itemId: 'DummyPanel',
                            header: false,
                            title: 'My Panel'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    },

    processMyPagingToolbar3: function(config) {
        var me = this;

        if (Ext.isEmpty(me.store)) {
            me.store = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.store;

        return config;
    },

    processGridPanel: function(config) {
        var me = this;

        if (Ext.isEmpty(me.store)) {
            me.store = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.store;

        return config;
    },

    onGridPanelSelect: function(rowmodel, record, index, eOpts) {
        MyApp.app.getCrudControllerController().onGridPanelSelect(rowmodel, record, index, eOpts);
    },

    onNewButtonClick: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onNewButtonClick(button, e, eOpts);
    },

    onEditButtonClick: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onEditButtonClick(button, e, eOpts);
    },

    onSaveButtonClick: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onSaveButtonClick(button, e, eOpts);
    },

    onCancelButtonClick: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onCancelButtonClick(button, e, eOpts);
    },

    onDeleteButtonClick: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onDeleteButtonClick(button, e, eOpts);
    }

});