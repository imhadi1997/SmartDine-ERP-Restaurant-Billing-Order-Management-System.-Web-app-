<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\employee_model as employee;
use Carbon\Carbon;

class employee_controller extends Controller {

    public function all_employee_controller(Request $request){
        $allsupllier = (new employee())->all_employees_model();
        return view('employee', ['data' => $allsupllier]);
    }

    public function save_new_employee_controller(Request $request) {
        $name = ucfirst(strtolower($request->input('mastername')));
        $cell = $request->input('itemname');
        $insertitemdb = (new employee())->insert_new_employee_model($name, $cell);
        if ($insertitemdb === true) {
            return redirect()->back()->with('success', 'Employee saved successfully!');
        } elseif ($insertitemdb === false) {
            return redirect()->back()->with('Empty', 'Database connection lost!');
        } elseif ($insertitemdb === 'empty') {
            return redirect()->back()->with('Empty', 'Required fields are empty');
        }
    }

    public function delete_employee_controller(Request $request) {
        $supplier_id = $request->input('item_id');
        $deleteitem = (new employee())->delete_employee_model($supplier_id);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'employee deleted successfully!');
        }
    }

    public function update_employee_controller(Request $request) {
        $itemid = $request->input('supplier_id');
        $new_value = $request->input('new_value');
        $field = $request->input('field');

        if(!$itemid || !$field || $new_value === null){
            return response()->json(['success'=>false, 'message'=>'Invalid data']);
        }

        if ($field === 'name') {
            $new_value = ucfirst(strtolower($new_value));
            $insertdb = (new employee())->update_employee_name_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Employee name updated successfully!']);}
        } elseif ($field === 'contact') {
            $insertdb = (new employee())->update_employee_contact_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Employee contact updated successfully!']);}
        }
    }

    public function update_employee_status_controller(Request $request, $empid) {
        $action = $request->input('empstatus');
        $emp_status = '';
        if ($action === 'activate') {
            $emp_status = true;
        } elseif ($action === 'deactive') {
            $emp_status = false;
        }
        $update_in_db = (new employee())->update_employee_status_model($empid, $emp_status);
        return redirect()->back()->with('success', 'Employee status updated successfully!');
    }

    public function employee_ledger_controller(Request $request) {
        $allsupllier = (new employee())->employee_details_with_balance_model();
        return view('employee_ledger', ['allemployee' => $allsupllier]);
    }

    public function employee_ledger_details_controller(Request $request) {
        $emp_id = $request->input('itemid');
        $fromDate = $request->input('from_date');
        $fromDate = $fromDate ? Carbon::parse($fromDate)->format('Y-m-d') : null;
        // To Date
        $toDate = $request->input('to_date');
        $toDate = $toDate ? Carbon::parse($toDate)->format('Y-m-d') : null;
        $opening_balance = (new employee())->get_opening_balance_model($emp_id, $fromDate);
        $employee_transaction = (new employee())->employee_ledger_details_model($emp_id, $fromDate, $toDate);
        return response()->json([
                    'opening_balance' => $opening_balance,
                    'transactions' => $employee_transaction
                ]);
    
    }

    public function save_employee_ledger_transaction_controller(Request $request) {
        $emp_id = $request->input('itemid');
        $amount = $request->input('paidamount');
        $transtype = $request->input('transaction_type');
        $details = ucfirst(strtolower($request->input('trans_details')));
        $userid = $request->session()->get('userid');
        if (!$emp_id || !$amount || !$transtype) {return redirect()->back()->with('error', 'Required fields are empty');}
        $saveindb = (new employee())->save_employee_ledger_transaction_model($emp_id, $details, $amount, $transtype, $userid);
        if ($saveindb === true) {return redirect()->back()->with('success', 'Transaction saved successfully!');}
        

    }




}
