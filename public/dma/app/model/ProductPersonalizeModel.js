/*
 * File: app/model/ProductPersonalizeModel.js
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

Ext.define('MyApp.model.ProductPersonalizeModel', {
    extend: 'Ext.data.Model',

    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'label',
            type: 'string'
        },
        {
            name: 'key',
            type: 'string'
        },
        {
            name: 'default',
            type: 'string'
        },
        {
            name: 'product_datatype_id',
            type: 'int'
        }
    ]
});