/*
 * File: app/model/ActiveUserResourceModel.js
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

Ext.define('MyApp.model.ActiveUserResourceModel', {
    extend: 'Ext.data.Model',

    fields: [
        {
            name: 'id',
            persist: false,
            type: 'int'
        },
        {
            name: 'name',
            persist: false,
            type: 'string'
        },
        {
            name: 'slug',
            persist: false,
            type: 'string'
        },
        {
            name: 'allow',
            persist: false,
            type: 'int'
        },
        {
            name: 'deny',
            persist: false,
            type: 'int'
        }
    ]
});