/*
 * File: app/view/ProductItemPanel.js
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

Ext.define('MyApp.view.ProductItemPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.productitempanel',

    border: false,
    layout: {
        type: 'border'
    },
    title: 'Artikel',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                me.processGridPanel({
                    xtype: 'gridpanel',
                    region: 'west',
                    split: true,
                    itemId: 'GridPanel',
                    width: 150,
                    header: false,
                    title: 'My Grid Panel',
                    store: 'ProductItemJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'title',
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
                            store: 'ProductItemJsonStore'
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
                            width: 320,
                            bodyPadding: 10,
                            header: false,
                            title: 'My Form',
                            trackResetOnLoad: true,
                            items: [
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Bezeichnung',
                                    name: 'title'
                                },
                                {
                                    xtype: 'combobox',
                                    anchor: '100%',
                                    fieldLabel: 'Produkt Layout',
                                    name: 'product_layout_id',
                                    displayField: 'description',
                                    store: 'ProductLayoutJsonStore',
                                    valueField: 'id'
                                },
                                {
                                    xtype: 'numberfield',
                                    anchor: '100%',
                                    fieldLabel: 'Preis',
                                    name: 'price'
                                },
                                {
                                    xtype: 'numberfield',
                                    anchor: '100%',
                                    fieldLabel: 'Gewicht',
                                    name: 'weight',
                                    decimalPrecision: 4
                                },
                                {
                                    xtype: 'checkboxfield',
                                    anchor: '100%',
                                    hideEmptyLabel: false,
                                    name: 'released',
                                    boxLabel: 'Freigegeben'
                                },
                                {
                                    xtype: 'numberfield',
                                    anchor: '100%',
                                    fieldLabel: 'Anzahl verfügbar',
                                    name: 'amount_available'
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Artikelnummer',
                                    name: 'product_item_no'
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'ArtikelNr intern',
                                    name: 'product_item_no_internal'
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'ArtikelNr extern',
                                    name: 'product_item_no_external'
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'ArtikelNr intern Lager',
                                    name: 'product_item_no_internal_stock'
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'ArtikelNr extern Lager',
                                    name: 'product_item_no_external_stock'
                                }
                            ]
                        },
                        {
                            xtype: 'tabpanel',
                            region: 'center',
                            itemId: 'DummyPanel',
                            header: false,
                            title: 'My Panel'
                        }
                    ]
                }
            ],
            listeners: {
                afterrender: {
                    fn: me.onPanelAfterRender,
                    scope: me
                }
            }
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


        var productweightpanel = eOpts.scope.down('productweightpanel');

        productweightpanel.masterRecord = record;

        var productweightgrid = productweightpanel.down('#GridPanel');
        var productweightstore = productweightgrid.getStore();

        productweightstore.clearFilter(true);
        productweightstore.filter([{property:"product_item_id", value: record.data.id}]);
        productweightstore.load({
            callback: function(records, operation, success) {
                productweightgrid.getSelectionModel().select(0);
            }
        });

        //if (!Ext.isEmpty(eOpts.scope.down('productitemhasproductcustomizepanel'))) {

        var bagstore = eOpts.scope.down('productitemhasproductcustomizepanel').down('#BagGridPanel').getStore();

        bagstore.clearFilter(true);
        bagstore.filter([{property: 'product_item_id', value: record.data.id}]);
        bagstore.load();

        eOpts.scope.down('productitemhasproductcustomizepanel').linkrecord = record;
        //}

    },

    onNewButtonClick: function(button, e, eOpts) {
        MyApp.app.getCrudControllerController().onNewButtonClick(button, e, eOpts, function(masterRecord, record) {
            record.data.product_product_id = masterRecord.data.id;
        });
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
    },

    onPanelAfterRender: function(component, eOpts) {
        eOpts.scope.down('#GridPanel').getStore().load({
            callback: function(records, operation, success) {

                var productWeightPanel = Ext.create('MyApp.view.ProductWeightPanel', {region: 'center'});
                eOpts.scope.down('#DummyPanel').add(productWeightPanel);



                var productItemHasProductCustomizePanel = Ext.create('MyApp.view.ProductItemHasProductCustomizePanel', {region: 'center'}); 
                eOpts.scope.down('#DummyPanel').add(productItemHasProductCustomizePanel);
                var bagstore = productItemHasProductCustomizePanel.down('#BagGridPanel').getStore();

                console.log(bagstore);

                bagstore.clearFilter(true);
                bagstore.filter([{property: 'product_item_id', value: records[0].data.id}]);
                bagstore.load();
                productItemHasProductCustomizePanel.linkrecord = records[0];

                eOpts.scope.down('#GridPanel').getSelectionModel().select(0);

            }
        });
    }

});