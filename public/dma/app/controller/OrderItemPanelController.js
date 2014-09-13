/*
 * File: app/controller/OrderItemPanelController.js
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

Ext.define('MyApp.controller.OrderItemPanelController', {
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
            ref: 'OrderItemDetailPanel',
            selector: '#OrderItemDetailPanel',
            xtype: 'orderitemdetailpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemProductPersonalizePanel',
            selector: '#OrderItemProductPersonalizePanel',
            xtype: 'orderitemproductpersonalizepanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemPanel',
            selector: '#OrderItemPanel',
            xtype: 'orderitempanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemOrderMetaPanel',
            selector: '#OrderItemOrderMetaPanel',
            xtype: 'orderitemordermetapanel'
        }
    ],

    onOrderItemGridPanelSelect: function(rowmodel, record, index, eOpts) {
        Ext.getStore('PackagePackageJsonStore').clearFilter(true);
        Ext.getStore('PackagePackageJsonStore').filter([{property:'order_item_id',value:record.data.id}]);
        Ext.getStore('PackagePackageJsonStore').load();


        this.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeGridPanel').store.clearFilter(true);
        this.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeGridPanel').store.filter([{property:'order_item_id',value:record.data.id}]);
        this.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeGridPanel').store.load();

        this.getOrderItemOrderMetaPanel().getComponent('OrderItemOrderMetaGridPanel').store.clearFilter(true);
        this.getOrderItemOrderMetaPanel().getComponent('OrderItemOrderMetaGridPanel').store.filter([{property:'order_item_id',value:record.data.id}]);
        this.getOrderItemOrderMetaPanel().getComponent('OrderItemOrderMetaGridPanel').store.load();

        //Ext.getStore('OrderItemHasProductPersonalizeJsonStore').load();

        var that = this;

        //Ext.getStore('OrderItemProductItemJsonStore').un('load');
        if (!Ext.getStore('OrderItemProductItemJsonStore').hasListener('load')) {
            Ext.getStore('OrderItemProductItemJsonStore').on('load', function(store, records, options){
                combobox = that.getOrderItemProductPersonalizePanel().getComponent('OrderItemProductPersonalizeFormPanel').getComponent('OrderItemProductPersonalizeComboBox');
                combobox.store.clearFilter();
                combobox.store.filter([{property:'product_layout_id',value:records[0].data.product_layout_id}]);
                combobox.store.load();
            });
        }
        Ext.getStore('OrderItemProductItemJsonStore').clearFilter(true);
        Ext.getStore('OrderItemProductItemJsonStore').filter([{property:'id',value:record.data.product_item_id}]);
        Ext.getStore('OrderItemProductItemJsonStore').load();

        //# OrderItemDetailPanel
        panel = this.getOrderItemDetailPanel();
        formPanel = panel.getComponent('OrderItemDetailFormPanel');
        toolbar = panel.getComponent('OrderItemDetailToolbar');
        formPanel.getForm().loadRecord(record);
        toolbar.getComponent('OrderItemDetailEditButton').enable();
        toolbar.getComponent('OrderItemDetailCancelButton').disable();
        toolbar.getComponent('OrderItemDetailSaveButton').disable();
        toolbar.getComponent('OrderItemDetailDeleteButton').enable();

        //# OrderItemDetailPanel / OrderItemstateGridPanel
        grid = panel.getComponent('OrderItemstatelogGridPanel');
        grid.store.clearFilter(true);
        grid.store.filter([{property:'order_item_id',value:record.data.id}]);
        grid.store.load();
        formPanel = grid.getComponent('OrderItemstatelogFormPanel').getForm().reset();

        //# OrderItemProductPersonalizePanel
        panel = this.getOrderItemProductPersonalizePanel();
        //formPanel = panel.getComponent('OrderItemProductPersonalizeFormPanel');
        toolbar = panel.getComponent('OrderItemProductPersonalizeToolbar');
        //formPanel.getForm().loadRecord(record);
        toolbar.getComponent('OrderItemProductPersonalizeNewButton').enable();

        //# load pdf
        var view = null;

        menu = this.getOrderItemDetailPanel().getComponent('OrderItemDetailToolbar').getComponent('OrderItemViewmodeButton').menu;
        menu.items.each(function(menuitem){ if(menuitem.checked){view=menuitem;} });

        if (view.suffix === undefined || view.suffix === null) {
            view.suffix = '';
        }

        this.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey + view.suffix + '.pdf');

        this.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed style="width:100%;height:100%" src="/deploy/' + record.data.authkey + view.suffix + '.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
    },

    onOrderItemGridPanelRender: function(component, eOpts) {
        component.store.on('load', function(store, records, options){
            if (records.length > 0) {
                component.getSelectionModel().select(0); 
            }
        });
    },

    init: function(application) {
        this.control({
            "#OrderItemGridPanel": {
                select: this.onOrderItemGridPanelSelect,
                render: this.onOrderItemGridPanelRender
            }
        });
    }

});
