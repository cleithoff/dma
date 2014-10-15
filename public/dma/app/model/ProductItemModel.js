/*
 * File: app/model/ProductItemModel.js
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

Ext.define('MyApp.model.ProductItemModel', {
    extend: 'Ext.data.Model',

    fields: [
        {
            name: 'id',
            type: 'int'
        },
        {
            name: 'title',
            type: 'string'
        },
        {
            name: 'product_product_id',
            type: 'int'
        },
        {
            name: 'product_product',
            persist: false
        },
        {
            name: 'product_layout_id',
            type: 'int'
        },
        {
            name: 'price',
            type: 'float'
        },
        {
            name: 'weight',
            type: 'float'
        },
        {
            name: 'released',
            type: 'boolean'
        },
        {
            name: 'amount_available',
            type: 'int'
        },
        {
            name: 'product_layout',
            persist: false
        },
        {
            name: 'product_item_no'
        },
        {
            name: 'product_item_no_internal'
        },
        {
            name: 'product_item_no_external'
        },
        {
            name: 'product_item_no_internal_stock'
        },
        {
            name: 'product_item_no_external_stock'
        },
        {
            convert: function(v, rec) {
                return rec.data.product_product.title + ', ' + rec.data.title; 
            },
            name: 'fulltitle',
            persist: false,
            type: 'string',
            useNull: true
        }
    ]
});