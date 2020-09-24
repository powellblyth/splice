<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Library\Services;

use App\Models\User;
use App\Library\Services\FactService;
use App\Notifications\importFromSupplierErrorNotification;
use Illuminate\Support\Facades\Log;

//// configuration data
//require_once __DIR__ . '/../config/unleashed.php';

class Unleashed
{

    const FORMAT_JSON = 'json';
    const FORMAT_XML  = 'xml';
    protected $customerCache = [];
    protected $api           = null;
    protected $apiId         = null;
    protected $apiKey        = null;
    protected $lastError     = null;
    protected $fakeData      = [
        'salesOrder'        => '{"Pagination":{"NumberOfItems":1,"PageSize":200,"PageNumber":1,"NumberOfPages":1},"Items":[{"SalesOrderLines":[{"LineNumber":1,"LineType":null,"Product":{"Guid":"de3a883c-0058-4e61-b0d6-5a438cf898e6","ProductCode":"AOPPTB","ProductDescription":"Algarve Orange Bliss (Portugal) - 4 tea bags"},"DueDate":"\/Date(1522022400000)\/","OrderQuantity":288.0000,"UnitPrice":2.4000,"DiscountRate":0.0000,"LineTotal":691.20000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":2.4000,"BCLineTotal":691.200,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"d0ece451-5734-40a0-af5f-237dc97ee713","LastModifiedOn":"\/Date(1531132187399)\/"},{"LineNumber":2,"LineType":null,"Product":{"Guid":"e7bcfdc0-91e4-4c38-9f1e-c493d7b67445","ProductCode":"DFPPTB","ProductDescription":"Douro Fruits (Portugal) - 4 tea bags"},"DueDate":"\/Date(1522022400000)\/","OrderQuantity":288.0000,"UnitPrice":2.4000,"DiscountRate":0.0000,"LineTotal":691.20000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":2.4000,"BCLineTotal":691.200,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"87c94e0f-b7b5-4b37-9eed-8cdb0fa0ca66","LastModifiedOn":"\/Date(1531132187414)\/"},{"LineNumber":3,"LineType":null,"Product":{"Guid":"0b5e2c03-9c28-4d72-9527-63e871affbef","ProductCode":"PGPPTB","ProductDescription":"Prince Earl Grey (Portugal) - 4 tea bags"},"DueDate":"\/Date(1522022400000)\/","OrderQuantity":288.0000,"UnitPrice":2.4000,"DiscountRate":0.0000,"LineTotal":691.20000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":2.4000,"BCLineTotal":691.200,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"7289b5ae-bafc-4dd8-b8db-72e7dff93e19","LastModifiedOn":"\/Date(1531132187430)\/"},{"LineNumber":4,"LineType":null,"Product":{"Guid":"de3a883c-0058-4e61-b0d6-5a438cf898e6","ProductCode":"AOPPTB","ProductDescription":"Algarve Orange Bliss (Portugal) - 4 tea bags"},"DueDate":"\/Date(1530316800000)\/","OrderQuantity":12.0000,"UnitPrice":2.4000,"DiscountRate":1.0000,"LineTotal":0.00000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":0.0000,"BCLineTotal":0.000,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"9e20de2a-df6d-4468-b092-b0c9d7a89412","LastModifiedOn":"\/Date(1531132187446)\/"},{"LineNumber":5,"LineType":null,"Product":{"Guid":"e7bcfdc0-91e4-4c38-9f1e-c493d7b67445","ProductCode":"DFPPTB","ProductDescription":"Douro Fruits (Portugal) - 4 tea bags"},"DueDate":"\/Date(1530316800000)\/","OrderQuantity":12.0000,"UnitPrice":2.4000,"DiscountRate":1.0000,"LineTotal":0.00000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":0.0000,"BCLineTotal":0.000,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"8b21d655-e9df-4589-bcb7-39686cd20766","LastModifiedOn":"\/Date(1531132187463)\/"},{"LineNumber":6,"LineType":null,"Product":{"Guid":"0b5e2c03-9c28-4d72-9527-63e871affbef","ProductCode":"PGPPTB","ProductDescription":"Prince Earl Grey (Portugal) - 4 tea bags"},"DueDate":"\/Date(1530316800000)\/","OrderQuantity":12.0000,"UnitPrice":2.4000,"DiscountRate":1.0000,"LineTotal":0.00000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":0.0000,"BCLineTotal":0.000,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"050a2dbb-c20a-44b6-9082-c91742a5925a","LastModifiedOn":"\/Date(1531132187472)\/"},{"LineNumber":0,"LineType":"Charge","Product":{"Guid":"00000000-0000-0000-0000-000000000000","ProductCode":null,"ProductDescription":"Delivery"},"DueDate":"\/Date(1531353600000)\/","OrderQuantity":1,"UnitPrice":100.00,"DiscountRate":0,"LineTotal":100.00,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":null,"TaxRate":0.000000,"LineTax":0.00,"XeroTaxCode":null,"BCUnitPrice":100.00,"BCLineTotal":100.00,"BCLineTax":0.00,"LineTaxCode":"ECZROUTPUT","XeroSalesAccount":"211","SerialNumbers":null,"BatchNumbers":null,"Guid":"2719b7d3-b74e-4539-aa1a-2362d3817e08","LastModifiedOn":null}],"OrderNumber":"SO-00000202","OrderDate":"\/Date(1528329600000)\/","RequiredDate":"\/Date(1531353600000)\/","CompletedDate":null,"OrderStatus":"Parked","Customer":{"CustomerCode":"GOODIES","CustomerName":"Vera Mares – Sociedade Unipessoal, Limitada","CurrencyId":48,"Guid":"56bb75a6-408a-4227-8536-5488a560532a","LastModifiedOn":"\/Date(1527751789491)\/"},"CustomerRef":null,"Comments":"288 of each tin paid, plus 12 of each tin FREE. Send via UPS tracking: 1Z7659V7DK02796963\n\n50% DEPOSIT PAID on presenting the invoice (7 June) and balance 50% after 60 days - by 7 August 2018.\n","Warehouse":{"WarehouseCode":"MAIN","WarehouseName":"chateaurouge.uk","IsDefault":false,"StreetNo":null,"AddressLine1":null,"AddressLine2":null,"Suburb":null,"City":null,"Region":null,"Country":null,"PostCode":null,"PhoneNumber":null,"FaxNumber":null,"MobileNumber":null,"DDINumber":null,"ContactName":null,"Obsolete":false,"Guid":"45751bd0-de3a-4636-8de0-b45601aaf4ba","LastModifiedOn":"\/Date(1527026804214)\/"},"ReceivedDate":null,"DeliveryName":"Vera Mares LDA","DeliveryStreetAddress":"Rua Vasco da Gama, 11","DeliveryStreetAddress2":null,"DeliverySuburb":null,"DeliveryCity":"Santo António da Charneca","DeliveryRegion":null,"DeliveryCountry":"Portugal","DeliveryPostCode":"2835-725","Currency":{"CurrencyCode":"GBP","Description":"United Kingdom, Pounds","Guid":"0f0c7205-f75c-44ff-9a14-7e0c4421bbea","LastModifiedOn":"\/Date(1472423480267)\/"},"ExchangeRate":1.000000,"DiscountRate":0.0000,"Tax":{"TaxCode":"ECZROUTPUT","Description":null,"TaxRate":0.000000,"CanApplyToExpenses":false,"CanApplyToRevenue":false,"Obsolete":false,"Guid":"00000000-0000-0000-0000-000000000000","LastModifiedOn":null},"TaxRate":0.000000,"XeroTaxCode":"ECZROUTPUT","SubTotal":2173.600,"TaxTotal":0.000,"Total":2173.600,"TotalVolume":0.000,"TotalWeight":0.000,"BCSubTotal":2173.600,"BCTaxTotal":0.000,"BCTotal":2173.600,"PaymentDueDate":"\/Date(1536319787117)\/","AllocateProduct":true,"SalesOrderGroup":null,"DeliveryMethod":"UPS","SalesPerson":null,"SendAccountingJournalOnly":false,"SourceId":null,"CreatedBy":"sean@chateaurouge.co.uk","CreatedOn":"\/Date(1522044434965)\/","LastModifiedBy":"sean@chateaurouge.co.uk","Guid":"b6b620d7-1d40-4488-b6fb-072e737302cc","LastModifiedOn":"\/Date(1531132187518)\/"}]}',
        'salesOrderList'    => '{"Pagination":{"NumberOfItems":4,"PageSize":200,"PageNumber":1,"NumberOfPages":1},"Items":[{"SalesOrderLines":[{"LineNumber":1,"LineType":null,"Product":{"Guid":"e271f21d-c2fa-4ba0-a280-5afec98a88f1","ProductCode":"JASLLT","ProductDescription":"Jasmine Dragon Pearls - Loose Leaf 120g Tin"},"DueDate":"\/Date(1528023545000)\/","OrderQuantity":1.0000,"UnitPrice":24.9500,"DiscountRate":0.0000,"LineTotal":24.95000000,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":13.51250000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"NONE","BCUnitPrice":24.9500,"BCLineTotal":24.950,"BCLineTax":0.000,"LineTaxCode":"NONE","XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"c8827fd6-2c73-4b14-9308-94887b54bf04","LastModifiedOn":"\/Date(1528320776134)\/"},{"LineNumber":0,"LineType":"Charge","Product":{"Guid":"00000000-0000-0000-0000-000000000000","ProductCode":null,"ProductDescription":"Shipping Cost (Royal Mail 1st Class)"},"DueDate":"\/Date(1528023545000)\/","OrderQuantity":1,"UnitPrice":3.50,"DiscountRate":0,"LineTotal":3.50,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":null,"TaxRate":0.000000,"LineTax":0.00,"XeroTaxCode":null,"BCUnitPrice":3.50,"BCLineTotal":3.50,"BCLineTax":0.00,"LineTaxCode":"NONE","XeroSalesAccount":"210","SerialNumbers":null,"BatchNumbers":null,"Guid":"1a92c73e-d817-4e59-b0cf-15bf11e53368","LastModifiedOn":null}],"OrderNumber":"Shopify_CRUK_1754","OrderDate":"\/Date(1528023545000)\/","RequiredDate":"\/Date(1528023545000)\/","CompletedDate":"\/Date(1528320776196)\/","OrderStatus":"Completed","Customer":{"CustomerCode":"621348159614","CustomerName":"Bupa Dental - Tudor-Petru Gazdac","CurrencyId":48,"Guid":"ab178fa2-429c-4736-be74-17e2dc9ceb04","LastModifiedOn":"\/Date(1528023558228)\/"},"CustomerRef":null,"Comments":null,"Warehouse":{"WarehouseCode":"MAIN","WarehouseName":"chateaurouge.uk","IsDefault":false,"StreetNo":null,"AddressLine1":null,"AddressLine2":null,"Suburb":null,"City":null,"Region":null,"Country":null,"PostCode":null,"PhoneNumber":null,"FaxNumber":null,"MobileNumber":null,"DDINumber":null,"ContactName":null,"Obsolete":false,"Guid":"45751bd0-de3a-4636-8de0-b45601aaf4ba","LastModifiedOn":"\/Date(1527026804214)\/"},"ReceivedDate":null,"DeliveryName":"Olga Vasiljeva","DeliveryStreetAddress":"Belmont Street","DeliveryStreetAddress2":"","DeliverySuburb":null,"DeliveryCity":"Bognor Regis","DeliveryRegion":"","DeliveryCountry":"United Kingdom","DeliveryPostCode":"PO21 1LG","Currency":{"CurrencyCode":"GBP","Description":"United Kingdom, Pounds","Guid":"0f0c7205-f75c-44ff-9a14-7e0c4421bbea","LastModifiedOn":"\/Date(1472423480267)\/"},"ExchangeRate":1.000000,"DiscountRate":0.0000,"Tax":{"TaxCode":"NONE","Description":null,"TaxRate":0.000000,"CanApplyToExpenses":false,"CanApplyToRevenue":false,"Obsolete":false,"Guid":"00000000-0000-0000-0000-000000000000","LastModifiedOn":null},"TaxRate":0.000000,"XeroTaxCode":"NONE","SubTotal":28.450,"TaxTotal":0.000,"Total":28.450,"TotalVolume":null,"TotalWeight":null,"BCSubTotal":28.450,"BCTaxTotal":0.000,"BCTotal":28.450,"PaymentDueDate":"\/Date(1528324376196)\/","AllocateProduct":true,"SalesOrderGroup":null,"DeliveryMethod":null,"SalesPerson":null,"SendAccountingJournalOnly":false,"SourceId":null,"CreatedBy":"Shopify","CreatedOn":"\/Date(1528023557035)\/","LastModifiedBy":"sean@chateaurouge.co.uk","Guid":"ba1d2a45-998e-4645-95ed-379b40fb7ecf","LastModifiedOn":"\/Date(1528320776290)\/"},{"SalesOrderLines":[{"LineNumber":1,"LineType":null,"Product":{"Guid":"d188c3f1-3391-4f89-9842-959b2fb58981","ProductCode":"WIELTB","ProductDescription":"Wiedouw Organic Rooibos - Tea Bags 17 Tea Bags"},"DueDate":"\/Date(1528145620000)\/","OrderQuantity":1.0000,"UnitPrice":8.9500,"DiscountRate":0.0000,"LineTotal":8.95000000,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":3.66860666666667,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"NONE","BCUnitPrice":8.9500,"BCLineTotal":8.950,"BCLineTax":0.000,"LineTaxCode":"NONE","XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"deb8a123-08a4-49da-8ffa-0711878bde10","LastModifiedOn":"\/Date(1528320774825)\/"},{"LineNumber":2,"LineType":null,"Product":{"Guid":"3f2a94a7-2123-4bad-910a-c6517ff4051d","ProductCode":"H657CL","ProductDescription":"1657 - Artisanal 70% Dark Drinking Chocolate 170g Tin"},"DueDate":"\/Date(1528145620000)\/","OrderQuantity":1.0000,"UnitPrice":11.9500,"DiscountRate":0.0000,"LineTotal":11.95000000,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":4.92771875000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"NONE","BCUnitPrice":11.9500,"BCLineTotal":11.950,"BCLineTax":0.000,"LineTaxCode":"NONE","XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"c5921769-dc2d-40f8-8c3c-24d93e4b3733","LastModifiedOn":"\/Date(1528320774778)\/"},{"LineNumber":0,"LineType":"Charge","Product":{"Guid":"00000000-0000-0000-0000-000000000000","ProductCode":null,"ProductDescription":"Shipping Cost (Royal Mail 1st Class)"},"DueDate":"\/Date(1528145620000)\/","OrderQuantity":1,"UnitPrice":3.50,"DiscountRate":0,"LineTotal":3.50,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":null,"TaxRate":0.000000,"LineTax":0.00,"XeroTaxCode":null,"BCUnitPrice":3.50,"BCLineTotal":3.50,"BCLineTax":0.00,"LineTaxCode":"NONE","XeroSalesAccount":"210","SerialNumbers":null,"BatchNumbers":null,"Guid":"4bdb007d-8cee-49ef-acdd-8be29d4382d7","LastModifiedOn":null}],"OrderNumber":"Shopify_CRUK_1756","OrderDate":"\/Date(1528145620000)\/","RequiredDate":"\/Date(1528145620000)\/","CompletedDate":"\/Date(1528320774903)\/","OrderStatus":"Completed","Customer":{"CustomerCode":"621673939070","CustomerName":"Alison Foster","CurrencyId":48,"Guid":"8b6e950d-deaf-4e25-a3ba-a18d50927c41","LastModifiedOn":"\/Date(1528147585899)\/"},"CustomerRef":null,"Comments":null,"Warehouse":{"WarehouseCode":"MAIN","WarehouseName":"chateaurouge.uk","IsDefault":false,"StreetNo":null,"AddressLine1":null,"AddressLine2":null,"Suburb":null,"City":null,"Region":null,"Country":null,"PostCode":null,"PhoneNumber":null,"FaxNumber":null,"MobileNumber":null,"DDINumber":null,"ContactName":null,"Obsolete":false,"Guid":"45751bd0-de3a-4636-8de0-b45601aaf4ba","LastModifiedOn":"\/Date(1527026804214)\/"},"ReceivedDate":null,"DeliveryName":"Alison Foster","DeliveryStreetAddress":"13 Parkway ","DeliveryStreetAddress2":"","DeliverySuburb":null,"DeliveryCity":"Irby","DeliveryRegion":"","DeliveryCountry":"United Kingdom","DeliveryPostCode":"CH61 3XJ","Currency":{"CurrencyCode":"GBP","Description":"United Kingdom, Pounds","Guid":"0f0c7205-f75c-44ff-9a14-7e0c4421bbea","LastModifiedOn":"\/Date(1472423480267)\/"},"ExchangeRate":1.000000,"DiscountRate":0.0000,"Tax":{"TaxCode":"NONE","Description":null,"TaxRate":0.000000,"CanApplyToExpenses":false,"CanApplyToRevenue":false,"Obsolete":false,"Guid":"00000000-0000-0000-0000-000000000000","LastModifiedOn":null},"TaxRate":0.000000,"XeroTaxCode":"NONE","SubTotal":24.400,"TaxTotal":0.000,"Total":24.400,"TotalVolume":null,"TotalWeight":null,"BCSubTotal":24.400,"BCTaxTotal":0.000,"BCTotal":24.400,"PaymentDueDate":"\/Date(1528324374903)\/","AllocateProduct":true,"SalesOrderGroup":null,"DeliveryMethod":null,"SalesPerson":null,"SendAccountingJournalOnly":false,"SourceId":null,"CreatedBy":"Shopify","CreatedOn":"\/Date(1528145633067)\/","LastModifiedBy":"sean@chateaurouge.co.uk","Guid":"9f92c12b-446e-4f8b-a039-06b4a49db90e","LastModifiedOn":"\/Date(1528320775004)\/"},{"SalesOrderLines":[{"LineNumber":1,"LineType":null,"Product":{"Guid":"d973285f-2b27-47cc-bbeb-92960372e82f","ProductCode":"H657RL","ProductDescription":"1657 - Artisanal 70% Dark Drinking Chocolate 500g Pouch"},"DueDate":"\/Date(1528196556000)\/","OrderQuantity":2.0000,"UnitPrice":19.9500,"DiscountRate":0.0000,"LineTotal":39.90000000,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":10.23817500000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"NONE","BCUnitPrice":19.9500,"BCLineTotal":39.900,"BCLineTax":0.000,"LineTaxCode":"NONE","XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"3fd1342d-fca5-412e-8fa0-44f06249fff8","LastModifiedOn":"\/Date(1528320773200)\/"},{"LineNumber":0,"LineType":"Charge","Product":{"Guid":"00000000-0000-0000-0000-000000000000","ProductCode":null,"ProductDescription":"Shipping Cost (Royal Mail 1st Class)"},"DueDate":"\/Date(1528196556000)\/","OrderQuantity":1,"UnitPrice":6.00,"DiscountRate":0,"LineTotal":6.00,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":null,"TaxRate":0.000000,"LineTax":0.00,"XeroTaxCode":null,"BCUnitPrice":6.00,"BCLineTotal":6.00,"BCLineTax":0.00,"LineTaxCode":"NONE","XeroSalesAccount":"210","SerialNumbers":null,"BatchNumbers":null,"Guid":"908f2e4f-40a7-4d2f-9d1d-ce360b778761","LastModifiedOn":null}],"OrderNumber":"Shopify_CRUK_1757","OrderDate":"\/Date(1528196556000)\/","RequiredDate":"\/Date(1528196556000)\/","CompletedDate":"\/Date(1528320773309)\/","OrderStatus":"Completed","Customer":{"CustomerCode":"621788168318","CustomerName":"Philip Bovey","CurrencyId":48,"Guid":"5f40093e-e96e-402c-ae61-53de3860e3cc","LastModifiedOn":"\/Date(1528196722926)\/"},"CustomerRef":null,"Comments":null,"Warehouse":{"WarehouseCode":"MAIN","WarehouseName":"chateaurouge.uk","IsDefault":false,"StreetNo":null,"AddressLine1":null,"AddressLine2":null,"Suburb":null,"City":null,"Region":null,"Country":null,"PostCode":null,"PhoneNumber":null,"FaxNumber":null,"MobileNumber":null,"DDINumber":null,"ContactName":null,"Obsolete":false,"Guid":"45751bd0-de3a-4636-8de0-b45601aaf4ba","LastModifiedOn":"\/Date(1527026804214)\/"},"ReceivedDate":null,"DeliveryName":"Philip Bovey","DeliveryStreetAddress":"102 Cleveland Gardens","DeliveryStreetAddress2":"Barnes","DeliverySuburb":null,"DeliveryCity":"LONDON","DeliveryRegion":"","DeliveryCountry":"United Kingdom","DeliveryPostCode":"SW13 0AH","Currency":{"CurrencyCode":"GBP","Description":"United Kingdom, Pounds","Guid":"0f0c7205-f75c-44ff-9a14-7e0c4421bbea","LastModifiedOn":"\/Date(1472423480267)\/"},"ExchangeRate":1.000000,"DiscountRate":0.0000,"Tax":{"TaxCode":"NONE","Description":null,"TaxRate":0.000000,"CanApplyToExpenses":false,"CanApplyToRevenue":false,"Obsolete":false,"Guid":"00000000-0000-0000-0000-000000000000","LastModifiedOn":null},"TaxRate":0.000000,"XeroTaxCode":"NONE","SubTotal":45.900,"TaxTotal":0.000,"Total":45.900,"TotalVolume":null,"TotalWeight":null,"BCSubTotal":45.900,"BCTaxTotal":0.000,"BCTotal":45.900,"PaymentDueDate":"\/Date(1528324373309)\/","AllocateProduct":true,"SalesOrderGroup":null,"DeliveryMethod":null,"SalesPerson":null,"SendAccountingJournalOnly":false,"SourceId":null,"CreatedBy":"Shopify","CreatedOn":"\/Date(1528196575094)\/","LastModifiedBy":"sean@chateaurouge.co.uk","Guid":"947af875-1f2e-4eef-b2de-29a8e0ff3629","LastModifiedOn":"\/Date(1528320773403)\/"},{"SalesOrderLines":[{"LineNumber":1,"LineType":null,"Product":{"Guid":"de3a883c-0058-4e61-b0d6-5a438cf898e6","ProductCode":"AOPPTB","ProductDescription":"Algarve Orange Bliss (Portugal) - 4 tea bags"},"DueDate":"\/Date(1522022400000)\/","OrderQuantity":288.0000,"UnitPrice":2.4000,"DiscountRate":0.0000,"LineTotal":691.20000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":2.4000,"BCLineTotal":691.200,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"d0ece451-5734-40a0-af5f-237dc97ee713","LastModifiedOn":"\/Date(1528113282768)\/"},{"LineNumber":2,"LineType":null,"Product":{"Guid":"e7bcfdc0-91e4-4c38-9f1e-c493d7b67445","ProductCode":"DFPPTB","ProductDescription":"Douro Fruits (Portugal) - 4 tea bags"},"DueDate":"\/Date(1522022400000)\/","OrderQuantity":288.0000,"UnitPrice":2.4000,"DiscountRate":0.0000,"LineTotal":691.20000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":2.4000,"BCLineTotal":691.200,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"87c94e0f-b7b5-4b37-9eed-8cdb0fa0ca66","LastModifiedOn":"\/Date(1528113312852)\/"},{"LineNumber":3,"LineType":null,"Product":{"Guid":"0b5e2c03-9c28-4d72-9527-63e871affbef","ProductCode":"PGPPTB","ProductDescription":"Prince Earl Grey (Portugal) - 4 tea bags"},"DueDate":"\/Date(1522022400000)\/","OrderQuantity":288.0000,"UnitPrice":2.4000,"DiscountRate":0.0000,"LineTotal":691.20000000,"Volume":null,"Weight":null,"Comments":"","AverageLandedPriceAtTimeOfSale":0.00000000000000,"TaxRate":0.000000,"LineTax":0.000,"XeroTaxCode":"ZERORATEDOUTPUT","BCUnitPrice":2.4000,"BCLineTotal":691.200,"BCLineTax":0.000,"LineTaxCode":null,"XeroSalesAccount":"200","SerialNumbers":null,"BatchNumbers":null,"Guid":"7289b5ae-bafc-4dd8-b8db-72e7dff93e19","LastModifiedOn":"\/Date(1528113369330)\/"},{"LineNumber":0,"LineType":"Charge","Product":{"Guid":"00000000-0000-0000-0000-000000000000","ProductCode":null,"ProductDescription":"Delivery"},"DueDate":"\/Date(1530316800000)\/","OrderQuantity":1,"UnitPrice":100.00,"DiscountRate":0,"LineTotal":100.00,"Volume":null,"Weight":null,"Comments":null,"AverageLandedPriceAtTimeOfSale":null,"TaxRate":0.000000,"LineTax":0.00,"XeroTaxCode":null,"BCUnitPrice":100.00,"BCLineTotal":100.00,"BCLineTax":0.00,"LineTaxCode":"ECZROUTPUT","XeroSalesAccount":"211","SerialNumbers":null,"BatchNumbers":null,"Guid":"2719b7d3-b74e-4539-aa1a-2362d3817e08","LastModifiedOn":null}],"OrderNumber":"SO-00000202","OrderDate":"\/Date(1528329600000)\/","RequiredDate":"\/Date(1530316800000)\/","CompletedDate":null,"OrderStatus":"Parked","Customer":{"CustomerCode":"GOODIES","CustomerName":"Vera Mares – Sociedade Unipessoal, Limitada","CurrencyId":48,"Guid":"56bb75a6-408a-4227-8536-5488a560532a","LastModifiedOn":"\/Date(1527751789491)\/"},"CustomerRef":null,"Comments":"50% DEPOSIT payable on presenting the invoice (7 June) and balance 50% after 60 days - by 7 August 2018.","Warehouse":{"WarehouseCode":"MAIN","WarehouseName":"chateaurouge.uk","IsDefault":false,"StreetNo":null,"AddressLine1":null,"AddressLine2":null,"Suburb":null,"City":null,"Region":null,"Country":null,"PostCode":null,"PhoneNumber":null,"FaxNumber":null,"MobileNumber":null,"DDINumber":null,"ContactName":null,"Obsolete":false,"Guid":"45751bd0-de3a-4636-8de0-b45601aaf4ba","LastModifiedOn":"\/Date(1527026804214)\/"},"ReceivedDate":null,"DeliveryName":"Vera Mares LDA","DeliveryStreetAddress":"Rua Vasco da Gama, 11","DeliveryStreetAddress2":null,"DeliverySuburb":null,"DeliveryCity":"Santo António da Charneca","DeliveryRegion":null,"DeliveryCountry":"Portugal","DeliveryPostCode":"2835-725","Currency":{"CurrencyCode":"GBP","Description":"United Kingdom, Pounds","Guid":"0f0c7205-f75c-44ff-9a14-7e0c4421bbea","LastModifiedOn":"\/Date(1472423480267)\/"},"ExchangeRate":1.000000,"DiscountRate":0.0000,"Tax":{"TaxCode":"ECZROUTPUT","Description":null,"TaxRate":0.000000,"CanApplyToExpenses":false,"CanApplyToRevenue":false,"Obsolete":false,"Guid":"00000000-0000-0000-0000-000000000000","LastModifiedOn":null},"TaxRate":0.000000,"XeroTaxCode":"ECZROUTPUT","SubTotal":2173.600,"TaxTotal":0.000,"Total":2173.600,"TotalVolume":0.000,"TotalWeight":0.000,"BCSubTotal":2173.600,"BCTaxTotal":0.000,"BCTotal":2173.600,"PaymentDueDate":"\/Date(1532953044738)\/","AllocateProduct":true,"SalesOrderGroup":null,"DeliveryMethod":"Dachser","SalesPerson":null,"SendAccountingJournalOnly":false,"SourceId":null,"CreatedBy":"sean@chateaurouge.co.uk","CreatedOn":"\/Date(1522044434965)\/","LastModifiedBy":"sean@chateaurouge.co.uk","Guid":"b6b620d7-1d40-4488-b6fb-072e737302cc","LastModifiedOn":"\/Date(1528113369361)\/"}]}',
        'batchesForProduct' => '{"Pagination":{"NumberOfItems":4,"PageSize":200,"PageNumber":1,"NumberOfPages":1},"Items":[{"Number":"L8206","ExpiryDate":"\/Date(1596153600000)\/","Quantity":3.0000,"OriginalQty":3.0000,"ProductCode":"MAYACL","WarehouseCode":"MAIN","Status":"Available","LastModifiedBy":"sean@chateaurouge.co.uk","CreatedBy":"sean@chateaurouge.co.uk","CreatedOn":"\/Date(1532609584486)\/","Guid":"3cce17d9-5904-40de-9161-3fd8a8bd6c13","LastModifiedOn":"\/Date(1532609584486)\/"},{"Number":"L8206","ExpiryDate":"\/Date(1596153600000)\/","Quantity":32.0000,"OriginalQty":32.0000,"ProductCode":"MAYACL","WarehouseCode":"AMAZON_CR_UK","Status":"Available","LastModifiedBy":"sean@chateaurouge.co.uk","CreatedBy":"sean@chateaurouge.co.uk","CreatedOn":"\/Date(1532609547015)\/","Guid":"f85eb454-38c3-4f5a-910e-dc252a8adaf6","LastModifiedOn":"\/Date(1532609547015)\/"},{"Number":"L8045","ExpiryDate":"\/Date(1580428800000)\/","Quantity":5.0000,"OriginalQty":6.0000,"ProductCode":"MAYACL","WarehouseCode":"MAIN","Status":"Available","LastModifiedBy":"sean@chateaurouge.co.uk","CreatedBy":"sean@chateaurouge.co.uk","CreatedOn":"\/Date(1525993565688)\/","Guid":"e6ef107a-7bf7-43e2-8529-9ecaa8e8efba","LastModifiedOn":"\/Date(1532300589429)\/"},{"Number":"L8111","ExpiryDate":"\/Date(1588204800000)\/","Quantity":35.0000,"OriginalQty":45.0000,"ProductCode":"MAYACL","WarehouseCode":"AMAZON_CR_UK","Status":"Available","LastModifiedBy":"sean@chateaurouge.co.uk","CreatedBy":"sean@chateaurouge.co.uk","CreatedOn":"\/Date(1525995228830)\/","Guid":"8876c6d4-42f4-4573-a17c-1da32728380c","LastModifiedOn":"\/Date(1532190834742)\/"}]}'
    ];

