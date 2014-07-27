/*
 * File: app/view/AppHelpPanel.js
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

Ext.define('MyApp.view.AppHelpPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.apphelppanel',

    itemId: 'AppHelpPanel',
    layout: {
        type: 'border'
    },
    closable: true,
    closeAction: 'hide',
    title: 'Hilfe und Dokumentation',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    itemId: 'AppHelpToolbar',
                    items: [
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'AppHelpEditButton',
                            text: 'Bearbeiten'
                        },
                        {
                            xtype: 'button',
                            itemId: 'AppHelpNewButton',
                            text: 'Neu'
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'AppHelpSaveButton',
                            text: 'Speichern'
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'AppHelpCancelButton',
                            text: 'Abbrechen'
                        },
                        {
                            xtype: 'button',
                            disabled: true,
                            itemId: 'AppHelpDeleteButton',
                            text: 'Löschen'
                        }
                    ]
                }
            ],
            items: [
                {
                    xtype: 'gridpanel',
                    region: 'west',
                    split: true,
                    itemId: 'AppHelpGridPanel',
                    width: 150,
                    store: 'AppHelpJsonStore',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'title',
                            text: 'Thema',
                            flex: 1
                        }
                    ]
                },
                {
                    xtype: 'form',
                    region: 'center',
                    split: true,
                    disabled: true,
                    itemId: 'AppHelpFormPanel',
                    layout: {
                        align: 'stretch',
                        type: 'vbox'
                    },
                    bodyPadding: 10,
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Title',
                            name: 'title'
                        },
                        {
                            xtype: 'htmleditor',
                            flex: 1,
                            height: 436,
                            itemId: 'text',
                            fieldLabel: 'Beschreibung',
                            name: 'text'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});