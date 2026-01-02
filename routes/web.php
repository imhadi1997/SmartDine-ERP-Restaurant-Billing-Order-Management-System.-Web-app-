<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\check_login;
use App\Http\Controllers\menu_top_level;
use App\Http\Controllers\order_controller;
use App\Http\Controllers\stock_controller;
use App\Http\Controllers\purchase_order_controller;
use App\Http\Controllers\supplier_controller;
use App\Http\Controllers\inventory_controller;
use App\Http\Controllers\employee_controller;

Route::get('/', function () {
    return view('home');
})->name('login');

//  Check User name & Password
Route::post('/login', [check_login::class, 'check_user'])->name('login_checker');

//  DasgBoard
Route::get('/dashboard', [check_login::class, 'dashboard_control'])->name('dashboard');
Route::post('/log-out', [check_login::class, 'logout_user'])->name('logout');

//  Menu Top Level
Route::get('/menu-master', [menu_top_level::class, 'top_level'])->name('menu_top_level');
Route::post('/save-new-master-input-menu', [menu_top_level::class, 'save_new_master_input'])->name('save_master_input_menu');
Route::delete('/delete-master-input-menu', [menu_top_level::class, 'delete_master_input'])->name('delete_menu_master_input');
Route::post('/update-master-input-menu', [menu_top_level::class, 'update_master_input_name'])->name('update_menu_master_input');

// Menu item
Route::get('/menu-items', [menu_top_level::class, 'menu_item_control'])->name('menu_items');
Route::post('/save-new-menu-item', [menu_top_level::class, 'save_new_menu_item'])->name('save_new_menu_item');
Route::delete('/delete-menu-item', [menu_top_level::class, 'delete_menu_item'])->name('delete_menu_item');
Route::post('/update-menu-item-name', [menu_top_level::class, 'update_menu_item_name'])->name('update_menu_item_name');

//  Menu Type of items
Route::get('/menu-type-of-items', [menu_top_level::class, 'menu_type_of_items_controller'])->name('menu_type_of_items');
Route::post('/save-new-type-of-item-menu', [menu_top_level::class, 'save_new_menu_item_controller'])->name('save_menu_item');
Route::post('/update-type-of-menu-name-price', [menu_top_level::class, 'update_menu_item_controller'])->name('update_menu_type_of_item_name_price');
Route::delete('/delete-main-menu-item', [menu_top_level::class, 'delete_main_menu_controller'])->name('delete_main_menu_item');

// Main Menu
Route::get('/main-menu', [menu_top_level::class, 'main_menu_full_controller'])->name('main_menu_complete');

// Order
Route::get('/new-order', [order_controller::class, 'new_order_controller'])->name('new_order');
Route::post('/save-new-order', [order_controller::class, 'save_new_order_controller'])->name('save_new_order');

// Order Status
Route::get('/order-updation-and-status', [order_controller::class, 'order_status_controller'])->name('order_update_status');

// Selected Order Details
Route::post('/post_order_details/{order_id}', [order_controller::class, 'get_selected_order_controller']);
Route::post('/update_order_qty/{item_id}', [order_controller::class, 'update_item_qty_in_unclear_order_controller']);
Route::post('/delete_order_item/{item_id}', [order_controller::class, 'delete_item_from_unclear_order_controller']);
Route::post('/add_item_in_existing_order', [order_controller::class, 'add_new_item_in_existing_order_controller'])->name('add_item_exiting_order');
Route::post('/clear_order', [order_controller::class, 'order_clear_status_controller'])->name('clear_order_status');

// Order Bill
Route::get('/order-bill', [order_controller::class, 'order_bill_controller'])->name('order_bill');
Route::post('/save-order-bill', [order_controller::class, 'save_order_bill_controller'])->name('save_order_bill');

// Order Report
Route::get('/order-report', [order_controller::class, 'order_report_controller'])->name('order_report');
Route::post('/post-order-report/{order_id}', [order_controller::class, 'get_selected_order_report_controller']);

// Stock Master input
Route::get('/stock-master', [stock_controller::class, 'stock_master_controller'])->name('stock_master_input');
Route::post('/save-new-master-input-stock', [stock_controller::class, 'save_new_master_input_stock_controller'])->name('save_master_input_stock');
Route::delete('/delete-master-input-stock', [stock_controller::class, 'delete_master_input_stock_controller'])->name('delete_stock_master_input');
Route::post('/update-master-input-stock', [stock_controller::class, 'update_master_input_stock_name_controller'])->name('update_stock_master_input');

// Stock item
Route::get('/stock-items', [stock_controller::class, 'stock_item_controller'])->name('stock_items');
Route::post('/save-new-stock-item', [stock_controller::class, 'save_new_stock_item_controller'])->name('save_new_stock_item');
Route::delete('/delete-stock-item', [stock_controller::class, 'delete_stock_item_controller'])->name('delete_stock_item');
Route::post('/update-stock-item-name', [stock_controller::class, 'update_stock_item_name_controller'])->name('update_stock_item_name');