    public function __construct()
    {
//        var_dump(config('unleashed'));
        $this->api    = config('unleashed.api_url');
        $this->apiId  = config('unleashed.api_id');
        $this->apiKey = config('unleashed.api_key');

        if (empty($this->api) || empty($this->apiId) || empty($this->apiKey)) {
            throw new \Exception('You must specify UNLEASHED_API_URL, UNLEASHED_API_ID, UNLEASHED_API_KEY ENV variables');
        }
    }

    /**
     *
     * Get the request signature:
     * Based on your API id and the request portion of the url
     * - $request is only any part of the url after the "?"
     * - use $request = "" if there is no request portion
     * - for GET $request will only be the filters eg ?customerName=Bob
     * - for POST $request will usually be an empty string
     * - $request never includes the "?"
     * Using the wrong value for $request will result in an 403 forbidden response from the API
     */
    public function getSignature($request, string $key)
    {
        return base64_encode(hash_hmac('sha256', $request, $key, true));
    }

    /**
     * @param string $id
     * @param string $signature
     * @param string $endpoint
     * @param string $requestUrl
     * @param string $format
     * @return false|resource
     * Create the curl object and set the required options
     * - $api will always be https://api.unleashedsoftware.com/
     * - $endpoint must be correctly specified
     * - $requestUrl does include the "?" if any
     * Using the wrong values for $endpoint or $requestUrl will result in a failed API call
     */
    public function getCurl(string $id, string $signature, string $endpoint, string $requestUrl, string $format)
    {
        $this->lastError = null;
        $curl            = curl_init($this->api . $endpoint . $requestUrl);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/$format",
                                                     "Accept: application/$format", "api-auth-id: $id", "api-auth-signature: $signature"));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 20);
        // these options allow us to read the error message sent by the API
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_HTTP200ALIASES, range(400, 599));

        if (0 !== curl_errno($curl)) {
            $this->lastError = curl_error($curl);
        }
        return $curl;
    }

    /**
     * @param $id
     * @param $key
     * @param $endpoint
     * @param $request
     * @param $format
     * @return bool|string
     * GET something from the API
     * - $request is only any part of the url after the "?"
     * - use $request = "" if there is no request portion
     * - for GET $request will only be the filters eg ?customerName=Bob
     * - $request never includes the "?"
     * Format agnostic method.  Pass in the required $format of FORMAT_JSON or self::FORMAT_XML
     */
    function get($id, $key, $endpoint, $request, $format)
    {
        $this->lastError = null;
        $requestUrl      = "";
        try {
            // calculate API signature
            $requestToSign = '';
            $requestToSend = '';
            if (is_array($request)) {
                foreach ($request as $foreachKey => $value) {
                    $requestToSend .= '&' . $foreachKey . "=" . urlencode($value);
                    $requestToSign .= '&' . $foreachKey . "=" . $value;
                }
                $requestToSend = ltrim($requestToSend, '&');
                $requestToSign = ltrim($requestToSign, '&');
            } else {
                $requestToSign = $request;
                $requestToSend = $request;
            }
            $signature = $this->getSignature($requestToSign, $key);
            if (!empty($requestToSend)) {
                $requestUrl = "?$requestToSend";
            }
            echo "REQUEST URI is " . $endpoint . $requestUrl . "\n";
            // create the curl object
            $curl = $this->getCurl($id, $signature, $endpoint, $requestUrl, $format);
            // GET something
            $curl_result = curl_exec($curl);
//            Log::error($curl_result);
            if (0 !== curl_errno($curl)) {
                $this->lastError = curl_error($curl);
//                die(var_dump(curl_errno($curl)));
            }
            curl_close($curl);
            return $curl_result;
        } catch (Exception $e) {
            Log::error('Error: ' + $e);
        }
    }

