/*
 * File: app.js
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

//@require @packageOverrides
Ext.Loader.setConfig({
    enabled: true
});

Ext.application({
    models: [
        'PartnerPartnerModel',
        'ImportPartnerTreeModel',
        'ImportOrderTreeModel',
        'ProductProductModel',
        'ProductItemModel',
        'ImportImportModel',
        'ImportActionModel',
        'OrderOrderModel',
        'OrderItemModel',
        'PackagePackageModel',
        'OrderItemstateModel',
        'OrderItemHasProductPersonalizeModel',
        'ProductLayoutModel',
        'ProductLayoutHasProductPersonalizeModel',
        'ReportReportModel',
        'MetadataModel',
        'ReportFilterModel',
        'ImportStackModel',
        'OrderItemstatelogModel',
        'PartnerAddressModel',
        'AppHelpModel',
        'UserRessourceModel',
        'ActiveUserResourceModel',
        'ProductCurrencyModel',
        'ProductDatatypeModel',
        'ProductPropertyModel',
        'ProductCustomizeModel',
        'ProductPersonalizeModel',
        'ProductPackageModel',
        'ProductCategoryModel',
        'ProductWeightModel',
        'ProductItemHasProductCustomizeModel',
        'ProductProductHasProductPropertyModel',
        'ImportParameterModel',
        'OrderMetaModel',
        'OrderItemHasOrderMeta',
        'PackagePackageorderModel',
        'ReportAdditionalModel',
        'OrderCombineitemModel',
        'OrderStateModel',
        'MailImapModel',
        'UserUserModel',
        'UserRoleModel',
        'UserResourceModel',
        'UserRoleHasUserResourceModel',
        'UserResourceHasReportReport',
        'PartnerAbsenceModel'
    ],
    stores: [
        'PartnerPartnerJsonStore',
        'ImportPartnerTreeStore',
        'ImportOrderTreeStore',
        'ProductProductOrderImportJsonStore',
        'ProductItemOrderImportJsonStore',
        'ImportImportJsonStore',
        'ImportActionJsonStore',
        'OrderOrderJsonStore',
        'OrderItemJsonStore',
        'PackagePackageJsonStore',
        'OrderItemstateJsonStore',
        'OrderItemHasProductPersonalizeJsonStore',
        'OrderItemProductItemJsonStore',
        'ProductLayoutJsonStore',
        'ProductLayoutHasProductPersonalizeJsonStore',
        'ReportReportJsonStore',
        'MetadataJsonStore',
        'ReportFilterJsonStore',
        'ImportStackJsonStore',
        'OrderItemstatelogJsonStore',
        'PartnerAddressInvoiceJsonStore',
        'PartnerAddressDeliveryJsonStore',
        'ProductProductJsonStore',
        'AppHelpJsonStore',
        'ActiveUserResourcesJsonStore',
        'ProductCurrencyJsonStore',
        'ProductDatatypeJsonStore',
        'ProductPropertyJsonStore',
        'ProductCustomizeJsonStore',
        'ProductPersonalizeJsonStore',
        'ProductPackageJsonStore',
        'ProductCategoryJsonStore',
        'ProductItemJsonStore',
        'ProductWeightJsonStore',
        'ProductItemHasProductCustomizeJsonStore',
        'ProductProductHasProductPropertyJsonStore',
        'ImportParameterJsonStore',
        'OrderMetaJsonStore',
        'OrderItemHasOrderMetaJsonStore',
        'PackagePackageorderJsonStore',
        'ReportAdditionalJsonStore',
        'OrderCombineitemJsonStore',
        'OrderStateJsonStore',
        'MailImapJsonStore',
        'UserUserJsonStore',
        'UserRoleJsonStore',
        'UserResourceJsonStore',
        'UserRoleHasUserResourceJsonStore',
        'UserResourceHasReportReportJsonStore',
        'PartnerAbsenceJsonStore'
    ],
    views: [
        'MainViewport',
        'PartnerPanel',
        'MainPanel',
        'OrderPanel',
        'ReportPanel',
        'UserPanel',
        'ProductPanel_Obsolete',
        'PartnerAddressPanel',
        'PartnerAbsencePanel',
        'PartnerImportPanel',
        'OrderImportPanel',
        'ImportPanel',
        'ImportImportPanel',
        'ImportActionPanel',
        'OrderItemPanel',
        'OrderItemDetailPanel',
        'OrderItemPackagePackagePanel',
        'OrderItemProductPersonalizePanel',
        'ReportReportPanel',
        'ImportExecutePanel',
        'AppHelpPanel',
        'LoginPanel',
        'ProductCurrencyPanel',
        'ProductDatatypePanel',
        'ProductPropertyPanel',
        'ProductCustomizePanel',
        'ProductPersonalizePanel',
        'ProductPackagePanel',
        'ProductCategoryPanel',
        'ProductLayoutPanel',
        'ProductPanel',
        'ProductItemPanel',
        'ProductWeightPanel',
        'ProductItemHasProductCustomizePanel',
        'ProductProductHasProductPropertyPanel',
        'OrderMetaPanel',
        'OrderItemOrderMetaPanel',
        'OrderPackagePackageorderPanel',
        'ReportAdditionalPanel',
        'OrderCombineitemPanel',
        'MailImapPanel',
        'UserRolePanel',
        'UserResourcePanel',
        'UserUserPanel',
        'DlgReportReportPanel',
        'DlgDataTakeoverPanel',
        'ImportStackPanel'
    ],
    autoCreateViewport: true,
    controllers: [
        'MainPanelController',
        'MainViewportController',
        'PartnerPanelController',
        'ProductPanelController',
        'UserPanelController',
        'PartnerAddressPanelController',
        'PartnerAbsencePanelController',
        'PartnerImportPanelController',
        'OrderPanelController',
        'ImportPanelController',
        'ImportImportPanelController',
        'ImportActionController',
        'OrderItemPanelController',
        'OrderItemDetailPanelController',
        'OrderItemPackagePackageController',
        'OrderItemProductPersonalizePanelController',
        'ReportReportPanelController',
        'ImportExecutePanelController',
        'AppHelpController',
        'RuleController',
        'CrudController',
        'OrderItemOrderMetaController',
        'UtilController',
        'OrderPackagePackageorderController'
    ],
    name: 'MyApp',

    launch: function() {
        Ext.override(Ext.grid.View, { enableTextSelection: true });

        Ext.override(Ext.data.Record, {
            forceDirty: function(name, value) {
                var me = this;
                if (Ext.isEmpty(value)) {
                    value = me.get(name);
                }
                me.set(name, 'forced dirty');
                me.set(name, value);
            }
        });
    }

});
