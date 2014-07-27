/*
 * File: app/controller/MainPanelController.js
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

Ext.define('MyApp.controller.MainPanelController', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            autoCreate: true,
            ref: 'MainPanel',
            selector: '#MainPanel',
            xtype: 'mainpanel'
        },
        {
            autoCreate: true,
            ref: 'PartnerPanel',
            selector: '#PartnerPanel',
            xtype: 'partnerpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderPanel',
            selector: '#OrderPanel',
            xtype: 'orderpanel'
        },
        {
            autoCreate: true,
            ref: 'ReportPanel',
            selector: '#ReportPanel',
            xtype: 'reportpanel'
        },
        {
            autoCreate: true,
            ref: 'ProductPanel',
            selector: '#ProductPanel',
            xtype: 'productpanel'
        },
        {
            autoCreate: true,
            ref: 'UserPanel',
            selector: '#UserPanel',
            xtype: 'userpanel'
        },
        {
            autoCreate: true,
            ref: 'ImportPanel',
            selector: '#ImportPanel',
            xtype: 'importpanel'
        },
        {
            autoCreate: true,
            ref: 'ImportImportPanel',
            selector: '#ImportImportPanel',
            xtype: 'importimportpanel'
        },
        {
            autoCreate: true,
            ref: 'ImportActionPanel',
            selector: '#ImportActionPanel',
            xtype: 'importactionpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderImportPanel',
            selector: '#OrderImportPanel',
            xtype: 'orderimportpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemPanel',
            selector: '#OrderItemPanel',
            xtype: 'orderitempanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemDetailPanel',
            selector: '#OrderItemDetailPanel',
            xtype: 'orderitemdetailpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemPackagePackagePanel',
            selector: '#OrderItemPackagePackagePanel',
            xtype: 'orderitempackagepackagepanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemProductPersonalizePanel',
            selector: '#OrderItemProductPersonalizePanel',
            xtype: 'orderitemproductpersonalizepanel'
        },
        {
            autoCreate: true,
            ref: 'ReportReportPanel',
            selector: '#ReportReportPanel',
            xtype: 'reportreportpanel'
        },
        {
            autoCreate: true,
            ref: 'ImportExecutePanel',
            selector: '#ImportExecutePanel',
            xtype: 'importexecutepanel'
        },
        {
            autoCreate: true,
            ref: 'AppHelpPanel',
            selector: '#AppHelpPanel',
            xtype: 'apphelppanel'
        }
    ],

    onCloseMenuItemClick: function(item, e, eOpts) {
        window.location.reload();
    },

    onPartnerMenuItemClick: function(item, e, eOpts) {
        panel = this.getMainPanel().getComponent('AppTabPanel').getComponent(this.getPartnerPanel().ref);

        if (panel === undefined) {
            this.getMainPanel().getComponent('AppTabPanel').add(this.getPartnerPanel());
        }

        this.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getPartnerPanel());
    },

    onOrderMenuItemClick: function(item, e, eOpts) {
        if (!MyApp.app.getRuleControllerController().allow('OrderPanel', MyApp.app.getRuleControllerController().rights.READ)) {
            return;
        }

        panel = this.getMainPanel().getComponent('AppTabPanel').getComponent(this.getOrderPanel().ref);

        if (panel === undefined) {
            this.getMainPanel().getComponent('AppTabPanel').add(this.getOrderPanel());
        }

        this.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getOrderPanel());


        if (MyApp.app.getRuleControllerController().allow('OrderItemPanel', MyApp.app.getRuleControllerController().rights.READ)) {
            // Order Item
            panel = this.getOrderPanel().getComponent('OrderOrderTabPanel').getComponent(this.getOrderItemPanel().ref);
            if (panel === undefined) {
                this.getOrderPanel().getComponent('OrderOrderTabPanel').add(this.getOrderItemPanel());
            }
        }

        if (MyApp.app.getRuleControllerController().allow('OrderItemDetailPanel', MyApp.app.getRuleControllerController().rights.READ)) {
            // Order Item Detail
            panel = this.getOrderItemPanel().getComponent('OrderItemTabPanel').getComponent(this.getOrderItemDetailPanel().ref);
            if (panel === undefined) {
                this.getOrderItemPanel().getComponent('OrderItemTabPanel').add(this.getOrderItemDetailPanel());
            }
        }

        if (MyApp.app.getRuleControllerController().allow('OrderItemPackagePackagePanel', MyApp.app.getRuleControllerController().rights.READ)) {
            // Order Item Package Package
            panel = this.getOrderItemPanel().getComponent('OrderItemTabPanel').getComponent(this.getOrderItemPackagePackagePanel().ref);
            if (panel === undefined) {
                this.getOrderItemPanel().getComponent('OrderItemTabPanel').add(this.getOrderItemPackagePackagePanel());
                var orderItemPackagePackageGridPanel = this.getOrderItemPackagePackagePanel().getComponent('OrderItemPackagePackageGridPanel');
                var orderItemPackagePackageFormPanel = this.getOrderItemPackagePackagePanel().getComponent('OrderItemPackagePackageFormPanel');
                var orderItemPackagePackageToolbar = this.getOrderItemPackagePackagePanel().getComponent('OrderItemPackagePackageToolbar');

                orderItemPackagePackageGridPanel.store.on('load', function(store, records, options){


                    if (orderItemPackagePackageGridPanel.getView().getNodes().length > 0) {
                        orderItemPackagePackageGridPanel.getSelectionModel().select(0); 
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageEditButton').enable();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageSaveButton').disable();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageCancelButton').disable();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageDeleteButton').enable();


                    } else {
                        orderItemPackagePackageFormPanel.getForm().reset();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageEditButton').disable();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageSaveButton').disable();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageCancelButton').disable();
                        orderItemPackagePackageToolbar.getComponent('OrderItemPackagePackageDeleteButton').disable();
                    }
                });

            }
        }

        if (MyApp.app.getRuleControllerController().allow('OrderItemProductPersonalizePanel', MyApp.app.getRuleControllerController().rights.READ)) {
            // Order Item Product Personalize
            panel = this.getOrderItemPanel().getComponent('OrderItemTabPanel').getComponent(this.getOrderItemProductPersonalizePanel().ref);
            if (panel === undefined) {
                this.getOrderItemPanel().getComponent('OrderItemTabPanel').add(this.getOrderItemProductPersonalizePanel());
                var orderItemProductPersonalizeGridPanel = this.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeGridPanel');
                var orderItemProductPersonalizeFormPanel = this.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeFormPanel');
                var orderItemProductPersonalizeToolbar = this.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeToolbar');

                orderItemProductPersonalizeGridPanel.store.on('load', function(store, records, options){
                    //orderItemProductPersonalizeGridPanel.getSelectionModel().select(0);
                    if (orderItemProductPersonalizeGridPanel.getView().getNodes().length > 0) {
                        orderItemProductPersonalizeGridPanel.getSelectionModel().select(0); 
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeEditButton').enable();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeSaveButton').disable();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeCancelButton').disable();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeDeleteButton').enable();

                    } else {
                        orderItemProductPersonalizeFormPanel.getForm().reset();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeEditButton').disable();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeSaveButton').disable();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeCancelButton').disable();
                        orderItemProductPersonalizeToolbar.getComponent('OrderItemProductPersonalizeDeleteButton').disable();
                    }        
                });    

            }
        }

        if (MyApp.app.getRuleControllerController().allow('OrderItemDetailPanel', MyApp.app.getRuleControllerController().rights.READ)) {
            this.getOrderItemPanel().getComponent('OrderItemTabPanel').setActiveTab(this.getOrderItemDetailPanel());
        }

        if (MyApp.app.getRuleControllerController().allow('OrderImportPanel', MyApp.app.getRuleControllerController().rights.READ)) {
            // Order Import
            panel = this.getOrderPanel().getComponent('OrderOrderTabPanel').getComponent(this.getOrderImportPanel().ref);
            if (panel === undefined) {
                this.getOrderPanel().getComponent('OrderOrderTabPanel').add(this.getOrderImportPanel());
            }
        }

        if (MyApp.app.getRuleControllerController().allow('OrderItemPanel', MyApp.app.getRuleControllerController().rights.READ)) {
            this.getOrderPanel().getComponent('OrderOrderTabPanel').setActiveTab(this.getOrderItemPanel());
        }

        // preselect order
        var orderOrderGridPanel = this.getOrderPanel().getComponent('OrderOrderGridPanel');
        orderOrderGridPanel.store.on('load', function(store, records, options){
            orderOrderGridPanel.getSelectionModel().select(0);
        });
        Ext.getStore('OrderOrderJsonStore').load();

    },

    onReportMenuItemClick: function(item, e, eOpts) {
        panel = this.getMainPanel().getComponent('AppTabPanel').getComponent(this.getReportPanel().ref);

        if (panel === undefined) {
            this.getMainPanel().getComponent('AppTabPanel').add(this.getReportPanel());
        }

        // Report Report
        panel = this.getReportPanel().getComponent('ReportReportTabPanel').getComponent(this.getReportReportPanel().ref);
        if (panel === undefined) {
            this.getReportPanel().getComponent('ReportReportTabPanel').add(this.getReportReportPanel());
        }

        this.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getReportPanel());
    },

    onProductMenuItemClick: function(item, e, eOpts) {
        panel = this.getMainPanel().getComponent('AppTabPanel').getComponent(this.getProductPanel().ref);

        if (panel === undefined) {
            this.getMainPanel().getComponent('AppTabPanel').add(this.getProductPanel());
        }

        this.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getProductPanel());
    },

    onUserMenuItemClick: function(item, e, eOpts) {
        panel = this.getMainPanel().getComponent('AppTabPanel').getComponent(this.getUserPanel().ref);

        if (panel === undefined) {
            this.getMainPanel().getComponent('AppTabPanel').add(this.getUserPanel());
        }

        this.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getUserPanel());
    },

    onImportMenuItemClick: function(item, e, eOpts) {
        panel = this.getMainPanel().getComponent('AppTabPanel').getComponent(this.getImportPanel().ref);

        if (panel === undefined) {
            this.getMainPanel().getComponent('AppTabPanel').add(this.getImportPanel());
        }

        this.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getImportPanel());


        panel = this.getImportPanel().getComponent('ImportImportTabPanel').getComponent(this.getImportImportPanel().ref);
        if (panel === undefined) {
            panel = this.getImportImportPanel();
            this.getImportPanel().getComponent('ImportImportTabPanel').add(panel);
        }

        panel = this.getImportPanel().getComponent('ImportImportTabPanel').getComponent(this.getImportExecutePanel().ref);
        if (panel === undefined) {
            panel = this.getImportExecutePanel();
            this.getImportPanel().getComponent('ImportImportTabPanel').add(panel);
        }

        this.getImportPanel().getComponent('ImportImportTabPanel').setActiveTab(this.getImportImportPanel());


        panel = this.getImportImportPanel().getComponent('ImportActionPanel');

        if (panel === undefined) {

            this.getImportImportPanel().add(this.getImportActionPanel());
            this.getImportActionPanel().setRegion('center');
        }
    },

    onAppHelpToolClick: function(tool, e, eOpts) {
        var me = this;

        var panel = me.getMainPanel().getComponent('AppTabPanel').getComponent(this.getAppHelpPanel().ref);

        if (panel === undefined) {
            me.getMainPanel().getComponent('AppTabPanel').add(this.getAppHelpPanel());
        }

        me.getMainPanel().getComponent('AppTabPanel').setActiveTab(this.getAppHelpPanel());
    },

    init: function(application) {
        this.control({
            "#CloseMenuItem": {
                click: this.onCloseMenuItemClick
            },
            "#PartnerMenuItem": {
                click: this.onPartnerMenuItemClick
            },
            "#OrderMenuItem": {
                click: this.onOrderMenuItemClick
            },
            "#ReportMenuItem": {
                click: this.onReportMenuItemClick
            },
            "#ProductMenuItem": {
                click: this.onProductMenuItemClick
            },
            "#UserMenuItem": {
                click: this.onUserMenuItemClick
            },
            "#ImportMenuItem": {
                click: this.onImportMenuItemClick
            },
            "#AppHelpTool": {
                click: this.onAppHelpToolClick
            }
        });
    }

});