// POST something to the API
// - $request is only any part of the url after the "?"
// - use $request = "" if there is no request portion
// - for POST $request will usually be an empty string
// - $request never includes the "?"
// Format agnostic method.  Pass in the required $format of FORMAT_JSON or self::FORMAT_XML
    function post($id, $key, $endpoint, $format, $dataId, $data)
    {
        $this->lastError = null;
        if (!isset($dataId, $data)) {
            return null;
        }

        try {
            // calculate API signature
            $signature = $this->getSignature("", $key);
            // create the curl object.
            // - POST always requires the object's id
            $curl = $this->getCurl($id, $signature, "$endpoint/$dataId", "", $format);
            // set extra curl options required by POST
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            // POST something
            echo 'trying to post ' . print_r($data, true) . ' to ' . "$endpoint/$dataId" . "\n";
            die();
            $curl_result = curl_exec($curl);
            Log::error($curl_result);
            if (0 !== curl_errno($curl)) {
//                die(var_dump(curl_error($curl)));
                $this->lastError = curl_error($curl);
            }
            curl_close($curl);
//            die('here');
            return $curl_result;
        } catch (Exception $e) {
//die(var_Dump($e))Order
            Log::error('Error: ' + $e);
        }
    }
// GET in JSON format
// - gets the data from the API and converts it to an stdClass object
    function getData($endpoint, $request, bool $fakeData = false)
    {
        // GET it, decode it, return it
        if ($fakeData) {
            return json_decode($fakeData);
        } else {
            return json_decode($this->get($this->apiId, $this->apiKey, $endpoint, $request, self::FORMAT_JSON));
        }
    }

