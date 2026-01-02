<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\inventory_model as inventory;
use Carbon\Carbon;

class inventory_controller extends Controller {

    public function show_inventory_controller(Request $request) {
        $checkstock = (new inventory())->total_inventory_model();
        $grouped = collect($checkstock)
                ->groupBy('master_name')
                ->map(function ($masterGroup) {
                return $masterGroup->groupBy('item_id');        
                });

        return view('inventory', ["totalstock" => $grouped]);
    }

    public function inventory_out_controller(Request $request) {
        $invenoty_items = (new inventory())->avilable_inventory_model();
        return view('inventory_out', ['inventory_items' => $invenoty_items]);
    }

    public function inventory_out_record_controller(Request $request) {
        $items = $request->input('items');
        $user = $request->session()->get('userid');
        $saveindb = (new inventory())->save_inventory_out_model($items, $user);

        return response()->json(['success' => true, 'message' => 'Details saved & inventory updated successfully!']);
    }

    public function inventory_report_controller(Request $request) {
        $invenoty_items = (new inventory())->avilable_inventory_model();
        return view('inventory_item_ledger', ['inventory_items' => $invenoty_items]);
    }

    public function stock_item_ledger_controller(Request $request) {
        $emp_id = $request->input('itemid');
        $fromDate = $request->input('from_date');
        $fromDate = $fromDate ? Carbon::parse($fromDate)->format('Y-m-d') : null;
        // To Date
        $toDate = $request->input('to_date');
        $toDate = $toDate ? Carbon::parse($toDate)->format('Y-m-d') : null;
        $stock_item_transactions = (new inventory())->get_stock_item_transaction_model($emp_id, $fromDate, $toDate);

        return response()->json([
                    'opening_balance' => $stock_item_transactions['opening_balance'],
                    'ledger' => $stock_item_transactions['ledger']
                ]);
    }

}