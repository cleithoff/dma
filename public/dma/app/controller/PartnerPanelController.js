/*
 * File: app/controller/PartnerPanelController.js
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

Ext.define('MyApp.controller.PartnerPanelController', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            autoCreate: true,
            ref: 'PartnerPanel',
            selector: '#PartnerPanel',
            xtype: 'partnerpanel'
        },
        {
            autoCreate: true,
            ref: 'PartnerAddressPanel',
            selector: '#PartnerAddressPanel',
            xtype: 'partneraddresspanel'
        },
        {
            autoCreate: true,
            ref: 'PartnerAbsencePanel',
            selector: '#PartnerAbsencePanel',
            xtype: 'partnerabsencepanel'
        },
        {
            autoCreate: true,
            ref: 'PartnerImportPanel',
            selector: '#PartnerImportPanel',
            xtype: 'partnerimportpanel'
        }
    ],

    onPartnerPartnerFilterButtonClick: function(button, e, eOpts) {
        grid = this.getPartnerPanel().getComponent('PartnerPartnerGridPanel');

        grid.store.clearFilter(true);

        filter = [];

        values = grid.getComponent('PartnerPartnerGridFilterFormPanel').getForm().getValues();

        console.log(values);

        if (values.partner_nr !== "") {
            filter.push({property:"partner_nr",value:values.partner_nr});
        }

        if (values.id !== "") {
            filter.push({property:"id",value:values.id});
        }

        if (values.title !== "") {
            filter.push({property:"title",value:values.title,operator:"LIKE"});
        }


        grid.store.filter(filter);
    },

    onPartnerPartnerClearFilterButtonClick: function(button, e, eOpts) {
        grid = this.getPartnerPanel().getComponent('PartnerPartnerGridPanel');

        grid.store.clearFilter();
    },

    onPartnerPartnerGridPanelSelect: function(rowmodel, record, index, eOpts) {
        panel = this.getPartnerAddressPanel().getComponent('PartnerAddressTabPanel');

        panel.getComponent('PartnerPartnerPanel').getComponent('PartnerPartnerToolbar').getComponent('PartnerPartnerEditButton').enable();
        panel.getComponent('PartnerPartnerPanel').getComponent('PartnerPartnerToolbar').getComponent('PartnerPartnerSaveButton').disable();
        panel.getComponent('PartnerPartnerPanel').getComponent('PartnerPartnerToolbar').getComponent('PartnerPartnerCancelButton').disable();

        panel.getComponent('PartnerAddressInvoicePanel').getComponent('PartnerAddressInvoiceToolbar').getComponent('PartnerAddressInvoiceEditButton').enable();
        panel.getComponent('PartnerAddressInvoicePanel').getComponent('PartnerAddressInvoiceToolbar').getComponent('PartnerAddressInvoiceSaveButton').disable();
        panel.getComponent('PartnerAddressInvoicePanel').getComponent('PartnerAddressInvoiceToolbar').getComponent('PartnerAddressInvoiceCancelButton').disable();

        panel.getComponent('PartnerAddressDeliveryPanel').getComponent('PartnerAddressDeliveryToolbar').getComponent('PartnerAddressDeliveryEditButton').enable();
        panel.getComponent('PartnerAddressDeliveryPanel').getComponent('PartnerAddressDeliveryToolbar').getComponent('PartnerAddressDeliverySaveButton').disable();
        panel.getComponent('PartnerAddressDeliveryPanel').getComponent('PartnerAddressDeliveryToolbar').getComponent('PartnerAddressDeliveryCancelButton').disable();


        form = panel.getComponent('PartnerPartnerPanel').getComponent('PartnerPartnerFormPanel');
        form.getForm().loadRecord(record);

        /*grid = this.getOrderItemPanel().getComponent('OrderItemGridPanel');
        grid.store.clearFilter(true);
        grid.store.filter([{property:'order_pool_id',value:record.data.order_pool_id}]);*/
    },

    init: function(application) {
        // add PartnerAddressPanel
        panel = this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').getComponent(this.getPartnerAddressPanel().ref);
        if (panel === undefined) {
            this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').add(this.getPartnerAddressPanel());
        }

        // add PartnerAbsencePanel
        panel = this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').getComponent(this.getPartnerAbsencePanel().ref);
        if (panel === undefined) {
            this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').add(this.getPartnerAbsencePanel());
        }

        // add PartnerImportPanel
        panel = this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').getComponent(this.getPartnerImportPanel().ref);
        if (panel === undefined) {
            this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').add(this.getPartnerImportPanel());
        }

        this.getPartnerPanel().getComponent('PartnerPartnerTabPanel').setActiveTab(this.getPartnerAddressPanel());

        this.control({
            "#PartnerPartnerFilterButton": {
                click: this.onPartnerPartnerFilterButtonClick
            },
            "#PartnerPartnerClearFilterButton": {
                click: this.onPartnerPartnerClearFilterButtonClick
            },
            "#PartnerPartnerGridPanel": {
                select: this.onPartnerPartnerGridPanelSelect
            }
        });
    }

});
