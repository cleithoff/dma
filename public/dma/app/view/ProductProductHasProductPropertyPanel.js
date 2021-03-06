/*
 * File: app/view/ProductProductHasProductPropertyPanel.js
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

Ext.define('MyApp.view.ProductProductHasProductPropertyPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.productproducthasproductpropertypanel',

    layout: {
        type: 'border'
    },
    header: false,
    title: 'Eigenschaften',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                me.processBagGridPanel({
                    xtype: 'gridpanel',
                    flex: 1,
                    region: 'west',
                    itemId: 'BagGridPanel',
                    width: 150,
                    title: 'verwendete Eigenschaften',
                    store: 'ProductProductHasProductPropertyJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'label',
                            text: 'Bezeichnung',
                            flex: 1
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'key',
                            text: 'Key',
                            flex: 1
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'value',
                            text: 'Wert',
                            flex: 1,
                            editor: {
                                xtype: 'textfield'
                            }
                        }
                    ],
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'right',
                            items: [
                                {
                                    xtype: 'button',
                                    itemId: 'LinkButton',
                                    text: '<',
                                    listeners: {
                                        click: {
                                            fn: me.onLinkButtonClick1,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    itemId: 'UnlinkButton',
                                    text: '>',
                                    listeners: {
                                        click: {
                                            fn: me.onUnlinkButtonClick1,
                                            scope: me
                                        }
                                    }
                                }
                            ]
                        },
                        me.processMyPagingToolbar11({
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'ProductProductHasProductPropertyJsonStore'
                        })
                    ],
                    plugins: [
                        Ext.create('Ext.grid.plugin.CellEditing', {

                        })
                    ]
                }),
                me.processLibGridPanel({
                    xtype: 'gridpanel',
                    flex: 1,
                    region: 'center',
                    itemId: 'LibGridPanel',
                    title: 'verfügbare Eigenschaften',
                    store: 'ProductPropertyJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'label',
                            text: 'Bezeichnung',
                            flex: 1
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'key',
                            text: 'Key',
                            flex: 1
                        }
                    ],
                    dockedItems: [
                        me.processMyPagingToolbar12({
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'ProductPropertyJsonStore'
                        })
                    ]
                })
            ]
        });

        me.callParent(arguments);
    },

    processMyPagingToolbar11: function(config) {
        var me = this;

        if (Ext.isEmpty(me.linkbagstore)) {
            me.linkbagstore = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.linkbagstore;

        return config;
    },

    processBagGridPanel: function(config) {
        var me = this;

        if (Ext.isEmpty(me.linkbagstore)) {
            me.linkbagstore = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.linkbagstore;

        return config;
    },

    processMyPagingToolbar12: function(config) {
        var me = this;

        if (Ext.isEmpty(me.linklibstore)) {
            me.linklibstore = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.linklibstore;

        return config;
    },

    processLibGridPanel: function(config) {
        var me = this;

        if (Ext.isEmpty(me.linklibstore)) {
            me.linklibstore = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.linklibstore;

        return config;
    },

    onLinkButtonClick1: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onLinkButtonClick(button, e, eOpts, function(linkrecord, librecord) {

            return {
                product_product_id: linkrecord.data.id,
                product_property_id: librecord.data.id
            };

        });
    },

    onUnlinkButtonClick1: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onUnlinkButtonClick(button, e, eOpts);
    }

});