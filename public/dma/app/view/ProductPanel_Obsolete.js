/*
 * File: app/view/ProductPanel_Obsolete.js
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

Ext.define('MyApp.view.ProductPanel_Obsolete', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.productpanel_Obsolete',

    itemId: 'ProductPanel',
    layout: {
        type: 'border'
    },
    closable: true,
    closeAction: 'hide',
    title: 'Produkte',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'gridpanel',
                    region: 'west',
                    split: true,
                    width: 150,
                    header: false,
                    title: 'My Grid Panel',
                    columns: [
                        {
                            xtype: 'gridcolumn',
                            dataIndex: 'string',
                            text: 'String'
                        },
                        {
                            xtype: 'numbercolumn',
                            dataIndex: 'number',
                            text: 'Number'
                        },
                        {
                            xtype: 'datecolumn',
                            dataIndex: 'date',
                            text: 'Date'
                        },
                        {
                            xtype: 'booleancolumn',
                            dataIndex: 'bool',
                            text: 'Boolean'
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    region: 'center',
                    border: false,
                    layout: {
                        type: 'border'
                    },
                    header: false,
                    title: 'My Panel',
                    items: [
                        {
                            xtype: 'tabpanel',
                            region: 'north',
                            split: true,
                            height: 320,
                            activeTab: 0,
                            items: [
                                {
                                    xtype: 'panel',
                                    layout: {
                                        type: 'border'
                                    },
                                    title: 'Produkt',
                                    dockedItems: [
                                        {
                                            xtype: 'toolbar',
                                            dock: 'top',
                                            items: [
                                                {
                                                    xtype: 'button',
                                                    text: 'MyButton'
                                                },
                                                {
                                                    xtype: 'button',
                                                    text: 'MyButton'
                                                },
                                                {
                                                    xtype: 'button',
                                                    text: 'MyButton'
                                                },
                                                {
                                                    xtype: 'button',
                                                    text: 'MyButton'
                                                },
                                                {
                                                    xtype: 'button',
                                                    text: 'MyButton'
                                                }
                                            ]
                                        }
                                    ],
                                    items: [
                                        {
                                            xtype: 'form',
                                            region: 'west',
                                            split: true,
                                            width: 320,
                                            bodyPadding: 10,
                                            header: false,
                                            title: 'My Form',
                                            items: [
                                                {
                                                    xtype: 'textfield',
                                                    anchor: '100%',
                                                    fieldLabel: 'Label'
                                                },
                                                {
                                                    xtype: 'combobox',
                                                    anchor: '100%',
                                                    fieldLabel: 'Label'
                                                },
                                                {
                                                    xtype: 'combobox',
                                                    anchor: '100%',
                                                    fieldLabel: 'Label'
                                                },
                                                {
                                                    xtype: 'datefield',
                                                    anchor: '100%',
                                                    fieldLabel: 'Label'
                                                },
                                                {
                                                    xtype: 'datefield',
                                                    anchor: '100%',
                                                    fieldLabel: 'Label'
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'panel',
                                            region: 'center',
                                            split: false,
                                            header: false,
                                            title: 'My Panel'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'panel',
                                    title: 'Kategorien'
                                },
                                {
                                    xtype: 'panel',
                                    title: 'Eigenschaften'
                                }
                            ]
                        },
                        {
                            xtype: 'panel',
                            region: 'center',
                            split: false,
                            layout: {
                                type: 'border'
                            },
                            title: 'Artikel',
                            items: [
                                {
                                    xtype: 'gridpanel',
                                    region: 'west',
                                    split: true,
                                    width: 150,
                                    header: false,
                                    title: 'My Grid Panel',
                                    columns: [
                                        {
                                            xtype: 'gridcolumn',
                                            dataIndex: 'string',
                                            text: 'String'
                                        },
                                        {
                                            xtype: 'numbercolumn',
                                            dataIndex: 'number',
                                            text: 'Number'
                                        },
                                        {
                                            xtype: 'datecolumn',
                                            dataIndex: 'date',
                                            text: 'Date'
                                        },
                                        {
                                            xtype: 'booleancolumn',
                                            dataIndex: 'bool',
                                            text: 'Boolean'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'tabpanel',
                                    region: 'center',
                                    activeTab: 0,
                                    items: [
                                        {
                                            xtype: 'panel',
                                            title: 'Artikel',
                                            dockedItems: [
                                                {
                                                    xtype: 'toolbar',
                                                    dock: 'top',
                                                    items: [
                                                        {
                                                            xtype: 'button',
                                                            text: 'MyButton'
                                                        },
                                                        {
                                                            xtype: 'button',
                                                            text: 'MyButton'
                                                        },
                                                        {
                                                            xtype: 'button',
                                                            text: 'MyButton'
                                                        },
                                                        {
                                                            xtype: 'button',
                                                            text: 'MyButton'
                                                        },
                                                        {
                                                            xtype: 'button',
                                                            text: 'MyButton'
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            xtype: 'panel',
                                            title: 'Gewichte'
                                        },
                                        {
                                            xtype: 'panel',
                                            title: 'Anpassungen'
                                        }
                                    ]
                                }
                            ]
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});