// POST in JSON format
// - the object to POST must be a valid stdClass object. Not array, not associative.
// - converts the object to string and POSTs it to the API
    function postData(string $endpoint, string $dataId, $data)
    {
        // POST it, return the API's response
        return $this->post($this->apiId, $this->apiKey, $endpoint, self::FORMAT_JSON, $dataId, json_encode($data));
    }

// Example method: GET customer list in xml or json
    function getCustomers(bool $fakeData = false)
    {
        return $this->getData("Customers", "", $fakeData);
    }

// Example method: GET customer list, filtered by name, in xml or json
    function getCustomersByName(string $customerName, bool $fakeData = false)
    {
        return $this->getData("Customers", "customerName=$customerName");
    }

// Example method: GET customer list, filtered by name, in xml or json
    function getCustomer(string $guid, bool $fakeData = false)
    {
        if (!array_key_exists($guid, $this->customerCache)) {
            echo "Fetching Customer $guid\n";
            $customer                   = $this->getData("Customers/$guid", "Guid=$guid");
            $this->customerCache[$guid] = $customer;
        } else {
            echo "$guid already cached\n";
        }

        return $this->customerCache[$guid];
    }

// Example method: GET orders since, filtered by name, in xml or json
    function getSalesOrdersSince($startDate, $endDate, bool $fakeData = false)
    {
        return $this->getData("SalesOrders", ['startDate' => $startDate, 'endDate' => $endDate], ($fakeData ? $this->fakeData['salesOrderList'] : false));
    }

