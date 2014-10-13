/*
 * File: app/controller/ReportReportPanelController.js
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

Ext.define('MyApp.controller.ReportReportPanelController', {
    extend: 'Ext.app.Controller',

    refs: [
        {
            autoCreate: true,
            ref: 'ReportPanel',
            selector: '#ReportPanel',
            xtype: 'reportpanel'
        },
        {
            autoCreate: true,
            ref: 'ReportReportPanel',
            selector: '#ReportReportPanel',
            xtype: 'reportreportpanel'
        }
    ],

    onReportReportEditButtonClick: function(button, e, eOpts) {
        var panel = this.getReportReportPanel();

        var formPanel = panel.getComponent('ReportReportFormPanel');
        var toolbar = panel.getComponent('ReportReportToolbar');
        formPanel.enable();

        toolbar.getComponent('ReportReportEditButton').disable();
        toolbar.getComponent('ReportReportNewButton').disable();
        toolbar.getComponent('ReportReportCancelButton').enable();
        toolbar.getComponent('ReportReportSaveButton').enable();
        toolbar.getComponent('ReportReportDeleteButton').disable();

        toolbar.getComponent('ReportReportPreviewButton').disable();
        toolbar.getComponent('ReportReportExportPdfButton').disable();
        toolbar.getComponent('ReportReportExportCsvButton').disable();
        toolbar.getComponent('ReportReportExportXmlButton').disable();
        toolbar.getComponent('ReportReportExportXsdButton').disable();
        toolbar.getComponent('ReportReportExecuteButton').disable();

    },

    onReportReportNewButtonClick: function(button, e, eOpts) {
        store = Ext.getStore('ReportReportJsonStore');
        panel = this.getReportReportPanel();

        formPanel = panel.getComponent('ReportReportFormPanel');
        toolbar = panel.getComponent('ReportReportToolbar');
        if (store !== undefined) {
            formPanel.enable();
            record = new store.model();
            formPanel.loadRecord(record);
            toolbar.getComponent('ReportReportEditButton').disable();
            toolbar.getComponent('ReportReportNewButton').disable();
            toolbar.getComponent('ReportReportCancelButton').enable();
            toolbar.getComponent('ReportReportSaveButton').enable();
            toolbar.getComponent('ReportReportDeleteButton').disable();

            toolbar.getComponent('ReportReportPreviewButton').disable();
            toolbar.getComponent('ReportReportExportPdfButton').disable();
            toolbar.getComponent('ReportReportExportCsvButton').disable();
            toolbar.getComponent('ReportReportExportXmlButton').disable();
            toolbar.getComponent('ReportReportExportXsdButton').disable();
            toolbar.getComponent('ReportReportExecuteButton').disable();
        }

    },

    onReportReportSaveButtonClick: function(button, e, eOpts) {
        store = Ext.getStore('ReportReportJsonStore');
        panel = this.getReportReportPanel();

        formPanel = panel.getComponent('ReportReportFormPanel');
        toolbar = panel.getComponent('ReportReportToolbar');

        record = formPanel.getForm().getRecord();
        if (record !== undefined && (record.data.id === undefined || record.data.id == 0)) {
            values = formPanel.getForm().getValues();
            record.set(values);
            store.insert(0, record);
        } else {
            formPanel.getForm().updateRecord();
        }
        formPanel.disable();
        toolbar.getComponent('ReportReportEditButton').enable();
        toolbar.getComponent('ReportReportNewButton').enable();
        toolbar.getComponent('ReportReportCancelButton').disable();
        toolbar.getComponent('ReportReportSaveButton').disable();
        toolbar.getComponent('ReportReportDeleteButton').enable();

        toolbar.getComponent('ReportReportPreviewButton').enable();
        toolbar.getComponent('ReportReportExportPdfButton').enable();
        toolbar.getComponent('ReportReportExportCsvButton').enable();
        toolbar.getComponent('ReportReportExportXmlButton').enable();
        toolbar.getComponent('ReportReportExportXsdButton').enable();
        toolbar.getComponent('ReportReportExecuteButton').disable();
    },

    onReportReportCancelButtonClick: function(button, e, eOpts) {
        panel = this.getReportReportPanel();
        grid = this.getReportPanel().getComponent('ReportReportGridPanel');

        formPanel = panel.getComponent('ReportReportFormPanel');
        toolbar = panel.getComponent('ReportReportToolbar');
        record = formPanel.getForm().getRecord();								
        if (record !== undefined) {
            formPanel.getForm().reset();
            formPanel.disable();
            toolbar.getComponent('ReportReportEditButton').enable();
            toolbar.getComponent('ReportReportNewButton').enable();
            toolbar.getComponent('ReportReportCancelButton').disable();
            toolbar.getComponent('ReportReportSaveButton').disable();

            toolbar.getComponent('ReportReportPreviewButton').enable();
            toolbar.getComponent('ReportReportExportPdfButton').enable();
            toolbar.getComponent('ReportReportExportCsvButton').enable();
            toolbar.getComponent('ReportReportExportXmlButton').enable();
            toolbar.getComponent('ReportReportExportXsdButton').enable();
            toolbar.getComponent('ReportReportExecuteButton').disable();    
        }

        if(grid !== undefined && grid.getView().getNodes().length > 0) {
            grid.getView().select(0);
            record = grid.getSelectionModel().getSelection()[0];
            formPanel.getForm().loadRecord(record);
        }
    },

    onReportReportDeleteButtonClick: function(button, e, eOpts) {
        var store = Ext.getStore('ReportReportJsonStore');
        var panel = this.getReportReportPanel(); 
        var grid = this.getReportPanel().getComponent('ReportReportGridPanel');

        var that = this;

        Ext.Msg.confirm('Attention!', 'Datensatz löschen?', function(btn) {
            if(btn == 'yes') {

                formPanel = panel.getComponent('ReportReportFormPanel');
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
    },

    onReportReportPreviewButtonClick: function(button, e, eOpts) {
        var that = this;

        var panel = this.getReportReportPanel();
        toolbar = panel.getComponent('ReportReportToolbar');

        filterFormPanel = this.getReportReportPanel().getComponent('ReportFilterFormPanel');

        //params = filterFormPanel.getValues();

        var params = {};

        params._sql = this.getReportReportPanel().getComponent('ReportReportFormPanel').getForm().findField('sql').getValue();

        Ext.Ajax.request({
            url: '/report/report/meta',
            params: params,
            success: function(response, opts) {
                var obj = Ext.decode(response.responseText);
                that.getReportReportPanel().remove(that.getReportReportPanel().getComponent('ReportPreviewGridPanel'));
                columns = [];
                fields = [];
                for (var idx in obj.data) {
                    field = obj.data[idx];
                    switch(field.native_type) {
                        case 'LONG':
                        columns.push({
                            dataIndex: field.name,
                            align: 'right',
                            text: field.name,
                            xtype: 'numbercolumn',
                            format: '0'
                        });
                        fields.push({
                            name: field.name,
                            type: 'int',
                        });
                        break;
                        case 'VAR_STRING':
                        columns.push({
                            dataIndex: field.name,
                            align: 'left',
                            text: field.name,
                            xtype: 'gridcolumn'
                        });
                        fields.push({
                            name: field.name,
                            type: 'string',
                        });
                        break;
                        case 'BLOB':
                        columns.push({
                            dataIndex: field.name,
                            align: 'left',
                            text: field.name,
                            xtype: 'gridcolumn'
                        });
                        fields.push({
                            name: field.name,
                            type: 'string',
                        });                    
                        break;
                        default:
                        columns.push({
                            dataIndex: field.name,
                            align: 'left',
                            text: field.name,
                            xtype: 'gridcolumn'
                        });
                        fields.push({
                            name: field.name,
                            type: 'string',
                        });                    
                        break;
                    }
                }
                Ext.define('Meta', {
                    extend: 'Ext.data.Model',
                    fields: fields
                });

                var metaStore = Ext.create('Ext.data.Store', {
                    model: 'Meta',
                    proxy: {
                        type: 'rest',
                        url: '/report/report/index',
                        extraParams: params,
                        reader: {
                            type: 'json',
                            root: 'data'
                        }
                    },
                    autoLoad: true,
                    pageSize: 1000000
                });
                var panel = Ext.create('Ext.grid.Panel', {
                    split: true,
                    region: 'center',
                    itemId: 'ReportPreviewGridPanel',
                    //title: 'Number Column Demo',
                    store: metaStore,
                    columns: columns,
                    dockedItems: [
                    {
                        xtype: 'pagingtoolbar',
                        dock: 'bottom',
                        displayInfo: true,
                        store: metaStore
                    }
                    ]
                    /*[
                    { text: 'Symbol',         dataIndex: 'symbol', flex: 1 },
                    { text: 'Current Price',  dataIndex: 'price',  renderer: Ext.util.Format.usMoney },
                    { text: 'Change',         dataIndex: 'change', xtype: 'numbercolumn', format:'0.00' },
                    { text: 'Volume',         dataIndex: 'volume', xtype: 'numbercolumn', format:'0,000' }
                    ]*/
                });


                that.getReportReportPanel().add(panel);
                toolbar.getComponent('ReportReportExecuteButton').enable();
            },
            failure: function(response, opts) {
                console.log('server-side failure with status code ' + response.status);
            }
        });
    },

    onReportReportExecuteButtonClick: function(button, e, eOpts) {
        var that = this;

        panel = that.getReportReportPanel();
        toolbar = panel.getComponent('ReportReportToolbar');

        Ext.MessageBox.confirm('Achtung!', 'Soll die Query wirklich ausgeführt werden?', function (btn) {
            if (btn === 'yes') {
                filterFormPanel = that.getReportReportPanel().getComponent('ReportFilterFormPanel');

                params = filterFormPanel.getValues();

                params._sql = that.getReportReportPanel().getComponent('ReportReportFormPanel').getForm().findField('execsql').getValue();

                Ext.Ajax.request({
                    url: '/report/report/execute',
                    params: params,
                    success: function(response, opts) {
                        that.onReportReportPreviewButtonClick();
                        Ext.MessageBox.alert('', 'Die Query wurde ausgeführt.');
                        toolbar.getComponent('ReportReportExecuteButton').disable();
                    },
                    failure: function(response, opts) {
                        console.log('server-side failure with status code ' + response.status);
                    }
                });
            }
        });
    },

    onReportReportExportXmlButtonClick: function(button, e, eOpts) {
        var _dc = new Date().getTime();

        filterFormPanel = this.getReportReportPanel().getComponent('ReportFilterFormPanel');

        params = filterFormPanel.getValues();

        strParams = '';

        for (var idx in params) {
            strParams = strParams + '&' + idx + '=' + params[idx];
        }

        record = this.getReportPanel().getComponent('ReportReportGridPanel').getSelectionModel().getSelection()[0];

        document.location = "/report/report/exportxml?_dc=" + _dc + "&report_report_id=" + record.data.id + strParams;
    },

    onReportReportExportXsdButtonClick: function(button, e, eOpts) {
        var _dc = new Date().getTime();

        filterFormPanel = this.getReportReportPanel().getComponent('ReportFilterFormPanel');

        params = filterFormPanel.getValues();

        strParams = '';

        for (var idx in params) {
            strParams = strParams + '&' + idx + '=' + params[idx];
        }

        record = this.getReportPanel().getComponent('ReportReportGridPanel').getSelectionModel().getSelection()[0];

        document.location = "/report/report/exportxsd?_dc=" + _dc + "&report_report_id=" + record.data.id + strParams;
    },

    onReportReportExportPdfButtonClick: function(button, e, eOpts) {
        var _dc = new Date().getTime();

        filterFormPanel = this.getReportReportPanel().getComponent('ReportFilterFormPanel');

        params = filterFormPanel.getValues();

        strParams = '';

        for (var idx in params) {
            strParams = strParams + '&' + idx + '=' + params[idx];
        }

        record = this.getReportPanel().getComponent('ReportReportGridPanel').getSelectionModel().getSelection()[0];

        document.location = "/report/report/exportpdf?_dc=" + _dc + "&report_report_id=" + record.data.id + strParams;
    },

    onReportReportExportCsvButtonClick: function(button, e, eOpts) {
        var _dc = new Date().getTime();

        filterFormPanel = this.getReportReportPanel().getComponent('ReportFilterFormPanel');

        params = filterFormPanel.getValues();

        strParams = '';

        for (var idx in params) {
            strParams = strParams + '&' + idx + '=' + params[idx];
        }

        record = this.getReportPanel().getComponent('ReportReportGridPanel').getSelectionModel().getSelection()[0];

        document.location = "/report/report/exportcsv?_dc=" + _dc + "&report_report_id=" + record.data.id + strParams;
    },

    init: function(application) {
        this.control({
            "#ReportReportEditButton": {
                click: this.onReportReportEditButtonClick
            },
            "#ReportReportNewButton": {
                click: this.onReportReportNewButtonClick
            },
            "#ReportReportSaveButton": {
                click: this.onReportReportSaveButtonClick
            },
            "#ReportReportCancelButton": {
                click: this.onReportReportCancelButtonClick
            },
            "#ReportReportDeleteButton": {
                click: this.onReportReportDeleteButtonClick
            },
            "#ReportReportPreviewButton": {
                click: this.onReportReportPreviewButtonClick
            },
            "#ReportReportExecuteButton": {
                click: this.onReportReportExecuteButtonClick
            },
            "#ReportReportExportXmlButton": {
                click: this.onReportReportExportXmlButtonClick
            },
            "#ReportReportExportXsdButton": {
                click: this.onReportReportExportXsdButtonClick
            },
            "#ReportReportExportPdfButton": {
                click: this.onReportReportExportPdfButtonClick
            },
            "#ReportReportExportCsvButton": {
                click: this.onReportReportExportCsvButtonClick
            }
        });
    }

});
