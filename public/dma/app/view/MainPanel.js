/*
 * File: app/view/MainPanel.js
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

Ext.define('MyApp.view.MainPanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.mainpanel',

    singleton: true,

    itemId: 'MainPanel',
    layout: {
        type: 'fit'
    },
    title: 'DMA',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'top',
                    itemId: 'MainToolbar',
                    items: [
                        {
                            xtype: 'button',
                            itemId: 'FileButton',
                            text: 'Datei',
                            menu: {
                                xtype: 'menu',
                                itemId: 'FileMenu',
                                items: [
                                    {
                                        xtype: 'menuitem',
                                        itemId: 'CloseMenuItem',
                                        text: 'Beenden'
                                    }
                                ]
                            }
                        },
                        {
                            xtype: 'button',
                            itemId: 'ModuleButton',
                            text: 'Module',
                            menu: {
                                xtype: 'menu',
                                itemId: 'ModuleMenu',
                                items: [
                                    {
                                        xtype: 'menuitem',
                                        itemId: 'PartnerMenuItem',
                                        text: 'Partner'
                                    },
                                    {
                                        xtype: 'menuitem',
                                        itemId: 'OrderMenuItem',
                                        text: 'Bestellungen'
                                    },
                                    {
                                        xtype: 'menuitem',
                                        itemId: 'ReportMenuItem',
                                        text: 'Reporte'
                                    },
                                    {
                                        xtype: 'menuitem',
                                        itemId: 'ImportMenuItem',
                                        text: 'Import'
                                    }
                                ]
                            }
                        },
                        {
                            xtype: 'button',
                            text: 'Produkte',
                            menu: {
                                xtype: 'menu',
                                width: 240,
                                items: [
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productpanel',
                                        itemId: 'ProductMenuItem',
                                        text: 'Produkte',
                                        listeners: {
                                            click: {
                                                fn: me.onProductMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productlayoutpanel',
                                        itemId: 'ProductLayoutMenuItem',
                                        text: 'Produktlayout Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductLayoutMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productcategorypanel',
                                        itemId: 'ProductCategoryMenuItem',
                                        text: 'Kategorie Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductCategoryMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productpackagepanel',
                                        itemId: 'ProductPackageMenuItem',
                                        text: 'Paket Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductPackageMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productdatatypepanel',
                                        itemId: 'ProductDatatypeMenuItem',
                                        text: 'Datentyp Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductDatatypeMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productpropertypanel',
                                        itemId: 'ProductPropertyMenuItem',
                                        text: 'Produkteigenschaften Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductPropertyMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productcustomizepanel',
                                        itemId: 'ProductCustomizeMenuItem',
                                        text: 'Produktanpassungen Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductCustomizeMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productpersonalizepanel',
                                        itemId: 'ProductPersonalizeMenuItem',
                                        text: 'Produktpersonalisierung Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductPersonalizeMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'productcurrencypanel',
                                        itemId: 'ProductCurrencyMenuItem',
                                        text: 'Währung Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onProductCurrencyMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'ordermetapanel',
                                        itemId: 'OrderMetaMenuItem',
                                        text: 'Metabestelldaten Definition',
                                        listeners: {
                                            click: {
                                                fn: me.onOrderMetaMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'importstackpanel',
                                        itemId: 'ImportStackMenuItem',
                                        text: 'Importstapel',
                                        listeners: {
                                            click: {
                                                fn: me.onImportStackMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    }
                                ]
                            }
                        },
                        {
                            xtype: 'button',
                            itemId: 'UserButton',
                            text: 'Benutzerverwaltung',
                            menu: {
                                xtype: 'menu',
                                items: [
                                    {
                                        xtype: 'menuitem',
                                        widget: 'useruserpanel',
                                        itemId: 'UserUserMenuItem',
                                        text: 'Benutzer',
                                        listeners: {
                                            click: {
                                                fn: me.onUserUserMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'userrolepanel',
                                        itemId: 'UserRoleMenuItem',
                                        text: 'Rollen',
                                        listeners: {
                                            click: {
                                                fn: me.onUserRoleMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    },
                                    {
                                        xtype: 'menuitem',
                                        widget: 'userresourcepanel',
                                        itemId: 'UserResourceMenuItem',
                                        text: 'Resourcen',
                                        listeners: {
                                            click: {
                                                fn: me.onUserResourceMenuItemClick,
                                                scope: me
                                            }
                                        }
                                    }
                                ]
                            }
                        }
                    ]
                }
            ],
            items: [
                {
                    xtype: 'tabpanel',
                    itemId: 'AppTabPanel'
                }
            ],
            tools: [
                {
                    xtype: 'tool',
                    itemId: 'AppHelpTool',
                    type: 'help'
                }
            ]
        });

        me.callParent(arguments);
    },

    onProductMenuItemClick: function(item, e, eOpts) {
        var panel = MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductLayoutMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductCategoryMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductPackageMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductDatatypeMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductPropertyMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductCustomizeMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductPersonalizeMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onProductCurrencyMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onOrderMetaMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onImportStackMenuItemClick: function(item, e, eOpts) {
        MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onUserUserMenuItemClick: function(item, e, eOpts) {
        var panel = MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onUserRoleMenuItemClick: function(item, e, eOpts) {
        var panel = MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    },

    onUserResourceMenuItemClick: function(item, e, eOpts) {
        var panel = MyApp.app.getCrudControllerController().onMenuItemClick(item, e, eOpts);
    }

});