// Example method: GET orders since, filtered by name, in xml or json
    function getSalesOrdersModifiedSince(string $startDate, string $endDate, int $pageNumber = 1, array $extraParams = [], bool $fakeData = false)
    {
        $params = array_merge(['modifiedSince' => $startDate, 'endDate' => $endDate], $extraParams);
        return $this->getData("SalesOrders/" . $pageNumber, $params, ($fakeData ? $this->fakeData['salesOrderList'] : false));
    }

    function getProductsModifiedSince(string $startDate, string $endDate, int $pageNumber = 1, array $extraParams = [], bool $fakeData = false)
    {
        $params = array_merge(['modifiedSince' => $startDate, 'endDate' => $endDate], $extraParams);
        return $this->getData("Products/" . $pageNumber, $params, ($fakeData ? $this->fakeData['ProductsList'] : false));
    }

    public function getProductGroups(string $unused, string $unused2, int $unused3, array $extraParams = [])
    {
//        $params = array_merge($extraParams);
        return $this->getData("ProductGroups/", $extraParams);
    }

    function getWarehouseStockTransfers(string $startDate, string $endDate, int $pageNumber = 1, array $extraParams = [], bool $fakeData = false)
    {
        $params = array_merge(['modifiedSince' => $startDate, 'endDate' => $endDate], $extraParams);
        return $this->getData("WarehouseStockTransfers/" . $pageNumber, $params, ($fakeData ? $this->fakeData['ProductsList'] : false));
    }

    function getWarehouses(string $startDate, string $endDate, int $pageNumber = 1, array $extraParams = [], bool $fakeData = false)
    {
        $params = array_merge(['modifiedSince' => $startDate, 'endDate' => $endDate], $extraParams);
        return $this->getData("Warehouses/" . $pageNumber, $params, ($fakeData ? $this->fakeData['ProductsList'] : false));
    }

