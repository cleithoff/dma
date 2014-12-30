/*
 * File: app/view/PartnerAbsencePanel.js
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

Ext.define('MyApp.view.PartnerAbsencePanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.partnerabsencepanel',

    height: 250,
    itemId: 'PartnerAbsencePanel',
    width: 400,
    autoDestroy: false,
    layout: {
        type: 'border'
    },
    title: 'Abwesenheiten',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'gridpanel',
                    region: 'center',
                    itemId: 'PartnerAbsenceGridPanel',
                    header: false,
                    title: 'My Grid Panel',
                    store: 'PartnerAbsenceJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                return record.data.partner_partner.partner_nr;
                            },
                            dataIndex: 'number',
                            text: 'PartnerNr'
                        },
                        {
                            xtype: 'gridcolumn',
                            renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                return record.data.partner_partner.title
                            },
                            dataIndex: 'string',
                            text: 'Partner'
                        },
                        {
                            xtype: 'datecolumn',
                            dataIndex: 'from',
                            text: 'von'
                        },
                        {
                            xtype: 'datecolumn',
                            dataIndex: 'until',
                            text: 'bis'
                        }
                    ],
                    dockedItems: [
                        {
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'PartnerAbsenceJsonStore'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});