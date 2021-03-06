/*
 * File: app/view/MailImapPanel.js
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

Ext.define('MyApp.view.MailImapPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.mailimappanel',

    border: false,
    itemId: 'MailImapPanel',
    layout: {
        type: 'border'
    },
    title: 'Korrespondenz',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                me.processMailImapGridPanel({
                    xtype: 'gridpanel',
                    flex: 1,
                    region: 'west',
                    split: true,
                    border: false,
                    itemId: 'MailImapGridPanel',
                    store: 'MailImapJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'mailTo',
                            text: 'Empfänger',
                            flex: 2
                        },
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'mailFrom',
                            text: 'Absender',
                            flex: 2
                        },
                        {
                            xtype: 'gridcolumn',
                            width: 70,
                            dataIndex: 'mailSubject',
                            text: 'Betreff',
                            flex: 5
                        },
                        {
                            xtype: 'gridcolumn',
                            width: 120,
                            dataIndex: 'mailDate',
                            text: 'Datum'
                        }
                    ],
                    dockedItems: [
                        me.processMyPagingToolbar28({
                            xtype: 'pagingtoolbar',
                            dock: 'bottom',
                            width: 360,
                            displayInfo: true,
                            store: 'MailImapJsonStore'
                        })
                    ],
                    listeners: {
                        select: {
                            fn: me.onMailImapGridPanelSelect,
                            scope: me
                        }
                    }
                }),
                {
                    xtype: 'form',
                    flex: 1,
                    region: 'center',
                    split: true,
                    border: false,
                    itemId: 'MailImapFormPanel',
                    layout: {
                        type: 'border'
                    },
                    items: [
                        {
                            xtype: 'textareafield',
                            region: 'center',
                            name: 'mailMessage',
                            grow: true
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    },

    processMyPagingToolbar28: function(config) {
        var me = this;

        if (Ext.isEmpty(me.store)) {
            me.store = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.store;

        return config;
    },

    processMailImapGridPanel: function(config) {
        var me = this;

        if (Ext.isEmpty(me.store)) {
            me.store = Ext.create('MyApp.store.' + config.store);
        }

        config.store = me.store;

        return config;
    },

    onMailImapGridPanelSelect: function(rowmodel, record, index, eOpts) {
        var me = this;

        MyApp.model.MailImapModel.load(record.data.id, {
            callback: function(record, operation, success) {
                me.down('#MailImapFormPanel').getForm().loadRecord(record);
            }
        });
    }

});