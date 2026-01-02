<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\user_login as user_model;
use App\Models\order_model as order;
use App\Models\employee_model as employee;
use App\Models\supplier_model as supplier;
use App\Models\purchase_order_model as purchase_order;
class check_login extends Controller {

    public function check_user(Request $request) {
        $username = $request->input('username');
        $password = $request->input('password');
        $user = (new user_model())->user_check($username, $password);
        if ($user) {
            $userstatus = $user->activation;
            if ($userstatus == true) {
                $request->session()->put('username', $user->user);
                $request->session()->put('userid', $user->id);
                $request->session()->put('usertype', $user->name);

                return redirect()->route('dashboard');
            }
            elseif ($userstatus == false) {
                return "User not active, Contact admin";
            }
            }   
        else {
            return "User not exists, Contact admin";
        }
    }

    public function dashboard_control(Request $request) {
        $len_order = (new order())->len_unclear_unpaid_order_model(false, false);
        $len_clear_but_unpaid = (new order())->len_unclear_unpaid_order_model(true, false);
        $active_emp = (new employee())->employee_len_model(true);
        $len_supplier = (new supplier())->len_supplier_model();
        $pending_purchase_order = (new purchase_order())->len_unclear_purchase_order_model(false);
        $employees_balance = ((new employee())->receiveables_from_employees_model())->grand_total;
        $supplier_balance = ((new supplier())->payable_to_supplier_model())->grand_total;
        return view('layouts.dashboard', ['count_unpaid_unclear' => $len_order, 'count_clear_unpaid' => $len_clear_but_unpaid, 'active_emp' => $active_emp,
                                                    'total_supplier' => $len_supplier, 'pending_purchase_order' => $pending_purchase_order, 'employees_receivable' => $employees_balance,
                                                    'supplier_balance' =>$supplier_balance]);
    }


    public function logout_user(Request $request) {
        $request->session()->forget(['username', 'userid', 'usertype']);
    
        return redirect()->route('login');
    }
}