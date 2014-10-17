/*
 * File: app/controller/OrderItemDetailPanelController.js
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

Ext.define('MyApp.controller.OrderItemDetailPanelController', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            autoCreate: true,
            ref: 'OrderItemDetailPanel',
            selector: '#OrderItemDetailPanel',
            xtype: 'orderitemdetailpanel'
        },
        {
            autoCreate: true,
            ref: 'OrderItemPanel',
            selector: '#OrderItemPanel',
            xtype: 'orderitempanel'
        }
    ],

    onOrderItemDetailEditButtonClick: function(button, e, eOpts) {
        panel = this.getOrderItemDetailPanel();

        formPanel = panel.getComponent('OrderItemDetailFormPanel');
        toolbar = panel.getComponent('OrderItemDetailToolbar');
        formPanel.enable();

        toolbar.getComponent('OrderItemDetailEditButton').disable();
        //toolbar.getComponent('OrderItemDetailNewButton').disable();
        toolbar.getComponent('OrderItemDetailCancelButton').enable();
        toolbar.getComponent('OrderItemDetailSaveButton').enable();
        toolbar.getComponent('OrderItemDetailDeleteButton').disable();

    },

    onOrderItemDetailSaveButtonClick: function(button, e, eOpts) {
        var that = this;

        var store = Ext.getStore('OrderItemJsonStore');
        var panel = this.getOrderItemDetailPanel();

        var formPanel = panel.getComponent('OrderItemDetailFormPanel');
        var toolbar = panel.getComponent('OrderItemDetailToolbar');

        //grid = button.ownerCt.ownerCt.ownerCt.query('#" . $name . "GridPanel')[0];

        var record = formPanel.getForm().getRecord();

        /*
        record.store.on('write', function(store,options) {
        if (record.data.authkey !== null) {
        that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed style="width:100%;height:100%" src="/deploy/' + record.data.authkey + '.pdf" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">'); 
        }
        });
        */

        function updatePanel() {
            formPanel.disable();
            //toolbar.getComponent('OrderItemDetailNewButton').enable();
            toolbar.getComponent('OrderItemDetailCancelButton').disable();
            toolbar.getComponent('OrderItemDetailSaveButton').disable();

            panel = that.getOrderItemPanel();

            if (panel.getComponent('OrderItemGridPanel').getSelectionModel().getSelection().length > 0) {
                toolbar.getComponent('OrderItemDetailEditButton').enable();
                toolbar.getComponent('OrderItemDetailDeleteButton').enable();
            } else {
                toolbar.getComponent('OrderItemDetailEditButton').disable();
                toolbar.getComponent('OrderItemDetailDeleteButton').disable();
            }
        }

        if (record !== undefined && (record.data.id === undefined || record.data.id == 0)) {
            values = formPanel.getForm().getValues();
            record.set(values);
            store.insert(0, record);
            /*grid = button.ownerCt.ownerCt.ownerCt.getComponent('" . $name . 'GridPanel' . "');*/
            //if (grid !== undefined) {
            /*grid.getView().select(0);*/ /* BUG!!! */
            //}
            updatePanel();
        } else {
            var order_itemstate_id = record.data.order_itemstate_id;

            var order_itemstate_id_new = formPanel.down('#OrderItemDetailOrderitemState').getSubmitValue();

            if (order_itemstate_id != order_itemstate_id_new) {
                Ext.Msg.defaultTextHeight = 320;
                var msg = Ext.Msg.prompt('Kommentar', 'Kommentar (wird für Log und E-Mail verwendet):', function(btn, text){
                    if (btn == 'ok'){
                        formPanel.down('#OrderItemDetailComment').setValue(text);
                        formPanel.getForm().updateRecord();
                    } else {
                        formPanel.getForm().reset();
                    }
                    updatePanel();
                }, this, 120);

                msg.setSize(480, 'auto').center();
                var textareaEl = msg.body.child('textarea');
                textareaEl.setWidth(textareaEl.dom.parentNode.offsetWidth);

            } else {
                formPanel.getForm().updateRecord();
                updatePanel();
            }


        }

    },

    onOrderItemDetailCancelButtonClick: function(button, e, eOpts) {
        panel = this.getOrderItemDetailPanel();
        grid = this.getOrderItemPanel().getComponent('OrderItemGridPanel');

        formPanel = panel.getComponent('OrderItemDetailFormPanel');
        toolbar = panel.getComponent('OrderItemDetailToolbar');
        record = formPanel.getForm().getRecord();								
        if (record !== undefined) {
            formPanel.getForm().reset();
            formPanel.disable();

            //toolbar.getComponent('OrderItemDetailNewButton').enable();
            toolbar.getComponent('OrderItemDetailCancelButton').disable();
            toolbar.getComponent('OrderItemDetailSaveButton').disable();
            if (grid.getSelectionModel().getSelection().length > 0) {
                toolbar.getComponent('OrderItemDetailEditButton').enable();
                toolbar.getComponent('OrderItemDetailDeleteButton').enable();
            } else {
                toolbar.getComponent('OrderItemDetailEditButton').disable();
                toolbar.getComponent('OrderItemDetailDeleteButton').disable();
            }    
        }

        if(grid !== undefined && grid.getSelectionModel().getSelection().length > 0) {
            //grid.getView().select(0);
            record = grid.getSelectionModel().getSelection()[0];
            formPanel.getForm().loadRecord(record);
        }
    },

    onOrderItemDetailDeleteButtonClick: function(button, e, eOpts) {
        var store = Ext.getStore('OrderItemJsonStore');
        var panel = this.getOrderItemDetailPanel(); 
        var grid = this.getOrderItemPanel().getComponent('OrderItemGridPanel');

        var that = this;

        Ext.Msg.confirm('Attention!', 'Datensatz löschen?', function(btn) {
            if(btn == 'yes') {
                //grid = button.ownerCt.ownerCt.ownerCt.query('#" . $name . "GridPanel')[0];

                formPanel = panel.getComponent('OrderItemDetailFormPanel');
                record = formPanel.getForm().getRecord();
                if (store !== undefined && record !== undefined) {
                    store.remove(record);
                    formPanel.getForm().reset();
                    if(grid !== undefined && grid.getView().getNodes().length > 0) {
                        grid.getView().select(0);
                    }
                }
            }
        });

        if (panel.getComponent('OrderItemDetailGridPanel').getSelectionModel().getSelection().length > 0) {
            toolbar.getComponent('OrderItemDetailDeleteButton').enable();
        } else {
            toolbar.getComponent('OrderItemDetailDeleteButton').disable();
        }
    },

    onOrderItemDetailRefreshButtonClick: function(button, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        var view = null;

        menu = this.getOrderItemDetailPanel().getComponent('OrderItemDetailToolbar').getComponent('OrderItemViewmodeButton').menu;
        menu.items.each(function(menuitem){ if(menuitem.checked){view=menuitem;} });

        if (view.suffix === undefined || view.suffix === null) {
            view.suffix = '';
        }

        var myMask = new Ext.LoadMask(Ext.getBody(), {msg:"Bitte warten Sie. Die Ausgabe wird erzeugt!"});
        myMask.show();

        Ext.Ajax.request({
            url: '/order/item/refresh',
            timeout: 1000 * 60 * 5,
            success: function(response, operation, success) {
                // console.log(response, operation, success);
                myMask.destroy();
                records = JSON.parse(response.responseText);
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + records.data[0].authkey + view.suffix + '.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');

            },
            failure: function() {
                myMask.destroy();
                Ext.MessageBox.alert('Fehler', 'Bei der Erzeugung ist ein Fehler aufgetreten.');

            },
            params: { 
                id: record.data.id,    
                viewmode: view.value,
                refresh: 1
            }
        });
    },

    onOrderItemDetailPreviewButtonClick: function(button, e, eOpts) {
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        this.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed style="width:100%;height:100%" src="/deploy/' + record.data.authkey + '.pdf" alt="pdf" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
    },

    onOrderItemDetailSendButtonClick: function(button, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        Ext.Ajax.request({
            url: '/order/item/send',
            success: function(response) {
                try {
                    r = JSON.parse(response.responseText);
                    if (r['success'] !== undefined && r.success === true) {
                        alert('E-Mail wurde versendet.');
                    } else {
                        alert('Die E-Mail konnte nicht versendet werden.');	
                    }
                } catch(ex) {
                    alert('Die E-Mail konnte nicht versendet werden.');
                }        
            },
            failure: function() {
                alert('Die E-Mail konnte nicht versendet werden.');
            },
            params: { id: record.data.id}
        });
    },

    onOrderItemstatelogGridPanelSelect: function(rowmodel, record, index, eOpts) {
        this.getOrderItemDetailPanel().getComponent('OrderItemstatelogGridPanel').getComponent('OrderItemstatelogFormPanel').getForm().loadRecord(record);
    },

    onOrderItemViewmodePreviewFrontMenuItemClick: function(item, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        that.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey +'.pdf');
        that.getOrderItemPanel().down('#UploadPDFTextField').setText('Upload Preview Front per Drag\'n\'Drop');

        Ext.Ajax.request({
            url: '/order/item/refresh',
            success: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + record.data.authkey + '.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            failure: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },

            params: { id: record.data.id, viewmode: 1, refresh: 0}
        });
    },

    onOrderItemViewmodePreviewBackMenuItemClick: function(item, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        that.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey + '_preview_back.pdf');
        that.getOrderItemPanel().down('#UploadPDFTextField').setText('Upload Preview Back per Drag\'n\'Drop');

        Ext.Ajax.request({
            url: '/order/item/refresh',
            success: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + record.data.authkey + '_preview_back.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            failure: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },

            params: { id: record.data.id, viewmode: 2, refresh: 0}
        });
    },

    onOrderItemViewmodePrintFrontMenuItemClick: function(item, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        that.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey + '_print_front.pdf');
        that.getOrderItemPanel().down('#UploadPDFTextField').setText('Upload Print Front per Drag\'n\'Drop');

        Ext.Ajax.request({
            url: '/order/item/refresh',
            success: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + record.data.authkey + '_print_front.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            failure: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },

            params: { id: record.data.id, viewmode: 3, refresh: 0}
        });
    },

    onOrderItemViewmodePrintBackMenuItemClick: function(item, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        that.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey +'_print_back.pdf');
        that.getOrderItemPanel().down('#UploadPDFTextField').setText('Upload Print Back per Drag\'n\'Drop');

        Ext.Ajax.request({
            url: '/order/item/refresh',
            success: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + record.data.authkey + '_print_back.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            failure: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },

            params: { id: record.data.id, viewmode: 4, refresh: 0}
        });
    },

    onOrderItemViewmodeTestFrontMenuItemClick: function(item, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        that.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey +'_test_front.pdf');
        that.getOrderItemPanel().down('#UploadPDFTextField').setText('Upload Test Front per Drag\'n\'Drop');

        Ext.Ajax.request({
            url: '/order/item/refresh',
            success: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + record.data.authkey + '_test_front.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            failure: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            params: { id: record.data.id, viewmode: 5, refresh: 0}
        });
    },

    onOrderItemViewmodeTestBackMenuItemClick: function(item, e, eOpts) {
        var that = this;
        var record = this.getOrderItemPanel().getComponent('OrderItemGridPanel').getSelectionModel().getSelection()[0];

        if (record === undefined) return;

        that.getOrderItemDetailPanel().down('#OrderItemFilename').setValue(record.data.authkey +'_test_back.pdf');
        that.getOrderItemPanel().down('#UploadPDFTextField').setText('Upload Test Back per Drag\'n\'Drop');

        Ext.Ajax.request({
            url: '/order/item/refresh',
            success: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="/deploy/' + record.data.authkey + '_test_back.pdf?_dc=' + (new Date().getTime()) + '" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },
            failure: function() {
                that.getOrderItemDetailPanel().getComponent('PreviewContainer').update('<embed src="" alt="pdf" style="width:100%;height:100%" pluginspage="http://www.adobe.com/products/acrobat/readstep2.html">');
            },

            params: { id: record.data.id, viewmode: 6, refresh: 0}
        });
    },

    init: function(application) {
        this.control({
            "#OrderItemDetailEditButton": {
                click: this.onOrderItemDetailEditButtonClick
            },
            "#OrderItemDetailSaveButton": {
                click: this.onOrderItemDetailSaveButtonClick
            },
            "#OrderItemDetailCancelButton": {
                click: this.onOrderItemDetailCancelButtonClick
            },
            "#OrderItemDetailDeleteButton": {
                click: this.onOrderItemDetailDeleteButtonClick
            },
            "#OrderItemDetailRefreshButton": {
                click: this.onOrderItemDetailRefreshButtonClick
            },
            "#OrderItemDetailPreviewButton": {
                click: this.onOrderItemDetailPreviewButtonClick
            },
            "#OrderItemDetailSendButton": {
                click: this.onOrderItemDetailSendButtonClick
            },
            "#OrderItemstatelogGridPanel": {
                select: this.onOrderItemstatelogGridPanelSelect
            },
            "#OrderItemViewmodePreviewFrontMenuItem": {
                click: this.onOrderItemViewmodePreviewFrontMenuItemClick
            },
            "#OrderItemViewmodePreviewBackMenuItem": {
                click: this.onOrderItemViewmodePreviewBackMenuItemClick
            },
            "#OrderItemViewmodePrintFrontMenuItem": {
                click: this.onOrderItemViewmodePrintFrontMenuItemClick
            },
            "#OrderItemViewmodePrintBackMenuItem": {
                click: this.onOrderItemViewmodePrintBackMenuItemClick
            },
            "#OrderItemViewmodeTestFrontMenuItem": {
                click: this.onOrderItemViewmodeTestFrontMenuItemClick
            },
            "#OrderItemViewmodeTestBackMenuItem": {
                click: this.onOrderItemViewmodeTestBackMenuItemClick
            }
        });
    }

});
