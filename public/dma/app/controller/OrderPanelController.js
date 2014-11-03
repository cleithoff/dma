/*
 * File: app/controller/OrderPanelController.js
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

Ext.define('MyApp.controller.OrderPanelController', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            autoCreate: true,
            ref: 'OrderPanel',
            selector: '#OrderPanel',
            xtype: 'orderpanel'
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
            ref: 'OrderPackagePackageorderPanel',
            selector: '#OrderPackagePackageorderPanel',
            xtype: 'orderpackagepackageorderpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderCombineitemPanel',
            selector: '#OrderCombineitemPanel',
            xtype: 'ordercombineitempanel'
        },
        {
            autoCreate: true,
            ref: 'MailImapPanel',
            selector: '#MailImapPanel',
            xtype: 'mailimappanel'
        }
    ],

    onOrderOrderGridPanelSelect: function(rowmodel, record, index, eOpts) {
        var grid = this.getOrderItemPanel().getComponent('OrderItemGridPanel');
        grid.store.clearFilter(true);
        grid.store.filter([{property:'order_pool_id',value:record.data.order_pool_id}]);

        // Package Packageorder
        this.getOrderPackagePackageorderPanel().record = record;
        var gridOrderPackagePackageorder = this.getOrderPackagePackageorderPanel().getComponent('OrderPackagePackageorderGridPanel');
        gridOrderPackagePackageorder.store.clearFilter(true);
        gridOrderPackagePackageorder.store.filter([{property:'order_combine_id',value:record.data.order_combine_id}]);
        gridOrderPackagePackageorder.store.load({
            callback: function(records, operation, success) {
                if (records.length > 0) {
                    gridOrderPackagePackageorder.getSelectionModel().select(0);
                }
            }
        });

        this.getOrderCombineitemPanel().record = record;
        this.getOrderCombineitemPanel().linkrecord = record;

        var grid = this.getOrderCombineitemPanel().down('#BagGridPanel');
        grid.store.clearFilter(true);
        grid.store.filter([{property:'order_combine_id',value:record.data.order_combine_id}]);
        grid.store.load();


        if (!Ext.isEmpty(record.data.comment)) {
            this.getOrderPanel().down('#CommentFormPanel').getForm().loadRecord(record);
            this.getOrderPanel().down('#CommentFormPanel').setTitle(record.data.comment);
            this.getOrderPanel().down('#CommentFormPanel').collapse();
            this.getOrderPanel().down('#CommentFormPanel').show();
        } else {
            this.getOrderPanel().down('#CommentFormPanel').hide();
        }

        // console.log(record);


        // FIXME: setTimeout
        /*
        grid = this.getMailImapPanel().down('#MailImapGridPanel');
        var mailimappanel = this.getMailImapPanel();

        grid.getStore().clearFilter(true);
        grid.getStore().filter([{property:'partner_nr',value:record.data.partner_partner.partner_nr}]);
        grid.getStore().load({
        callback: function(records, operation, success) {
        if (!Ext.isEmpty(records) || records.length > 0) {
        grid.getSelectionModel().select(0);
        } else {
        mailimappanel.down('#MailImapFormPanel').getForm().reset();
        }
        }
        });
        */

        //grid.store.load(); // filter loads automatically - dont use .load because records lose store (record.store == null)
        /*
        Ext.getStore('OrderItemJsonStore').clearFilter(true);
        Ext.getStore('OrderItemJsonStore').filter([{property:'order_pool_id',value:record.data.order_pool_id}]);
        Ext.getStore('OrderItemJsonStore').load();*/
    },

    onOrderOrderFilterButtonClick: function(button, e, eOpts) {
        var grid = this.getOrderPanel().getComponent('OrderOrderGridPanel');

        grid.store.clearFilter(true);

        var filter = [];

        var values = grid.getComponent('OrderOrderGridFilterFormPanel').getForm().getValues();

        // console.log(values);

        if (values.partner_nr !== "") {
            filter.push({property:"partner_partner.partner_nr",value:values.partner_nr});
        }

        if (values.id !== "") {
            filter.push({property:"partner_partner_id",value:values.id});
        }

        if (values.title !== "") {
            filter.push({property:"partner_partner.title",value:values.title,operator:"LIKE"});
        }

        if (values.import_import_id !== "") {
            filter.push({property:"import_import_id",value:values.import_import_id,operator:"="});
        }

        if (values.import_stack_id !== "") {
            filter.push({property:"import_stack_id",value:values.import_stack_id,operator:"="});
        }

        if (values.product_category_id !== "") {
            filter.push({property:"product_category_id",value:values.product_category_id,operator:"="});
        }

        if (values.order_state_id !== "") {
            filter.push({property:"order_state_id",value:values.order_state_id,operator:"="});
        }

        grid.store.filter(filter);
    },

    onOrderOrderClearFilterButtonClick: function(button, e, eOpts) {
        var grid = this.getOrderPanel().getComponent('OrderOrderGridPanel');

        grid.getComponent('OrderOrderGridFilterFormPanel').getForm().reset();

        grid.store.clearFilter();
    },

    init: function(application) {
        this.control({
            "#OrderOrderGridPanel": {
                select: this.onOrderOrderGridPanelSelect
            },
            "#OrderOrderFilterButton": {
                click: this.onOrderOrderFilterButtonClick
            },
            "#OrderOrderClearFilterButton": {
                click: this.onOrderOrderClearFilterButtonClick
            }
        });
    }

});
