<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class order_model extends Model{

    protected $table = 'new_order';
    protected $table_2 = 'order_details';
    protected $table_3 = 'order_bill';

    public function save_new_order_model($orderitems, $userid) {
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');
        $currentYear = Carbon::now()->format('Y');
        $likePattern = 'mb-' . $currentYear . '-%';
        $lastOrder = DB::table($this->table)
                    ->where('order_id', 'like', $likePattern)
                    ->orderByRaw('LENGTH(order_id) DESC')
                    ->orderBy('order_id', 'desc')
                    ->first();
        
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->order_id);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newOrderId = 'mb-' . $currentYear . '-' . $newNumber;

        $saveorder = DB::table($this->table)->insert([
                'order_id' => $newOrderId,
                'add_by' => $userid,
                'add_date' => $todayDate,
                'add_time' => $todayTime,
                'status' => false,
                'clear_by' => null,
                'clear_date'  => null,
                'clear_time'  => null,
                'discount'  => false
            ]);
        foreach ($orderitems as $item) {
            $saveorderdetails = DB::table($this->table_2)->insert([
                'order_id' => $newOrderId,
                'item_id' => $item['item_id'],
                'price' => $item['price'],
                'qty' => $item['qty'],
                'clear_date' => null
            ]);
    }
    return $newOrderId;
    }

    public function unpaid_unclear_order_details_model($status_check , $payment_check) {
        $orderdetailsdb =  DB::table($this->table)
            ->select(
                'new_order.order_id as order_id', 'new_order.add_by as add_id', 'new_order.add_date as add_date', 'new_order.add_time as add_time', 'new_order.clear_by as clear_id', 'new_order.clear_date as clear_date', 'new_order.clear_time as clear_time',
                'users1.user as user',
                'users2.user as clear_user'
            )
            ->leftJoin('users as users1', 'new_order.add_by', '=', 'users1.id')
            ->leftJoin('users as users2', 'new_order.clear_by', '=', 'users2.id')
            ->where('new_order.status', '=', $status_check) 
            ->where('new_order.discount', '=', $payment_check) 
            ->orderByRaw("CAST(SUBSTRING_INDEX(new_order.order_id, '-', 1) AS UNSIGNED) ASC")
            ->get();
        
        return $orderdetailsdb;
    }

    public function selected_order_details_model($orderid) {
        $details = DB::table($this->table_2)
            ->select(
                'order_details.item_id', 'order_details.price as price', 'order_details.qty as qty',
                'item_table.cat_id',
                'item_table.name as item_name',
                'main_category.name as category_name'
            )
            ->leftJoin('item_table', 'order_details.item_id', '=', 'item_table.item_id')
            ->leftJoin('main_category', 'item_table.cat_id', '=', 'main_category.cat_id')
            ->where('order_details.order_id', $orderid)
            ->orderBy('order_details.id', 'asc')
            ->get();
        return $details;
    }

    public function update_item_qty_unclear_order_model($itemid, $orderid, $qty){
        $updated = DB::table($this->table_2)
            ->where('order_id', $orderid)
            ->where('item_id', $itemid)
            ->update(['qty' => $qty]);
        return true;
    }

    public function delete_item_from_unclear_order_model($itemid, $orderid){
        $check_last_item = DB::table($this->table_2)->where('order_id', $orderid)->count();
        if($check_last_item <= 1){
            return false;
        } else {
            $deleted = DB::table($this->table_2)
                ->where('order_id', $orderid)
                ->where('item_id', $itemid)
                ->delete();
            return true;
        }
    }

    public function add_new_item_in_existing_order_model($itemid, $orderid, $sale_price, $qty) {
        $exists = DB::table($this->table_2)
                    ->where('order_id', $orderid)
                    ->where('item_id', $itemid)
                    ->first();
        if($exists){
            return 'existis';
        }
        DB::table($this->table_2)->insert([
            'order_id' => $orderid,
            'item_id' => $itemid,
            'price' => $sale_price,
            'qty' => $qty,
            'clear_date' => null
        ]);

        return true;
    }

    public function order_clear_status_model($orderid, $userid): bool {
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');

        $updatedb = DB::table($this->table)
            ->where('order_id', $orderid)
            ->update([
                'status' => True,
                'clear_by' => $userid,
                'clear_date' => $todayDate,
                'clear_time' => $todayTime
            ]);
        
        $updatedetails = DB::table($this->table_2)
            ->where('order_id', $orderid)
            ->update([
                'clear_date' => $todayDate
            ]); 

        return true;
    }

    public function save_order_bill_model($order_id, $paid, $discount, $userid) {
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');

        DB::table($this->table_3)->insert([
            'bill_no'=> $order_id,
            'paid' => $paid,
            'discount' => $discount,
            'clear_date' => $todayDate,
            'clear_time' => $todayTime,
            'clear_by' => $userid
        ]);

        DB::table($this->table)
            ->where('order_id', $order_id)
            ->update(['discount' => true]);

        DB::table($this->table_2)
            ->where('order_id', $order_id)
            ->update(['clear_date' => $todayDate]);

        return true;

    }

    public function order_report_model($orderid) {

        $details =  DB::table($this->table)
            ->leftJoin('users as users1', 'new_order.add_by', '=', 'users1.id')
            ->leftJoin('users as users2', 'new_order.clear_by', '=', 'users2.id')

            ->leftJoin('order_details', 'new_order.order_id', '=', 'order_details.order_id')
            ->leftJoin('item_table', 'order_details.item_id', '=', 'item_table.item_id')
            ->leftJoin('main_category', 'item_table.cat_id', '=', 'main_category.cat_id')

            ->leftJoin('order_bill', 'new_order.order_id', '=', 'order_bill.bill_no')
            ->leftJoin('users as user3', 'order_bill.clear_by', '=', 'user3.id')

            ->select(
                'new_order.order_id as order_id', 'new_order.add_by as add_id', 'new_order.add_date as add_date', 'new_order.add_time as add_time', 'new_order.clear_by as clear_id', 'new_order.clear_date as clear_date', 'new_order.clear_time as clear_time',
            
                'users1.user as user',
                'users2.user as clear_user',

                'order_details.item_id', 'order_details.price as price', 'order_details.qty as qty',
                'item_table.cat_id',
                'item_table.name as item_name',
                'main_category.name as category_name',

                'order_bill.paid',
                'order_bill.discount',
                'order_bill.clear_date as bill_date',
                'order_bill.clear_time as bill_time',
                'user3.user as bill_user'
            )
            ->where('new_order.order_id', $orderid)
            ->get();
        return $details;
    }

    public function all_orders_model() {
        $details = DB::table($this->table)
            ->select('new_order.order_id as order_id')
            ->orderBy('new_order.id', 'asc')
            ->get();
        return $details;
    }

    public function len_unclear_unpaid_order_model($kitchen_status, $payment_status) {
        $len_order = DB::table($this->table)
            ->where($this->table . '.status', $kitchen_status)
            ->where($this->table . '.discount', $payment_status)
            ->count();

        return $len_order;
        
    }
}