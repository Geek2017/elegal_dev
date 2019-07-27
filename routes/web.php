<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('test', function(){
    return view('test');
});

Route::get('add-to-log', 'HomeController@myTestAddToLog');
Route::get('logs', 'HomeController@logActivity')->name('logs');
Route::get('dt-get-logs', 'HomeController@dtGetLogs')->name('dt-get-logs');
Route::get('get-logs', 'HomeController@getLogs')->name('get-logs');

Route::get('print-layout-billing', 'PrintController@printBillingLayout')->name('print-layout-billing');
Route::get('print-billing', 'PrintController@printBilling')->name('print-billing');
Route::get('pdf-billing', 'PrintController@pdfBilling')->name('pdf-billing');

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'HomeController@index');

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('users', 'DashboardController@users')->name('dashboard.users');
        Route::get('clients', 'DashboardController@clients')->name('dashboard.clients');
        Route::get('cases', 'DashboardController@cases')->name('dashboard.cases');
        Route::get('chargeable-expenses', 'DashboardController@chargeableExpenses')->name('dashboard.chargeable-expenses');
    });

    Route::post('upload-image',array('as'=>'upload-image','uses'=>'ImageController@imageUpload'));
    Route::get('move-image/{image}', 'ImageController@imageMove')->name('move-image');

    Route::resource('profile', 'ProfileController')->only([
        'index', 'store', /*'create', 'edit', 'update'*/
    ]);

    Route::view('googleapi','googleapi');

    Route::get('case-tracker/alerts', 'CaseTrackerController@alerts')->name('case-tracker.alert');

    // GLOBAL ROUTES -----------> START
    Route::get('fees', 'FeeController@feeList')->name('fees');
    Route::get('get-case-name', 'ContractController@casename')->name('get-case-name');
    // GLOBAL ROUTES -----------> END

    // UNUSED ROUTES -----------> START
    Route::resource('transaction', 'TransactionController');
    Route::get('tran-fee-action', 'TransactionController@tranFeeAction')->name('tran-fee-action');
    Route::post('case-fee-store', 'TransactionController@caseFeeStore')->name('case-fee-store');
    Route::get('get-fund', 'TransactionController@getTrustFund')->name('get-fund');
    Route::get('get-trans-fee', 'TransactionController@getTransFee')->name('get-trans-fee');
    Route::get('transaction-amount', 'TransactionController@transactionAmount')->name('transaction-amount');
    Route::get('action-contract-case', 'CaseManagementController@actionCase')->name('action-contract-case');
    Route::get('create-case', 'CaseManagementController@createCase')->name('create-case');
    Route::get('add-co-counsel', 'CaseManagementController@addCoCounsel')->name('add-co-counsel');
    Route::get('remove-co-counsel', 'CaseManagementController@removeCoCounsel')->name('remove-co-counsel');
    Route::get('update-case', 'CaseManagementController@updateCase')->name('update-case');
    Route::resource('chargeable', 'ChargeableExpenseController');
    Route::get('billing-mockup', 'BillingController@mockUp')->name('billing-mockup');
    Route::get('billing-service-report', 'BillingController@getServiceReport')->name('billing-service-report');
    Route::get('fetch-service-report', 'BillingController@fetchServiceReport')->name('fetch-service-report');
    Route::get('bill-info/{id}', 'BillingController@billInfo')->name('bill-info');
    Route::get('balance', 'BillingController@balance')->name('balance');
    //  ROUTES -----------> END

    // UNDEFINED ROUTES -----------> START
    Route::get('case-tracker/pending-actions', 'CaseTrackerController@pendingCaseActions')->name('case-tracker.pending-actions');
    // use to get the latest of service report of the case query params id={caseId}
    Route::get('case/latest/service-reports', 'CaseManagementController@caseServiceReport')->name('case.latest.service-reports');
    Route::get('case_management/lists', 'CaseManagementController@cases')->name('case_management.lists');
    Route::resource('case', 'CaseManagementController');


    // UNDEFINED ROUTES -----------> END

