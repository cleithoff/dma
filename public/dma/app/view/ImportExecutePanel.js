/*
 * File: app/view/ImportExecutePanel.js
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

Ext.define('MyApp.view.ImportExecutePanel', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.importexecutepanel',

    height: 250,
    itemId: 'ImportExecutePanel',
    width: 400,
    title: 'Ausführen',

    initComponent: function() {
        var me = this;

        Ext.applyIf(me, {
            items: [
                {
                    xtype: 'form',
                    border: false,
                    itemId: 'ImportParameterFormPanel',
                    bodyPadding: 10,
                    header: false,
                    title: 'Parameter'
                },
                {
                    xtype: 'form',
                    border: false,
                    itemId: 'ImportExecuteFormPanel',
                    bodyPadding: 10,
                    items: [
                        {
                            xtype: 'filefield',
                            anchor: '100%',
                            itemId: 'ImportExecuteFileUpload',
                            fieldLabel: 'CSV Datei'
                        },
                        {
                            xtype: 'button',
                            itemId: 'ImportExecuteUploadButton',
                            text: 'Upload'
                        }
                    ]
                }
            ]
        });

        me.callParent(arguments);
    }

});