// Example method: GET orders since, filtered by name, in xml or json
    function getBatchesForProduct(string $productCode, string $warehouseCode, bool $fakeData = false)
    {
        return $this->getData("BatchNumbers", ['ProductCode' => $productCode, 'WarehouseCode' => $warehouseCode], ($fakeData ? $this->fakeData['batchesForProduct'] : false));
    }

    public function getSalesOrder(string $orderNum, bool $fakeData = false)
    {
        return $this->getData("SalesOrders", ['orderNumber' => $orderNum], ($fakeData ? $this->fakeData['salesOrder'] : false));
    }

    public function getSalesOrderOfStatus(string $status, bool $fakeData = false)
    {
        return $this->getData("SalesOrders", ['orderStatus' => $status], ($fakeData ? $this->fakeData['salesOrder'] : false));
    }

    public function postSerialBatch(string $guid, bool $newSerialBatch = true)
    {
        return $this->postData("SalesOrders", $guid, ['serialBatch' => $newSerialBatch]);
    }

// Example method: POST a customer in xml or json
    function postCustomer($customer)
    {
        $this->postData("Customers", $customer->Guid, $customer);
    }

// Example method: POST a purchase order in xml or json
    function postPurchaseOrder($purchase)
    {
        return $this->postData("PurchaseOrders", $purchase->Guid, $purchase);
    }

