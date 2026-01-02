<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\stock_model as stock;
use App\Models\purchase_order_model as purchase_order;
use App\Models\supplier_model as supplier;
use App\Models\inventory_model as inventory;
use SebastianBergmann\Environment\Console;

class purchase_order_controller extends Controller {
    public function new_purchase_order_controller(Request $request) {
        $topLevelData = (new stock())->stock_item_table_for_new_purchase_order_model();
        return view("purchase_order_new", ['topLevels'=>$topLevelData]);
    }

    public function save_new_purchase_order_controller(Request $request) {
        $items = $request->input('items');
        $user = $request->session()->get('userid');
        $saveintodb = (new purchase_order())->save_new_purchase_order_model($items, $user);
        
        return response()->json(['success' => true, 'message' => 'Purchase Order#: ' . $saveintodb . ' saved succefully!']);
    }

    public function purchase_order_status_controller(Request $request) {
        $pending_order_numbers = (new purchase_order())->unpaid_unclear_purchase_order_details_model(false, false);
        $topLevelData = (new stock())->stock_item_table_for_new_purchase_order_model();

        return view('purchase_order_status', ['orders'=> $pending_order_numbers, 'topLevelData'=> $topLevelData]);
    }

    public function get_selected_purchase_order_controller($order_id) {
        $details = (new purchase_order())->selected_purchase_order_details_model($order_id);
        return response()->json($details);
    }

    public function update_item_qty_in_unclear_purchase_order_controller(Request $request, $itemid) {
        $order_id = $request->input('order_id');
        $qty = $request->input('qty');
        $updation = (new purchase_order())->update_item_qty_unclear_purchase_order_model($itemid, $order_id, $qty);
        if ($updation === true) {
            return response()->json(['success' => true, 'message' => 'item qty in purchase order updated']);
        } elseif ($updation === "clear") {
            return response()->json(['success' => false, 'message' => 'item already cleared']);
        }
    }

    public function add_new_item_in_existing_purchase_order_controller(Request $request){
        $orderid = $request->input('order_id');
        $itemid = $request->input('item_id');
        $sale_price = $request->input('price');
        $qty = $request->input('qty');

        $saveindb = (new purchase_order())->add_new_item_in_existing_purchase_order_model($itemid, $orderid, $sale_price, $qty);
        if ($saveindb === true) {
            return response()->json(['success'=> true, 'message'=>'Item added successfully!']);
        } elseif ($saveindb === 'existis') {
            return response()->json(['success'=> false, 'message'=>'Item already exists in this order!']);
        }
    }

    public function purchase_order_bill_controller(Request $request) {
        $orders = (new purchase_order())->unpaid_unclear_purchase_order_details_model(false, false);
        $suppliers = (new supplier())->all_suppliers();
        return view("purchase_order_bill", ['orders' => $orders, 'supplier' =>$suppliers]);
    }

    public function purchase_order_save_bill_controller(Request $request) {
        $supplier_id  = $request->input('supplier_id');
        $total_amount = $request->input('total_amount');
        $qty  = $request->input('qty');
        $userid = $request->session()->get('userid');
        $pono = $request->input('pono');
        $itemid = $request->input('itemid');
        $savebilldb = (new purchase_order())->save_purchase_bill_model($pono, $supplier_id, $total_amount, $qty, $userid, $itemid);
        if ($savebilldb){
            $perunit =  round($total_amount / $qty, 2);
            $update_inventory = (new inventory())->insert_into_inventory_model($itemid, $qty);
            $update_item_table_price = (new stock())->update_type_of_stock_item_price_model($itemid, $perunit);
            $insert_in_batch = (new inventory())->insert_into_batch_model($savebilldb, $itemid, $qty, $perunit);
            $ledgercaption = 'Purchase Order#: '. $pono . '   Bill #' .  $savebilldb;
            $saveintosupplierledger = (new supplier())->save_supplier_ledger_transaction_model($supplier_id, $ledgercaption, $total_amount, 'Debit', $userid);
            return response()->json(['success' => true, 'message' => 'Purchase Order#: '. $pono . '   Bill #' .  $savebilldb . '    saved succefully!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Bill not saved']);
        }
    }

    public function purchase_order_report_controller(Request $request) {
        $orders = (new purchase_order())->all_purchase_orders_model();
        return view('purchase_order_report', ['orders' => $orders] );
    }

    public function get_selected_purchase_order_report_controller($orderid){
        $details = (new purchase_order())->purchase_order_report_model($orderid);
        return response()->json($details);
    }

    public function purchase_order_clear_status_controller(Request $request) {
        $order_id = $request->input('itemid');
        $userid = $request->input('userid');

        $updatestatus = (new purchase_order())->purchase_order_clear_status_model($order_id, $userid);
        $mm = $order_id . '  Purchase order cleared successfully';
        return response()->json(['success' => true, 'message' => $mm]);
    }

}