// ROUTE GROUPS FOR PERMISSIONS ----------------------------------------------------------------------------------------

    /* Client Route group */
    Route::group(['middleware' => ['permission:browse-client']], function () {
        Route::resource('client', 'ClientController');
        Route::get('client-destroy', 'ClientController@clientDestroy')->name('client-destroy');

        Route::get('client-list', 'ClientController@clientList')->name('client-list');
        Route::get('clients/list', 'ClientController@clientListSelect')->name('clients-list-select2');

        // get trust fund record
        Route::get('trust-fund-record', 'TransactionController@trustFundRecord')->name('trust-fund-record');
        Route::post('store-fund', 'TransactionController@storeTrustFund')->name('store-fund');

        Route::resource('business','BusinessController');
    });

    /* Counsel Route group */
    Route::group(['middleware' => ['permission:browse-counsel']], function () {
        Route::resource('counsel', 'CounselController');
        Route::get('counsel-list', 'CounselController@getList')->name('counsel-list');
    });

    /* Contract Route group */
    Route::group(['middleware' => ['permission:browse-contract']], function () {

        Route::resource('contract', 'ContractController');
        Route::get('create-contract/{id}', 'ContractController@createContract')->name('create-contract');
        Route::post('update-contract/{id}', 'ContractController@updateContract')->name('update-contract');
        Route::get('contract-store', 'ContractController@contractStore')->name('contract-store');
        Route::get('case-counsels', 'ContractController@getCaseCounsel')->name('case-counsels');
        Route::get('contract-fee', 'ContractController@contractFee')->name('contract-fee');
        Route::get('contract-list', 'ContractController@getList')->name('contract-list');

        Route::get('edit-case', 'CaseManagementController@editCase')->name('edit-case');
        Route::get('delete-case', 'CaseManagementController@deleteCase')->name('delete-case');
        Route::post('store-contract-case', 'CaseManagementController@storeCase')->name('store-contract-case');
        Route::get('load-counsel', 'CaseManagementController@loadCounsel')->name('load-counsel');

        Route::get('check-transaction', 'TransactionController@checkTransaction')->name('check-transaction');
        Route::post('store-fee', 'TransactionController@storeFee')->name('store-fee');
        Route::get('delete-fee', 'TransactionController@deleteFee')->name('delete-fee');
        Route::get('store-duplicate-fee', 'TransactionController@duplicatefee')->name('store-duplicate-fee');

        Route::get('get-client-list', 'ClientController@getClientList')->name('get-client-list');

    });

    /* Case Tracker Route group */
    Route::group(['middleware' => ['permission:browse-case-tracker']], function () {
        Route::get('case-tracker/clients/case/{id}', 'CaseTrackerController@show')->name('case-tracker.client-case');
        Route::get('case-tracker/{id}/delete', 'CaseTrackerController@destroy')->name('case-tracker.delete-client-case');
        Route::post('case-tracker/{id}/update', 'CaseTrackerController@update')->name('case-tracker.update-client-case');
        Route::resource('case-tracker', 'CaseTrackerController')->only([
            'index', 'store',
        ]);
    });

    /* Office Supply Route group */
    Route::group(['middleware' => ['permission:browse-supply-management']], function () {
        Route::get('supplies', 'SupplyController@getList')->name('supplies');
        Route::get('supplies/print', '\Reports\SupplyReportReportController@index')->name('supply.print');
        Route::resource('supply', 'SupplyController')->only([
            'index', 'create', 'edit', 'store', 'update'
        ]);
    });

    /* Charge Slip Route group */
    Route::group(['middleware' => ['permission:browse-charge-slip']], function () {
        Route::get('walk-in/charge-slip-list', 'WalkInChargeSlipController@getList')->name('walk-in.charge-slip.lists');
        Route::get('walk-in/charge-slip/create', 'WalkInChargeSlipController@create')->name('walk-in.charge-slip.create');
        Route::get('walk-in/charge-slip', 'WalkInChargeSlipController@index')->name('walk-in.charge-slip.index');
        Route::get('walk-in/charge-slip/{id}', 'WalkInChargeSlipController@edit')->name('walk-in.charge-slip.edit');
        Route::post('walk-in/charge-slip', 'WalkInChargeSlipController@store')->name('walk-in.charge-slip.post');
        Route::patch('walk-in/charge-slip/{id}', 'WalkInChargeSlipController@update')->name('walk-in.charge-slip.update');
        Route::get('walk-in/charge-slip/{id}/print', '\Reports\WalkInChargeSlipController@index')->name('walk-in.charge-slip.print');
        Route::get('charge-slip/walk-in/{id}/delete', 'WalkInChargeSlipController@destroy')->name('walk-in.charge-slip.destroy');

        Route::get('fees/general-category', 'WalkInChargeSlipController@generalFeeList')->name('general.fees');
    });

    /* Cash Receipt Route group */
    Route::group(['middleware' => ['permission:browse-cash-receipt']], function () {
        Route::get('cash-receipt/client-payment-opt', 'CashReceiptController@getPayment')->name('client-payment-opt');
        Route::get('cash-receipt/list', 'CashReceiptController@getCashReceipts')->name('cash-receipt-list');
        Route::resource('cash-receipt', 'CashReceiptController')->only([
            'index', 'store', 'create', 'edit', 'update'
        ]);
    });

    /* Report Route group */
    Route::group(['middleware' => ['permission:browse-report']], function () {
        Route::get('cash-receipts', 'ReportController@cashReceiptView')->name('reports.cash-receipt');

        Route::get('counsel-service-reports', 'ReportController@counselServiceReportView')->name('reports.counsel-service-reports');
        Route::get('show/cash-receipt-paymments', '\Reports\CashReceiptPaymentsReportController@index')->name('reports.show-cash-receipt');
        Route::get('show/counsel-service-reports', '\Reports\CounselServiceReportReportController@index')->name('reports.show-counsel-report-service');

        // trust fund
        Route::get('trust-fund', 'TrustFundController@index')->name('trust-fund.index');
        Route::get('trust-fund/members', 'TrustFundController@show')->name('trust-fund.members');
        Route::get('trust-fund/print/ledgers/members', '\Reports\TrustFunController@index')->name('trust-fund.print');
        Route::get('trust-fund/print/ledgers/all', '\Reports\TrustFunController@all')->name('trust-fund.print-all');
    });

    /* Activity Report Sheet Route group */
    Route::group(['middleware' => ['permission:browse-activity-report-sheet']], function () {
        Route::resource('ars', 'ArsController')->only([
            'index', 'create', 'edit', 'store', 'update'
        ]);
        Route::get('ars/{id}/delete', 'ArsController@destroy')->name('ars.destroy');
        Route::get('ars/{id}/print', '\Reports\ArsReportController@index')->name('ars.print');

        Route::get('clients/service-reports', 'ArsController@getClientServiceReportNo')->name('ars.clients-service-reports');

        Route::get('ars-list', 'ArsController@getList')->name('ars-list');
        Route::get('create-ars', 'ArsController@createCase')->name('create-ars');
        Route::get('ars/client/cases', 'TransactionController@clientCases')->name('ars.client-cases');
    });

    /* Service Report Route group */
    Route::group(['middleware' => ['permission:browse-service-report']], function () {
        /* Service Report Route */
        Route::resource('service-report','ServiceReportController');
        Route::get('get-client-contract', 'ServiceReportController@clientContract')->name('get-client-contract');
        Route::get('get-contract-info', 'ServiceReportController@contractInfo')->name('get-contract-info');
        Route::get('get-fee-info', 'ServiceReportController@feeInfo')->name('get-fee-info');
        Route::get('get-fee-sr-info', 'ServiceReportController@feeSrInfo')->name('get-fee-sr-info');
        Route::post('store-service-report', 'ServiceReportController@storeServiceReport')->name('store-service-report');
        Route::get('get-service-report', 'ServiceReportController@getServiceReport')->name('get-service-report');
        Route::post('store-chargeable-expense', 'ServiceReportController@storeChargeableExpense')->name('store-chargeable-expense');
        Route::get('delete-chargeable-expense', 'ServiceReportController@deleteChargeableExpense')->name('delete-chargeable-expense');
        Route::get('delete-service-report', 'ServiceReportController@deleteServiceReport')->name('delete-service-report');
        Route::get('get-fee-desc', 'FeeController@getFeeDesc')->name('get-fee-desc');
    });

    /* Billing Route group */
    Route::group(['middleware' => ['permission:browse-billing']], function () {
        Route::resource('billing', 'BillingController');
        Route::get('get-billable-contract', 'BillingController@getBillables')->name('get-billable-contract');
        Route::post('update-billing', 'BillingController@updateBilling')->name('update-billing');
        Route::post('store-billing', 'BillingController@storeBilling')->name('store-billing');
        Route::get('get-month', 'BillingController@getMonth')->name('get-month');
        Route::get('service-report-list', 'BillingController@serviceReportList')->name('service-report-list');
        Route::get('service-report-list-edit', 'BillingController@serviceReportListEdit')->name('service-report-list-edit');
        Route::get('special-billing-list', 'BillingController@specialBillingList')->name('special-billing-list');
        Route::get('get-retainers', 'BillingController@getRetainers')->name('get-retainers');
        Route::get('get-retainers-edit', 'BillingController@getRetainersEdit')->name('get-retainers-edit');
        Route::get('get-special-billing', 'BillingController@getSpecialBilling')->name('get-special-billing');
        Route::get('get-billing-list', 'BillingController@getList')->name('get-billing-list');
        Route::get('billing-void/{id}', 'BillingController@void')->name('billing-void');

        Route::get('billings/pdf/regenerate', '\Reports\BillingController@index')->name('billing.print');
        Route::get('billings/{id}/pdf', '\Reports\BillingController@previewBillingPdf')->name('billing.pdf-preview');

        // get the latest billing for the client
        Route::get('clients/unpaid-bills', 'ClientController@unpaidBills')->name('unpaid-bills');
    });