// Example method: POST a sales order in xml or json
    function postSalesOrder($salesOrder)
    {
        return $this->postData("SalesOrders", $salesOrder->Guid, $salesOrder);
    }

    public static function getTimestampFromUnleashedDate(string $date): int
    {
        $search = '/(.*)([0-9]{13})(.*)/';
        return (int) preg_replace($search, '\\2', $date) / 1000;

    }

    public static function getCourierIdFromProductDescription(string $shippingProductDescription): ?string
    {
        $result = null;
        if (0 === strpos($shippingProductDescription, 'Shipping Cost')) {

            $info            = trim(str_replace('Shipping Cost', '', $shippingProductDescription));
            $removedBrackets = str_replace(['(', ')'], '', $info);
            foreach (config('unleashed.couriers') as $internalCourierName => $unleashedCourierName) {
                if (is_array(config('unleashed.shipping_methods.' . $internalCourierName))) {
                    foreach (config('unleashed.shipping_methods.' . $internalCourierName) as $internalShippingProduct => $shippingProductName) {
                        if ($shippingProductName == $removedBrackets) {
                            $result = $internalCourierName;
                            break(2);
                        }
                    }
                }
            }

        }
        return $result;
    }

    /**
     * Take a shipping string from unleashed and turn it into something we recognise
     * @param string $shippingProductDescription
     * @return string|null
     */
    public static function getDeliveryMethodIdFromProductDescription(string $shippingProductDescription): ?string
    {
        $result = null;
        if (false !== strpos($shippingProductDescription, 'Shipping Cost')) {
            $info            = trim(str_replace('Shipping Cost', '', $shippingProductDescription));
            $removedBrackets = str_replace(['(', ')'], '', $info);
            foreach (config('unleashed.shipping_methods') as $courierName => $validShippingMethods) {
                foreach ($validShippingMethods as $internalKey => $unleashedName) {
                    if ($removedBrackets == $unleashedName) {
                        $result = $internalKey;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * @param string $factName
     * @param string $className
     * @param string $unleashedFunctionName
     * @param boolean $refresh force the date to refresh
     * @param array $extraParams any extra params to push
     */
    public function importThing(string $factName, string $className, string $unleashedFunctionName, bool $refresh = false, array $extraParams = [])
    {

        $factService = FactService::getInstance();
        $dateTo      = gmdate('Y-m-d\TH:i:s.000');
        if (false === $refresh) {
            $dateFrom = $factService->getFactValue($factName, '2001-01-01T19:20+01:00');
        } else {
            $dateFrom = '2001-01-01T19:20+01:00';
        }

        vaR_dump([$dateFrom, $dateTo]);
        echo "\n";
        $page = 1;
        try {
            do {
                echo "LOOP BEGIN\n";
                if (!method_exists($this, $unleashedFunctionName)) {
                    throw new \Exception('cannot call ' . $unleashedFunctionName . ' at unleashed');
                }
                $response = $this->{$unleashedFunctionName}($dateFrom, $dateTo, $page, $extraParams);

                echo "====================\n\n\n";
                if (!isset($response->Items)) {
                    Log::error('Error downloading ' . $className . 's using ' . $unleashedFunctionName);
                    Log::error(json_encode($response));
                    Log::error($this->lastError);
                    throw new \Exception('Error downloading items, something went wrong between me and the server ' . $className . 's using ' . $unleashedFunctionName . ', ' . $this->lastError);
                }
                $unleashedObjectSet = $response->Items;
                if (is_array($unleashedObjectSet)) {
                    foreach ($unleashedObjectSet as $counter => $unleashedObject) {
                        $dbObject = $className::where('guid', $unleashedObject->Guid)->where('source', 'unleashed')->first();
                        if (!$dbObject instanceof $className) {
                            $dbObject = new $className();
                        }
                        $dbObject->populateFromUnleashed($unleashedObject);
                    }
                }
                $page++;
                if (isset($response->Pagination)) {
                    $numberOfPages = $response->Pagination->NumberOfPages;
                } else {
                    $numberOfPages = 1;
                }
            } while ($page <= $numberOfPages);
            $factService->setFact($factName, $dateTo);
        } catch (\Illuminate\Database\QueryException $e) {
            echo "SQL: " . $e->getSql() . "\n";
            echo "MESSAGE: " . $e->getMessage() . "\n";
            $notifiableUsers = User::Where('notify_about_system_errors', true)->get();
            $notification    = new importFromSupplierErrorNotification('Unleashed', $className, ['label' => 'QueryException', 'sql' => $e->getSql(), 'message' => $e->getMessage()]);
            foreach ($notifiableUsers as $user) {
                try {
                    $user->notify($notification);
                } catch (\Aws\Ses\Exception\SesException $e) {
                    ;
                }
            }
            exit();
        } catch (\Exception $e) {
            $notifiableUsers = User::Where('notify_about_system_errors', true)->get();
            $notification    = new importFromSupplierErrorNotification('Unleashed', $className, ['Exception' => 'importThing - ' . $unleashedFunctionName . ' - ' . get_class($e) . ' - ' . $e->getMessage()]);
            foreach ($notifiableUsers as $user) {
                try {
                    $user->notify($notification);
                } catch (\Aws\Ses\Exception\SesException $e) {
                    ;
                }
            }
            var_dump($e->getMessage());
            exit(4);
        }
    }
}
