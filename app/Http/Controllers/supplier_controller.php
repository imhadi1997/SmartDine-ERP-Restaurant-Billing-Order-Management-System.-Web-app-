<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\supplier_model as supplier;
use Carbon\Carbon;

class supplier_controller extends Controller {

    public function all_supplier_controller(Request $request){
        $allsupllier = (new supplier())->all_suppliers();
        return view('supplier', ['data' => $allsupllier]);
    }

    public function save_new_supplier_controller(Request $request) {
        $name = ucfirst(strtolower($request->input('mastername')));
        $cell = $request->input('itemname');
        $insertitemdb = (new supplier())->insert_new_supplier_model($name, $cell);
        if ($insertitemdb === true) {
            return redirect()->back()->with('success', 'Supplier saved successfully!');
        } elseif ($insertitemdb === false) {
            return redirect()->back()->with('Empty', 'Database connection lost!');
        } elseif ($insertitemdb === 'empty') {
            return redirect()->back()->with('Empty', 'Required fields are empty');
        }
    }

    public function delete_supplier_controller(Request $request) {
        $supplier_id = $request->input('item_id');
        $deleteitem = (new supplier())->delete_supplier_model($supplier_id);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Supplier deleted successfully!');
        }
    }

    public function update_supplier_controller(Request $request) {
        $itemid = $request->input('supplier_id');
        $new_value = $request->input('new_value');
        $field = $request->input('field');

        if(!$itemid || !$field || $new_value === null){
            return response()->json(['success'=>false, 'message'=>'Invalid data']);
        }

        if ($field === 'name') {
            $new_value = ucfirst(strtolower($new_value));
            $insertdb = (new supplier())->update_supplier_name_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Supplier name updated successfully!']);}
        } elseif ($field === 'contact') {
            $insertdb = (new supplier())->update_supplier_contact_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Supplier contact updated successfully!']);}
        }
    }

    public function supplier_ledger_controller(Request $request) {
        $allsupllier = (new supplier())->supplier_details_with_balance_model();
        return view('supplier_ledger', ['allemployee' => $allsupllier]);
    }

    public function supplier_ledger_details_controller(Request $request) {
        $emp_id = $request->input('itemid');
        $fromDate = $request->input('from_date');
        $fromDate = $fromDate ? Carbon::parse($fromDate)->format('Y-m-d') : null;
        // To Date
        $toDate = $request->input('to_date');
        $toDate = $toDate ? Carbon::parse($toDate)->format('Y-m-d') : null;
        $opening_balance = (new supplier())->get_supplier_opening_balance_model($emp_id, $fromDate);
        $employee_transaction = (new supplier())->supplier_ledger_details_model($emp_id, $fromDate, $toDate);
        return response()->json([
                    'opening_balance' => $opening_balance,
                    'transactions' => $employee_transaction
                ]);
    
    }

    public function save_supplier_ledger_transaction_controller(Request $request) {
        $emp_id = $request->input('itemid');
        $amount = $request->input('paidamount');
        $transtype = $request->input('transaction_type');
        $details = ucfirst(strtolower($request->input('trans_details')));
        $userid = $request->session()->get('userid');
        if (!$emp_id || !$amount || !$transtype) {return redirect()->back()->with('error', 'Required fields are empty');}
        $saveindb = (new supplier())->save_supplier_ledger_transaction_model($emp_id, $details, $amount, $transtype, $userid);
        if ($saveindb === true) {return redirect()->back()->with('success', 'Transaction saved successfully!');}
        

    }




}