// Stock Type of items
Route::get('/stock-type-of-items', [stock_controller::class, 'stock_type_of_items_controller'])->name('stock_type_of_items');
Route::post('/save-new-type-of-item-stock', [stock_controller::class, 'save_new_stock_type_of_item_controller'])->name('save_stock_item');
Route::post('/update-type-of-stock-name-price-unit', [stock_controller::class, 'update_stock_item_controller'])->name('update_stock_type_of_item_name_price');
Route::delete('/delete-main-stock-item', [stock_controller::class, 'delete_main_stock_controller'])->name('delete_main_stock_item');

// Main Stock 
Route::get('/main-stock', [stock_controller::class, 'main_stock_full_controller'])->name('main_stock_complete');

// Vendor Details
Route::get('/supplier-details', [supplier_controller::class, 'all_supplier_controller'])->name('supplier');
Route::post('/save-new-supplier', [supplier_controller::class, 'save_new_supplier_controller'])->name('save_supplier');
Route::delete('/delete-supplier', [supplier_controller::class, 'delete_supplier_controller'])->name('delete_supplier');
Route::post('/update-supplier-information', [supplier_controller::class, 'update_supplier_controller'])->name('update_supplier');
Route::get('/supplier-ledger', [supplier_controller::class, 'supplier_ledger_controller'])->name('supplier_ledger');
Route::post('/supplier-ledger-transactions', [supplier_controller::class, 'supplier_ledger_details_controller'])->name('supplier_ledger_details');
Route::post('/save-supplier-ledger-transaction', [supplier_controller::class, 'save_supplier_ledger_transaction_controller'])->name('save_supplier_ledger_transaction');

// Purchase Order
Route::get('/new-purchase-order', [purchase_order_controller::class, 'new_purchase_order_controller'])->name('new_purchase_order');
Route::post('/save-new-purchase-order', [purchase_order_controller::class, 'save_new_purchase_order_controller'])->name('save_new_purchase_order');

// Purchase Order Status
Route::get('/purchase-order-updation-and-status', [purchase_order_controller::class, 'purchase_order_status_controller'])->name('purchase_order_update_status');

// Selected Purchase order details
Route::post('/post_purchase_order_details/{order_id}', [purchase_order_controller::class, 'get_selected_purchase_order_controller']);
Route::post('/update_purchase_order_qty/{item_id}', [purchase_order_controller::class, 'update_item_qty_in_unclear_purchase_order_controller']);
Route::post('/add-item-in-existing-purchase-order', [purchase_order_controller::class, 'add_new_item_in_existing_purchase_order_controller'])->name('add_item_exiting_purchase_order');
Route::post('/clear-purchase-order', [purchase_order_controller::class, 'purchase_order_clear_status_controller'])->name('clear_purchase_order_status');

// Purchase Order Bill
Route::get('/purchase-order-bill', [purchase_order_controller::class, 'purchase_order_bill_controller'])->name('purchase_order_bill');

// Save Purchase Order Bill
Route::post('/save-purchase-order-bill', [purchase_order_controller::class, 'purchase_order_save_bill_controller'])->name('save_new_purchase_bill');

// Purchase Order Report
Route::get('/purchase-order-report', [purchase_order_controller::class, 'purchase_order_report_controller'])->name('purchase_order_report');
Route::post('/post-purchase-order-report/{order_id}', [purchase_order_controller::class, 'get_selected_purchase_order_report_controller']);

// inventory
Route::get('/inventory', [inventory_controller::class, 'show_inventory_controller'])->name('inventory');

// Emplyee
Route::get('/employee-details', [employee_controller::class, 'all_employee_controller'])->name('employee');
Route::post('/save-new-employee', [employee_controller::class, 'save_new_employee_controller'])->name('save_employee');
Route::delete('/delete-employee', [employee_controller::class, 'delete_employee_controller'])->name('delete_employee');
Route::post('/update-employee-information', [employee_controller::class, 'update_employee_controller'])->name('update_employee');
Route::post('/update-employee-status/{empid}', [employee_controller::class, 'update_employee_status_controller'])->name('emp_status');
Route::get('/employee-ledger', [employee_controller::class, 'employee_ledger_controller'])->name('employee_ledger');
Route::post('/employee-ledger-transactions', [employee_controller::class, 'employee_ledger_details_controller'])->name('employee_ledger_details');
Route::post('/save-employee-ledger-transaction', [employee_controller::class, 'save_employee_ledger_transaction_controller'])->name('save_employee_ledger_transaction');

// Stock Out
Route::get('/inventory-out', [inventory_controller::class, 'inventory_out_controller'])->name('inventory_out');
Route::post('/save-inventory-out', [inventory_controller::class, 'inventory_out_record_controller'])->name('save_inventory_out');

// Stock Report 
Route::get('/inventory-report', [inventory_controller::class, 'inventory_report_controller'])->name('inventory_report');
Route::post('/stock-item-ledger', [inventory_controller::class, 'stock_item_ledger_controller'])->name('stock_item_ledger_details');
