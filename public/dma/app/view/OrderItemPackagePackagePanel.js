/*
 * File: app/view/OrderItemPackagePackagePanel.js
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

Ext.define('MyApp.view.OrderItemPackagePackagePanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.orderitempackagepackagepanel',

    height: 492,
    itemId: 'OrderItemPackagePackagePanel',
    width: 400,
    title: 'Pakete',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    itemId: 'OrderItemPackagePackageToolbar',
                    items: [
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'OrderItemPackagePackageEditButton',
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
                            itemId: 'OrderItemPackagePackageSaveButton',
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
                            itemId: 'OrderItemPackagePackageCancelButton',
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
                            itemId: 'OrderItemPackagePackageDeleteButton',
                            text: 'Löschen',
                            listeners: {
                                afterrender: {
                                    fn: me.onOrderItemPackagePackageDeleteButtonAfterRender,
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
                    disabled: true,
                    itemId: 'OrderItemPackagePackageFormPanel',
                    bodyPadding: 10,
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
                    itemId: 'OrderItemPackagePackageGridPanel',
                    store: 'PackagePackageJsonStore',
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
                                console.log(record);
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
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    },

    onOrderItemPackagePackageEditButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderItemPackagePackagePanel', MyApp.app.getRuleControllerController().rights.UPDATE));
    },

    onOrderItemPackagePackageSaveButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderItemPackagePackagePanel', MyApp.app.getRuleControllerController().rights.UPDATE));
    },

    onOrderItemPackagePackageCancelButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderItemPackagePackagePanel', MyApp.app.getRuleControllerController().rights.UPDATE));
    },

    onOrderItemPackagePackageDeleteButtonAfterRender: function(component, eOpts) {
        component.setVisible(MyApp.app.getRuleControllerController().allow('OrderItemPackagePackagePanel', MyApp.app.getRuleControllerController().rights.DELETE));
    }

});