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
        }
    ],

    onOrderOrderGridPanelSelect: function(rowmodel, record, index, eOpts) {
        var grid = this.getOrderItemPanel().getComponent('OrderItemGridPanel');
        grid.store.clearFilter(true);
        grid.store.filter([{property:'order_pool_id',value:record.data.order_pool_id}]);

        if (!Ext.isEmpty(record.data.comment)) {
            Ext.MessageBox.alert('Hinweis', record.data.comment);
        }

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

        console.log(values);

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
