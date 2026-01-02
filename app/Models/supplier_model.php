<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class supplier_model extends Model{

    protected $table = 'supplier';
    protected $table_2 = 'supplier_ledger';

    public function all_suppliers(){
        $alltoplevel = DB::table($this->table)->get();
        return $alltoplevel;
    }

    public function insert_new_supplier_model($name, $cell) {
        $max_id = DB::table($this->table)->max('supplier_id');
        if ($max_id == Null){
            $new_level_id = '0001';
        } else {
                $new_level_id = str_pad($max_id + 1, 4, '0', STR_PAD_LEFT);
            }
        
        if (!empty($name) && !empty($cell)){
            $savedata = DB::table($this->table)->insert([
                        'supplier_id'=> $new_level_id,
                        'name' => $name,
                        'cell'    => $cell]);
            return true;
        } else {
            return 'empty';
        }
    }

    public function delete_supplier_model($supplier_id) {
        DB::table($this->table)->where('supplier_id' , $supplier_id)->delete();
        return true;
    }

    public function update_supplier_name_model($itemid, $new_value) {
        $updated = DB::table($this->table)
            ->where('supplier_id', $itemid)
            ->update(['name' => $new_value]);
        return true;
    }

    public function update_supplier_contact_model($itemid, $new_value) {
        $updated = DB::table($this->table)
            ->where('supplier_id', $itemid)
            ->update(['cell' => $new_value]);
        return true;
    }

    public function supplier_details_with_balance_model(){
        $alltoplevel = DB::table($this->table)
            ->leftJoin($this->table_2, $this->table . '.supplier_id', '=', $this->table_2 . '.emp_id')
            ->select(
                $this->table . '.supplier_id',
                $this->table . '.name',
                $this->table . '.cell',
                DB::raw("
                    COALESCE(
                        SUM(CASE WHEN {$this->table_2}.type = 'Debit' THEN {$this->table_2}.amount ELSE 0 END) -
                        SUM(CASE WHEN {$this->table_2}.type = 'Credit' THEN {$this->table_2}.amount ELSE 0 END),
                    0) AS current_balance
                ")
            )
            ->groupBy(
                $this->table . '.supplier_id',
                $this->table . '.name',
                $this->table . '.cell'
            )
            ->get();

        return $alltoplevel;
    }

    public function supplier_ledger_details_model($empid, $fromdate, $enddate) {
        $query = DB::table($this->table_2)
                ->leftJoin('users', $this->table_2. '.add_by', '=', 'users.id')
                ->select(
                    $this->table_2. '.details',
                    $this->table_2. '.amount',
                    $this->table_2. '.type',
                    $this->table_2. '.date',
                    $this->table_2. '.time',
                    'users.user as add_by_user'
                )
                ->where('emp_id', $empid)
                ->orderBy($this->table_2. '.date', 'asc');

            if($fromdate) $query->whereDate($this->table_2.'.date', '>=', $fromdate);
            if($enddate) $query->whereDate($this->table_2.'.date', '<=', $enddate);
        return $query->get();
            
    }

    public function save_supplier_ledger_transaction_model($emp_id, $details, $amount, $transtype, $userid) {
        $todayDate = Carbon::now('Asia/Karachi')->format('Y-m-d');
        $todayTime = Carbon::now()->format('h:i A');
        $saveindb = DB::table($this->table_2)->insert([
            'emp_id' => $emp_id,
            'details' => $details,
            'amount' => $amount, 
            'date' => $todayDate,
            'time' => $todayTime,
            'type' => $transtype,
            'add_by' => $userid
            ]
        );
        return true;
    }

    public function get_supplier_opening_balance_model($empid, $fromdate){
        $openingBalance = $openingBalance = DB::table($this->table_2)
        ->where('emp_id', $empid)
        ->where('date', '<', $fromdate)
        ->select(
            DB::raw("COALESCE(SUM(CASE WHEN type = 'Debit' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'Credit' THEN amount ELSE 0 END), 0) as opening_balance")
        )
        ->first();

        return $openingBalance ? $openingBalance->opening_balance : 0;
    }

    public function len_supplier_model(){
        $len_order = DB::table($this->table)->count();
        return $len_order;
    }

    public function payable_to_supplier_model() {
        $alltoplevel = DB::table($this->table)
                ->leftJoin($this->table_2, $this->table . '.supplier_id', '=', $this->table_2 . '.emp_id')
                ->select(DB::raw("
                    COALESCE(
                        SUM(
                            CASE WHEN {$this->table_2}.type = 'Debit' THEN {$this->table_2}.amount
                                WHEN {$this->table_2}.type = 'Credit' THEN -{$this->table_2}.amount
                                ELSE 0
                            END
                        ), 0
                    ) AS grand_total
                "))
                ->first();

        return $alltoplevel;
    }
}