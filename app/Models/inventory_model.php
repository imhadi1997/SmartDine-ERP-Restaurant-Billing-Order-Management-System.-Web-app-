<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class inventory_model extends Model{

    protected $table = 'inventory';
    protected $table_2 = 'inventory_batch';

    protected $table_3 = 'store_top_level';

    protected $table_4 = 'store_main_category';
    protected $table_5 = 'store_item_table';

    protected $table_6 = 'inventory_out';

    protected $table_7 = 'purchase_order_bill';

    public function insert_into_inventory_model($itemid, $qty) {
        $savebill = DB::table($this->table)->updateOrInsert(
        ['item_id' => $itemid], 
            ['qty' => DB::raw("qty + $qty")]
            );
        return true;
    }

    public function insert_into_batch_model($batchno, $itemid, $qty, $per_unit_price) {
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $saveindb = DB::table($this->table_2)->insert([
            'batch_no' => $batchno,
            'item_id' => $itemid,
            'qty' => $qty,
            'price' => $per_unit_price,
            'date' => $todayDate,
            'status' => false
        ]);
        return true;
    }

    public function total_inventory_model() {
        $checkdb = DB::table($this->table)
                    ->select($this->table.'.item_id', $this->table.'.qty',
                        $this->table_5. '.name as item_name', $this->table_5. '.unit as unit',
                        $this->table_4. '.name as cat_name',
                        $this->table_3. '.name as master_name', 
                        $this->table_2. '.batch_no as batch_no', $this->table_2.'.qty as batch_qty', $this->table_2. '.price as batch_price')
                    ->leftJoin($this->table_5, $this->table. '.item_id', '=', $this->table_5. '.item_id')
                    ->leftJoin($this->table_4, $this->table_5. '.cat_id', '=', $this->table_4. '.cat_id')
                    ->leftjoin($this->table_3, $this->table_4. '.level_id', '=', $this->table_3. '.level_id')
                    ->leftJoin($this->table_2, function ($join) {
                        $join->on($this->table . '.item_id', '=', $this->table_2 . '.item_id')
                            ->where($this->table_2 . '.status', false);
                    })
                    ->orderBy($this->table . '.item_id', 'asc')
                    ->get();
        return $checkdb;
    }

    public function avilable_inventory_model() {
         $checkdb = DB::table($this->table)
                    ->select($this->table.'.item_id', $this->table.'.qty',
                        $this->table_5. '.name as item_name', $this->table_5. '.unit as unit',
                        $this->table_4. '.name as cat_name',
                        $this->table_3. '.name as master_name',)
                    ->leftJoin($this->table_5, $this->table. '.item_id', '=', $this->table_5. '.item_id')
                    ->leftJoin($this->table_4, $this->table_5. '.cat_id', '=', $this->table_4. '.cat_id')
                    ->leftjoin($this->table_3, $this->table_4. '.level_id', '=', $this->table_3. '.level_id')
                    ->orderBy($this->table . '.item_id', 'asc')
                    ->get();
        return $checkdb;
    }

    public function save_inventory_out_model($orderitems, $user) {
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');

        foreach ($orderitems as $item) {
            $order_qty = $item['qty'];
            $item_id = $item['item_id'];

            $batches = DB::table($this->table_2)
                ->where($this->table_2. '.item_id', '=' ,$item_id)
                ->where($this->table_2. '.qty', '>', 0)
                ->where($this->table_2. '.status', '=', false)
                ->orderBy($this->table_2. '.date', 'asc')
                ->get();

                $remaining_qty = $order_qty;

                foreach ($batches as $batch) {
                    if ($remaining_qty <= 0) break;
                    
                    if ($batch->qty >= $remaining_qty) {
                        // This batch can cover the remaining order
                        $deduct_qty = $remaining_qty;
                        $new_qty = $batch->qty - $deduct_qty;
                        if ($new_qty < 0) {
                            $new_qty = 0;
                        }

                        $status = ($new_qty == 0) ? 1 : 0;

                        // Update batch quantity
                        DB::table($this->table_2)
                            ->where('batch_no', $batch->batch_no)
                            ->where('item_id', $batch->item_id)
                            ->update([
                                'qty'    => $new_qty,
                                'status' => $status
                            ]);

                        // Record in inventory_out
                        DB::table($this->table_6)->insert([
                            'batch_no' => $batch->batch_no,
                            'item_id' => $batch->item_id,
                            'qty' => $deduct_qty,
                            'price' => $batch->price,
                            'date' => $todayDate,
                            'time' => $todayTime,
                            'add_by' => $user
                        ]);
                        $remaining_qty = 0; 
                    } else {
                        $deduct_qty = $batch->qty;
                        $new_qty = $batch->qty - $deduct_qty;
                        if ($new_qty < 0) {
                            $new_qty = 0;
                        }

                        $status = ($new_qty == 0) ? 1 : 0;

                        // Update batch quantity
                        DB::table($this->table_2)
                            ->where('batch_no', $batch->batch_no)
                            ->where('item_id', $batch->item_id)
                            ->update([
                                'qty'    => $new_qty,
                                'status' => $status
                            ]);


                        DB::table($this->table_6)->insert([
                            'batch_no' => $batch->batch_no,
                            'item_id' => $batch->item_id,
                            'qty' => $deduct_qty,
                            'price' => $batch->price,
                            'date' => $todayDate,
                            'time' => $todayTime,
                            'add_by' => $user
                        ]);
                        $remaining_qty -= $deduct_qty;; 

                    }

                    $total_qty = DB::table($this->table_2)
                        ->where($this->table_2. '.item_id', "=", $item_id)
                        ->sum($this->table_2. '.qty');

                    DB::table($this->table)
                        ->where($this->table. '.item_id', "=", $item_id)
                        ->update([$this->table. '.qty' => $total_qty]);

                }
        }
        return true;
    }

    public function get_stock_item_transaction_model($item_id, $fromDate, $toDate) {
        $opening_in = DB::table($this->table_7)
            ->where($this->table_7. '.item_id', "=", $item_id)
            ->whereDate($this->table_7. '.date', '<', $fromDate)
            ->sum($this->table_7. '.qty');

        $opening_out = DB::table($this->table_6)
            ->where($this->table_6. '.item_id', "=", $item_id)
            ->whereDate($this->table_6. '.date', '<', $fromDate)
            ->sum($this->table_6. '.qty');

        $opening_balance = $opening_in - $opening_out;

        $in_transactions = DB::table($this->table_7)
            ->select(
                $this->table_7. '.order_id',
                $this->table_7. '.bill_no',
                $this->table_7. '.date',
                $this->table_7. '.qty',
                $this->table_7. '.price',
                DB::raw("'IN' as type")
            )
            ->where($this->table_7. '.item_id', $item_id)
            ->whereBetween($this->table_7. '.date', [$fromDate, $toDate])
            ->get();

        $out_transactions = DB::table($this->table_6)
            ->select(
                $this->table_6. '.date',
                $this->table_6. '.batch_no',
                $this->table_6. '.qty',
                $this->table_6. '.price',
                DB::raw("'OUT' as type")
            )
            ->where($this->table_6. '.item_id', $item_id)
            ->whereBetween($this->table_6. '.date', [$fromDate, $toDate])
            ->get();

        $ledger = $in_transactions
            ->merge($out_transactions)
            ->sortBy(function($row){
                // convert date + time into a single timestamp for correct sorting
                $time = isset($row->time) ? date('H:i', strtotime($row->time)) : '00:00';
                return $row->date . ' ' . $time;
            })
            ->values();


        $running_balance = $opening_balance;
        foreach ($ledger as $row) {
            if ($row->type === 'IN') {
                $running_balance += $row->qty;
            } else {
                $running_balance -= $row->qty;
            }
            $row->balance = $running_balance;
        }

        return [
            'opening_balance' => $opening_balance,
            'ledger' => $ledger
        ];

    }
}