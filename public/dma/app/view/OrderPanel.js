/*
 * File: app/view/OrderPanel.js
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

Ext.define('MyApp.view.OrderPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.orderpanel',

    height: 531,
    itemId: 'OrderPanel',
    width: 767,
    layout: {
        type: 'border'
    },
    closable: true,
    closeAction: 'hide',
    title: 'Bestellungen',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'gridpanel',
                    region: 'west',
                    split: true,
                    itemId: 'OrderOrderGridPanel',
                    width: 320,
                    store: 'OrderOrderJsonStore',
                    columns: [
                        {
                            xtype: 'numbercolumn',
                            hidden: true,
                            maxWidth: 100,
                            width: 60,
                            align: 'right',
                            dataIndex: 'id',
                            text: 'ID',
                            format: '0'
                        },
                        {
                            xtype: 'numbercolumn',
                            maxWidth: 100,
                            width: 60,
                            align: 'right',
                            dataIndex: 'order_pool_id',
                            text: 'BestellID',
                            format: '0'
                        },
                        {
                            xtype: 'gridcolumn',
                            maxWidth: 100,
                            align: 'right',
                            dataIndex: 'order_no_external',
                            text: 'Auftrag-Nr.',
                            flex: 1
                        },
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                return record.data.partner_partner.partner_nr;
                            },
                            maxWidth: 100,
                            align: 'right',
                            dataIndex: 'partner_partner.partner_nr',
                            text: 'Partner-Nr.',
                            flex: 1
                        },
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                return record.data.partner_partner.title;
                            },
                            dataIndex: 'partner_partner.title',
                            text: 'Partner',
                            flex: 2
                        },
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                if (Ext.isEmpty(record.data.import_import)) return '';

                                return record.data.import_import.title;
                            },
                            hidden: true,
                            maxWidth: 120,
                            dataIndex: 'partner_partner.title',
                            text: 'Import',
                            flex: 2
                        },
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                if (Ext.isEmpty(record.data.import_stack)) return '';

                                return record.data.import_stack.title;
                            },
                            hidden: true,
                            maxWidth: 300,
                            dataIndex: 'partner_partner.title',
                            text: 'Importstack',
                            flex: 2
                        }
                    ],
                    dockedItems: [
                        {
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'OrderOrderJsonStore'
                        },
                        {
                            xtype: 'form',
                            dock: 'top',
                            itemId: 'OrderOrderGridFilterFormPanel',
                            width: 100,
                            bodyPadding: 10,
                            items: [
                                {
                                    xtype: 'numberfield',
                                    anchor: '100%',
                                    fieldLabel: 'Partner-Nr.',
                                    name: 'partner_nr',
                                    decimalPrecision: 0,
                                    listeners: {
                                        specialkey: {
                                            fn: me.onNumberfieldSpecialkey,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'numberfield',
                                    anchor: '100%',
                                    fieldLabel: 'Partner ID',
                                    name: 'id',
                                    decimalPrecision: 0,
                                    listeners: {
                                        specialkey: {
                                            fn: me.onNumberfieldSpecialkey1,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'textfield',
                                    anchor: '100%',
                                    fieldLabel: 'Partner',
                                    name: 'title',
                                    listeners: {
                                        specialkey: {
                                            fn: me.onTextfieldSpecialkey,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'combobox',
                                    anchor: '100%',
                                    fieldLabel: 'Import',
                                    name: 'import_import_id',
                                    autoSelect: false,
                                    displayField: 'title',
                                    store: 'ImportImportJsonStore',
                                    valueField: 'id',
                                    listeners: {
                                        specialkey: {
                                            fn: me.onTextfieldSpecialkey1,
                                            scope: me
                                        },
                                        select: {
                                            fn: me.onComboboxSelect,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'combobox',
                                    anchor: '100%',
                                    fieldLabel: 'Importstapel',
                                    name: 'import_stack_id',
                                    autoSelect: false,
                                    displayField: 'title',
                                    store: 'ImportStackJsonStore',
                                    valueField: 'id',
                                    listeners: {
                                        specialkey: {
                                            fn: me.onTextfieldSpecialkey11,
                                            scope: me
                                        },
                                        select: {
                                            fn: me.onComboboxSelect1,
                                            scope: me
                                        }
                                    }
                                },
                                {
                                    xtype: 'button',
                                    itemId: 'OrderOrderFilterButton',
                                    text: 'Filtern'
                                },
                                {
                                    xtype: 'button',
                                    itemId: 'OrderOrderClearFilterButton',
                                    text: 'Filter leeren'
                                }
                            ]
                        }
                    ]
                },
                {
                    xtype: 'tabpanel',
                    region: 'center',
                    split: true,
                    itemId: 'OrderOrderTabPanel'
                }
            ]
        });

        me.callParent(arguments);
    },

    onNumberfieldSpecialkey: function(field, e, eOpts) {
        // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
        // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
        if (e.getKey() == e.ENTER) {
            field.up('form').down('#OrderOrderFilterButton').fireEvent('click', field.up('form').down('#OrderOrderFilterButton'));
        }

    },

    onNumberfieldSpecialkey1: function(field, e, eOpts) {
        // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
        // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
        if (e.getKey() == e.ENTER) {
            field.up('form').down('#OrderOrderFilterButton').fireEvent('click', field.up('form').down('#OrderOrderFilterButton'));
        }
    },

    onTextfieldSpecialkey: function(field, e, eOpts) {
        // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
        // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
        if (e.getKey() == e.ENTER) {
            field.up('form').down('#OrderOrderFilterButton').fireEvent('click', field.up('form').down('#OrderOrderFilterButton'));
        }
    },

    onTextfieldSpecialkey1: function(field, e, eOpts) {
        // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
        // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
        if (e.getKey() == e.ENTER) {
            field.up('form').down('#OrderOrderFilterButton').fireEvent('click', field.up('form').down('#OrderOrderFilterButton'));
        }
    },

    onComboboxSelect: function(combo, records, eOpts) {
        combo.up('form').down('#OrderOrderFilterButton').fireEvent('click', combo.up('form').down('#OrderOrderFilterButton'));

    },

    onTextfieldSpecialkey11: function(field, e, eOpts) {
        // e.HOME, e.END, e.PAGE_UP, e.PAGE_DOWN,
        // e.TAB, e.ESC, arrow keys: e.LEFT, e.RIGHT, e.UP, e.DOWN
        if (e.getKey() == e.ENTER) {
            field.up('form').down('#OrderOrderFilterButton').fireEvent('click', field.up('form').down('#OrderOrderFilterButton'));
        }
    },

    onComboboxSelect1: function(combo, records, eOpts) {
        combo.up('form').down('#OrderOrderFilterButton').fireEvent('click', combo.up('form').down('#OrderOrderFilterButton'));

    }

});