// ROUTE GROUPS FOR PERMISSIONS ----------------------------------------------------------------------------------------


// ROUTE GROUPS FOR ROLES ----------------------------------------------------------------------------------------------

    Route::group(['middleware' => ['role:admin|office-clerk']], function () {
        // Paralegal Assignment Sheet
        Route::get('paralegal-assignment-sheet', 'ParalegalAssignmentSheetController@index')->name('pas.index');
        Route::get('paralegal-assignment-sheet/print', '\Reports\ParalegalAssignmentSheetReportController@index')->name('pas.print');

    });

    Route::group(['middleware' => ['role:admin|counsel']], function () {
        Route::post('chart-of-accounts/{id}/update', 'AccountController@update')->name('chart-of-accounts.patch');
        Route::resource('chart-of-accounts', 'AccountController')->only([
            'index', 'store', 'destroy', /*'create', 'edit', 'update',*/
        ]);

        Route::get('chart-of-accounts/list', 'AccountController@chartOfAccountList')->name('chart.of.accounts.list');
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::get('role', 'RoleController@index')->name('role');
        Route::get('role-list', 'RoleController@getList')->name('role-list');
        Route::get('role-show/{id}', 'RoleController@show')->name('role-show');
        Route::get('role-create', 'RoleController@create')->name('role-create');
        Route::post('role-store', 'RoleController@store')->name('role-store');
        Route::post('role-update/{id}', 'RoleController@update')->name('role-update');

        Route::resource('user', 'UserController');
        Route::get('user-list', 'UserController@getList')->name('user-list');

        // get clients cases query-params id={userId}
        Route::get('transactions/client/cases', 'TransactionController@clientCases')->name('transactions.client-cases');
        Route::get('transactions/client/latest-service-report', 'TransactionController@clientServiceReport')->name('transactions.latest-service-reports');

        Route::resource('fee', 'FeeController');
        Route::get('fee-list', 'FeeController@feeGetList')->name('fee-list');
        Route::get('fee-desc/{id}', 'FeeController@feeDesc')->name('fee-desc');
        Route::get('fee-find', 'FeeController@feeFind')->name('fee-find');
        Route::get('fee-detail-find', 'FeeController@feeDetailFind')->name('fee-detail-find');
        Route::get('fee-category', 'FeeController@feeCategory')->name('fee-category');
        Route::post('fee-update', 'FeeController@feeUpdate')->name('fee-update');
        Route::post('fee-desc-store', 'FeeController@feeDescStore')->name('fee-desc-store');
        Route::post('fee-desc-update', 'FeeController@feeDescUpdate')->name('fee-desc-update');
        Route::get('fee-desc-delete', 'FeeController@feeDescDelete')->name('fee-desc-delete');        

        /* Settings Controller */
        Route::get('note', 'SettingsController@noteIndex')->name('note');
        Route::get('note-list', 'SettingsController@noteList')->name('note-list');
        Route::get('note-create', 'SettingsController@noteCreate')->name('note-create');
        Route::post('note-store', 'SettingsController@noteStore')->name('note-store');
        Route::get('note-delete/{id}', 'SettingsController@noteDelete')->name('note-delete');
        Route::get('note-show/{id}', 'SettingsController@noteShow')->name('note-show');

    });

// ROUTE GROUPS FOR ROLES ----------------------------------------------------------------------------------------------

});
