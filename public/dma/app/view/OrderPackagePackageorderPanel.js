/*
 * File: app/view/OrderPackagePackageorderPanel.js
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

Ext.define('MyApp.view.OrderPackagePackageorderPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.orderpackagepackageorderpanel',

    height: 492,
    itemId: 'OrderPackagePackageorderPanel',
    width: 400,
    layout: {
        type: 'border'
    },
    title: 'Pakete',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    itemId: 'OrderPackagePackageorderToolbar',
                    items: [
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'OrderPackagePackageorderEditButton',
                            text: 'Bearbeiten',
                            listeners: {
                                afterrender: {
                                    fn: me.onOrderItemPackagePackageEditButtonAfterRender,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'OrderPackagePackageorderSaveButton',
                            text: 'Speichern',
                            listeners: {
                                afterrender: {
                                    fn: me.onOrderItemPackagePackageSaveButtonAfterRender,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'OrderPackagePackageorderCancelButton',
                            text: 'Abbrechen',
                            listeners: {
                                afterrender: {
                                    fn: me.onOrderItemPackagePackageCancelButtonAfterRender,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'OrderPackagePackageorderDeleteButton',
                            text: 'Löschen',
                            listeners: {
                                afterrender: {
                                    fn: me.onOrderItemPackagePackageDeleteButtonAfterRender,
                                    scope: me
                                }
                            }
                        },
                        {
                            xtype: 'button',
                            itemId: 'OrderPackagePackageorderRefreshButton',
                            text: 'Aktualisieren',
                            listeners: {
                                click: {
                                    fn: me.onOrderPackagePackageorderRefreshButtonClick,
                                    scope: me
                                }
                            }
                        }
                    ]
                }
            ],
            items: [
                {
                    xtype: 'form',
                    region: 'north',
                    disabled: true,
                    height: 150,
                    itemId: 'OrderPackagePackageorderFormPanel',
                    bodyPadding: 10,
                    trackResetOnLoad: true,
                    items: [
                        {
                            xtype: 'displayfield',
                            anchor: '100%',
                            fieldLabel: 'Mantel',
                            name: 'frame_type_title'
                        },
                        {
                            xtype: 'displayfield',
                            anchor: '100%',
                            fieldLabel: 'Paket',
                            name: 'package_type_title'
                        },
                        {
                            xtype: 'checkboxfield',
                            anchor: '100%',
                            name: 'readytosend',
                            boxLabel: 'fertig zum Versand',
                            inputValue: '1',
                            uncheckedValue: '0'
                        },
                        {
                            xtype: 'datefield',
                            anchor: '100%',
                            fieldLabel: 'Ausgangsdatum',
                            name: 'outgoing',
                            altFormats: 'm/d/Y|n/j/Y|n/j/y|m/j/y|n/d/y|m/j/Y|n/d/Y|m-d-y|m-d-Y|m/d|m-d|md|mdy|mdY|d|Y-m-d|n-j|n/j|d.m.Y',
                            format: 'd.m.Y',
                            submitFormat: 'Y-m-d'
                        },
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            fieldLabel: 'Sendungsnummer',
                            name: 'sendingnumber'
                        }
                    ]
                },
                {
                    xtype: 'gridpanel',
                    region: 'center',
                    itemId: 'OrderPackagePackageorderGridPanel',
                    store: 'PackagePackageorderJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                return record.data.frame_type.title;
                            },
                            dataIndex: 'frame_type.title',
                            text: 'Mantel'
                        },
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                return record.data.package_type.title;
                            },
                            dataIndex: 'package_type.title',
                            text: 'Paket'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'amount',
                            text: 'Menge'
                        },
                        {
                            xtype: 'numbercolumn',
                            dataIndex: 'weight',
                            text: 'Gewicht',
                            format: '0,000.0000'
                        },
                        {
                            xtype: 'datecolumn',
                            dataIndex: 'outgoing',
                            text: 'Ausgang'
                        },
                        {
                            xtype: 'checkcolumn',
                            dataIndex: 'readytosend',
                            text: 'versandfertig'
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'sendingnumber',
                            text: 'Sendungsnummer'
                        },
                        {
                            xtype: 'gridcolumn',
                            hidden: true,
                            dataIndex: 'authkey',
                            text: 'Authkey'
                        }
                    ],
                    listeners: {
                        celldblclick: {
                            fn: me.onOrderPackagePackageorderGridPanelCellDblClick,
                            scope: me
                        }
                    }
                }
            ]
        });

        me.callParent(arguments);
    },

    onOrderItemPackagePackageEditButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderPackagePackageorderPanel', MyApp.app.getRuleControllerController().rights.UPDATE));
    },

    onOrderItemPackagePackageSaveButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderPackagePackageorderPanel', MyApp.app.getRuleControllerController().rights.UPDATE));
    },

    onOrderItemPackagePackageCancelButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderPackagePackageorderPanel', MyApp.app.getRuleControllerController().rights.UPDATE));
    },

    onOrderItemPackagePackageDeleteButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderPackagePackageorderPanel', MyApp.app.getRuleControllerController().rights.DELETE));
    },

    onOrderPackagePackageorderRefreshButtonClick: function(button, e, eOpts) {
        var me = this;

        Ext.Ajax.request({
            url: '/package/packageorder/refresh',
            method: 'GET',
            params: {"filter": JSON.stringify([{"property":"order_combine_id","value":me.record.data.order_combine_id}])},
            success: function(response, opts) {
                var obj = Ext.decode(response.responseText);
                console.dir(obj);

                var grid = me.down('#OrderPackagePackageorderGridPanel');
                grid.store.clearFilter(true);
                grid.store.filter([{property:'order_combine_id',value:me.record.data.order_combine_id}]);
                grid.store.load();
                if (!Ext.isEmpty(record.data.comment)) {
                    Ext.MessageBox.alert('Hinweis', record.data.comment);
                }

            },
            failure: function(response, opts) {
                console.log('server-side failure with status code ' + response.status);
            }
        });




    },

    onOrderPackagePackageorderGridPanelCellDblClick: function(tableview, td, cellIndex, record, tr, rowIndex, e, eOpts) {
        var grid = tableview.up('#OrderPanel').down('#OrderOrderGridPanel');

        if (grid.getSelectionModel().getSelection().length > 0) {
            order = grid.getSelectionModel().getSelection()[0];

            Ext.Ajax.request({
                url: '/partner/address/index',
                method: 'GET',
                success: function(response) {
                    partner_address = JSON.parse(response.responseText);
                    console.log('http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&zip=' + partner_address.data[0].post_plz + '&idc=' + record.data.sendingnumber);
                    win = window.open('http://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&zip=' + partner_address.data[0].post_plz + '&idc=' + record.data.sendingnumber,'_blank'); 
                    win.focus();
                },
                failure: function() {},
                params: { id: order.data.partner_partner.partner_address_id_delivery}
            });

        }

    }

});