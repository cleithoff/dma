/*
 * File: app/model/ProductLayoutModel.js
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

Ext.define('MyApp.model.ProductLayoutModel', {
    extend: 'Ext.data.Model',

    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'description',
            type: 'string'
        },
        {
            name: 'hotfolder',
            type: 'string'
        },
        {
            name: 'xsl_front_preview',
            type: 'string'
        },
        {
            name: 'pdf_front_preview',
            type: 'string'
        },
        {
            name: 'xsl_back_preview',
            type: 'string'
        },
        {
            name: 'pdf_back_preview',
            type: 'string'
        },
        {
            name: 'xsl_front_print',
            type: 'string'
        },
        {
            name: 'pdf_front_print',
            type: 'string'
        },
        {
            name: 'xsl_back_print',
            type: 'string'
        },
        {
            name: 'pdf_back_print',
            type: 'string'
        },
        {
            name: 'xsl_front_test',
            type: 'string'
        },
        {
            name: 'pdf_front_test',
            type: 'string'
        },
        {
            name: 'xsl_back_test',
            type: 'string'
        },
        {
            name: 'pdf_back_test',
            type: 'string'
        },
        {
            name: 'correction_form_class',
            type: 'string'
        },
        {
            name: 'plugin_classes',
            type: 'string',
            useNull: true
        },
        {
            name: 'render_print_front',
            type: 'string',
            useNull: true
        },
        {
            name: 'render_print_back',
            type: 'string',
            useNull: true
        }
    ]
});