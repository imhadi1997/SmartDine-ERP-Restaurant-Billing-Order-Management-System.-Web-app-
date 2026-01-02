<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class purchase_order_model extends Model{

    protected $table = 'new_purchase_order';
    protected $table_2 = 'purchase_order_details';
    protected $table_3 = 'purchase_order_bill';

    public function save_new_purchase_order_model($orderitems, $userid){
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');
        $currentYear = Carbon::now()->format('Y');
        $likePattern = $currentYear . '-%';
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

        $newOrderId = $currentYear . '-' . $newNumber;
        
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

    public function unpaid_unclear_purchase_order_details_model($status_check , $payment_check) {
        $orderdetailsdb =  DB::table($this->table)
            ->select(
                'new_purchase_order.order_id as order_id', 'new_purchase_order.add_by as add_id', 'new_purchase_order.add_date as add_date', 'new_purchase_order.add_time as add_time', 'new_purchase_order.clear_by as clear_id', 'new_purchase_order.clear_date as clear_date', 'new_purchase_order.clear_time as clear_time',
                'users1.user as user',
                'users2.user as clear_user'
            )
            ->leftJoin('users as users1', 'new_purchase_order.add_by', '=', 'users1.id')
            ->leftJoin('users as users2', 'new_purchase_order.clear_by', '=', 'users2.id')
            ->where('new_purchase_order.status', '=', $status_check) 
            ->where('new_purchase_order.discount', '=', $payment_check) 
            ->orderByRaw("CAST(SUBSTRING_INDEX(new_purchase_order.order_id, '-', 1) AS UNSIGNED) ASC")
            ->get();
        
        return $orderdetailsdb;
    }

    public function selected_purchase_order_details_model($orderid){
        $details = DB::table($this->table_2)
            ->select(
                'purchase_order_details.item_id', 'purchase_order_details.price as price', 'purchase_order_details.qty as qty',
                'store_item_table.cat_id',
                'store_item_table.name as item_name',
                'store_main_category.name as category_name',
                'store_item_table.unit as unit',
                DB::raw('COALESCE(SUM(purchase_order_bill.qty), 0) as received')
            )
            ->leftJoin('store_item_table', 'purchase_order_details.item_id', '=', 'store_item_table.item_id')
            ->leftJoin('store_main_category', 'store_item_table.cat_id', '=', 'store_main_category.cat_id')
            ->leftJoin('purchase_order_bill', function ($join) use ($orderid) {
                $join->on('purchase_order_bill.item_id', '=', 'purchase_order_details.item_id')
                    ->where('purchase_order_bill.order_id', '=', $orderid);
            })
            ->where('purchase_order_details.order_id', $orderid)
            ->groupBy(
                'purchase_order_details.item_id',
                'purchase_order_details.price',
                'purchase_order_details.qty',
                'store_item_table.cat_id',
                'store_item_table.name',
                'store_main_category.name',
                'store_item_table.unit',
                'purchase_order_details.id'
            )
            ->orderBy('purchase_order_details.id', 'asc')
            ->get();
        return $details;
    }

    public function update_item_qty_unclear_purchase_order_model($itemid, $orderid, $qty){
        $check = DB::table($this->table_2)->select('clear_date')->where('order_id', $orderid)->where('item_id', $itemid)->first();
        if ($check->clear_date === null) {
            $updated = DB::table($this->table_2)
                ->where('order_id', $orderid)
                ->where('item_id', $itemid)
                ->update(['qty' => $qty]);
            return true;
        } else {
            return "clear";
        }
    }

    public function add_new_item_in_existing_purchase_order_model($itemid, $orderid, $sale_price, $qty) {
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

    public function save_purchase_bill_model($pono, $supplier_id, $total_amount, $qty, $userid, $itemid){
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');
        $likePattern = $pono . '-%';
        $lastOrder = DB::table($this->table_3)
                    ->where('bill_no', 'like', $likePattern)
                    ->orderByRaw('LENGTH(bill_no) DESC')
                    ->orderBy('bill_no', 'desc')
                    ->first();
        if ($lastOrder) {
            $parts = explode('-', $lastOrder->bill_no);
            $lastNumber = (int) end($parts);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $newOrderId = $pono . '-' . $newNumber;

        $savebill = DB::table($this->table_3)->insert([
            'bill_no' => $newOrderId,
            'order_id' => $pono,
            'item_id' => $itemid,
            'qty' => $qty,
            'price' => $total_amount,
            'supplier_id' => $supplier_id,
            'date' => $todayDate,
            'time' => $todayTime,
            'add_by' => $userid
        ]);

        return $newOrderId;
    }

    public function all_purchase_orders_model() {
        $details = DB::table($this->table)
            ->select('new_purchase_order.order_id as order_id')
            ->orderBy('new_purchase_order.id', 'asc')
            ->get();
        return $details;
    }

    public function purchase_order_report_model($orderid){
        $details =  DB::table($this->table)
        ->leftJoin('users as users1', 'new_purchase_order.add_by', '=', 'users1.id')
        ->leftJoin('users as users2', 'new_purchase_order.clear_by', '=', 'users2.id')

        ->leftJoin('purchase_order_details', 'new_purchase_order.order_id', '=', 'purchase_order_details.order_id')
        ->leftJoin('store_item_table', 'purchase_order_details.item_id', '=', 'store_item_table.item_id')
        ->leftJoin('store_main_category', 'store_item_table.cat_id', '=', 'store_main_category.cat_id')

        ->leftJoin('purchase_order_bill', function($join){
                $join->on('purchase_order_details.order_id', '=', 'purchase_order_bill.order_id')
                    ->on('purchase_order_details.item_id', '=', 'purchase_order_bill.item_id');
            })

        ->leftJoin('users as user3', 'purchase_order_bill.add_by', '=', 'user3.id')
        ->leftJoin('supplier', 'purchase_order_bill.supplier_id', '=', 'supplier.supplier_id')
        ->select(
            'new_purchase_order.order_id as order_id', 'new_purchase_order.add_by as add_id', 'new_purchase_order.add_date as add_date', 'new_purchase_order.add_time as add_time', 'new_purchase_order.clear_by as clear_id', 'new_purchase_order.clear_date as clear_date', 'new_purchase_order.clear_time as clear_time',
        
            'users1.user as user',
            'users2.user as clear_user',

            'purchase_order_details.item_id', 'purchase_order_details.price as price', 'purchase_order_details.qty as qty',
            'store_item_table.cat_id',
            'store_item_table.name as item_name',
            'store_item_table.unit as unit',
            'store_main_category.name as category_name',

            'purchase_order_bill.bill_no as bill_no',
            'purchase_order_bill.item_id as bill_item',
            'purchase_order_bill.qty as bill_qty',
            'purchase_order_bill.price as bill_price',
            'purchase_order_bill.date as bill_date',
            'purchase_order_bill.time as bill_time',
            'user3.user as bill_user',

            'supplier.name as supplier_name',
        )
        ->where('new_purchase_order.order_id', $orderid)
        ->get();
    return $details;
    }

    public function purchase_order_clear_status_model($orderid, $userid): bool {
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

        return true;
    }

    public function len_unclear_purchase_order_model($payment_status) {
        $len_order = DB::table($this->table)
            ->where($this->table . '.status', $payment_status)
            ->count();

        return $len_order;
        
    }

}
 