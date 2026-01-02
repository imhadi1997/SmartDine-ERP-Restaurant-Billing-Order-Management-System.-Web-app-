<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\menu_table as menu;
use App\Models\order_model as order;

class order_controller extends Controller {

    public function new_order_controller(Request $request) {
        $topLevelData = (new menu())->item_table_for_new_order_model();
        return view("order_new", ['topLevels'=>$topLevelData]);
    }

    public function save_new_order_controller(Request $request) {
        $items = $request->input('items');
        $user = $request->session()->get('userid');
        $saveintodb = (new order())->save_new_order_model($items, $user);
   
        return response()->json(['success' => true, 'message' => 'Order#: ' . $saveintodb . ' saved succefully!']);
    }

    public function order_status_controller(Request $request)   {
        $pending_order_numbers = (new order())->unpaid_unclear_order_details_model(false, false);
        $topLevelData = (new menu())->item_table_for_new_order_model();
        return view('order_status', ['orders'=> $pending_order_numbers, 'topLevelData'=> $topLevelData]);
    }

    public function get_selected_order_controller($order_id) {
        $details = (new order())->selected_order_details_model($order_id);
        return response()->json($details);
    }

    public function update_item_qty_in_unclear_order_controller(Request $request, $itemid){
        $order_id = $request->input('order_id');
        $qty = $request->input('qty');
        $updation = (new order())->update_item_qty_unclear_order_model($itemid, $order_id, $qty);
        if ($updation === true) {
            return response()->json(['success' => true, 'message' => 'item qty of order updated']);
        }
    }

    public function delete_item_from_unclear_order_controller(Request $request, $itemid){
        $order_id = $request->input('order_id');
        $orderdeletion = (new order())->delete_item_from_unclear_order_model($itemid, $order_id);
        if ($orderdeletion === true) {
            $mm = 'item removed from order# ' . $order_id;
            return response()->json(['success' => true, 'message' => $mm]);
        } elseif ($orderdeletion === false) {
            return response()->json(['success' => false, 'message' => 'last item unable to remove']);
        }
    }

    public function add_new_item_in_existing_order_controller(Request $request) {
        $orderid = $request->input('order_id');
        $itemid = $request->input('item_id');
        $sale_price = $request->input('price');
        $qty = $request->input('qty');

        $saveindb = (new order())->add_new_item_in_existing_order_model($itemid, $orderid, $sale_price, $qty);
        if ($saveindb === true) {
            return response()->json(['success'=> true, 'message'=>'Item added successfully!']);
        } elseif ($saveindb === 'existis') {
            return response()->json(['success'=> false, 'message'=>'Item already exists in this order!']);
        }
    }

    public function order_clear_status_controller(Request $request) {
        $order_id = $request->input('itemid');
        $userid = $request->input('userid');

        $updatestatus = (new order())-> order_clear_status_model($order_id, $userid);
        $mm = $order_id . '  Order cleared successfully';
        return response()->json(['success' => true, 'message' => $mm]);
    }

    public function order_bill_controller(Request $request) {
        $orders = (new order())->unpaid_unclear_order_details_model( true, false);
        return view("order_bill", ['orders' => $orders]);

    }

    public function save_order_bill_controller(Request $request) {
        $order_id = $request->input('itemid');
        $paid = $request->input('paidamount');
        $discount = $request->input('discount');
        $userid = $request->input('userid');
        $payableamount = $request->input('saleprice1');

        if (empty($order_id) || empty($paid) || empty($discount)) {
            return response()->json([
                'success' => false,
                'message' => 'Required fields are empty'
            ]);
        }

        if ($paid < $payableamount) {
            $less = $payableamount - $paid;
            return response()->json([
            'success' => false,
            'message' => 'Paid amount is less than payable add atleast more: ' . number_format($less, 2)
        ]);
        }
        $savebill = (new order())->save_order_bill_model($order_id, $paid, $discount, $userid);
        $remain = $paid - $payableamount;
        return response()->json([
            'success' => true,
            'message' => $order_id . " Bill saved succefully!",
            'remain' => $remain
        ]);
    }

    public function order_report_controller(Request $request) {
        $orders = (new order())->all_orders_model();
        return view('order_report', ['orders' => $orders] );
    }

    public function get_selected_order_report_controller($orderid) {
        $details = (new order())->order_report_model($orderid);
        return response()->json($details);
    }

}