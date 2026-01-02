<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class stock_model extends Model{

    
    protected $table = 'store_top_level';
    protected $table_2 = 'store_main_category';
    protected $table_3 = 'store_item_table';

    public function stock_top_level_model() {
        $alltoplevel = DB::table($this->table)->get();
        return $alltoplevel;
    }

    public function insert_new_master_input_stock_model($name) {
        $max_id = DB::table($this->table)->max('level_id');
        if ($max_id == Null){
            $new_level_id = '01';
        } else {
            $new_level_id = str_pad($max_id + 1, 2, '0', STR_PAD_LEFT);
        }

        DB::table($this->table)->insert([
                    'level_id'=> $new_level_id,
                    'name'    => $name
                ]);
        return true;
    }

    public function delete_stock_master_input_model($masterid) {
        DB::table($this->table)->where('level_id' , $masterid)->delete();
        return true;
    }

    public function update_stock_master_input_name_model($masterid, $newname) {
        DB::table($this->table)->where('level_id', $masterid)->update(['name' => $newname]);
        return true;
    }

    public function join_stock_master_and_items_model() {
        $data = DB::table($this->table_2)
            ->leftJoin('store_top_level', 'store_main_category.level_id', '=', 'store_top_level.level_id')
            ->select('store_main_category.*', 'store_top_level.name as master_name')
            ->orderBy('store_main_category.cat_id', 'asc')
            ->get();
        return $data;
    }

    public function insert_new_stock_item_model($name, $masterid) {
        $max_id = DB::table($this->table_2)->max('cat_id');
        if ($max_id == Null){
            $new_level_id = '001';
        } else {
            $new_level_id = str_pad($max_id + 1, 3, '0', STR_PAD_LEFT);
        }
        if (!empty($name) && !empty($masterid)){
            $savedata = DB::table($this->table_2)->insert([
                        'cat_id'=> $new_level_id,
                        'level_id' => $masterid,
                        'name'    => $name]);
            return true;
        } else {
            return 'empty';
        }
        }
    
    public function delete_stock_item_model($itemid) {
        DB::table($this->table_2)->where('cat_id' , $itemid)->delete();
        return true;
    }

    public function update_stock_item_name_model($itemid, $itemnewname) {
        DB::table($this->table_2)->where('cat_id', $itemid)->update(['name' => $itemnewname]);
        return true; 
    }

    public function stock_type_of_items_model() {
        $data = DB::table($this->table_3)
               ->leftJoin('store_main_category', 'store_item_table.cat_id','=', 'store_main_category.cat_id')
               ->leftJoin('store_top_level', 'store_main_category.level_id','=', 'store_top_level.level_id')
               ->select('store_item_table.item_id as item_id','store_item_table.name as item_name','store_item_table.sale_price as item_price', 'store_item_table.unit as unit', 'store_main_category.name as category_name', 'store_top_level.name as master_name')
               ->orderBy('store_item_table.item_id','asc')
               ->get();
        return $data;
    }
    public function master_and_ctegory_for_stock_item_model(){
        $data = DB::table($this->table_2)
                        ->leftJoin('store_top_level', 'store_main_category.level_id', '=', 'store_top_level.level_id')
                        ->select('store_main_category.cat_id as cat_id', 'store_main_category.name as cat_name','store_top_level.name as master_name')
                        ->orderBy('store_main_category.cat_id', 'asc')
                        ->get();
        return $data;
    }

    public function save_new_item_of_stock_modle($name, $saleprice, $unit, $catid) {
        $max_id = DB::table($this->table_3)->max('item_id');
        if ($max_id == Null){
            $new_level_id = '0001';
        } else {
                $new_level_id = str_pad($max_id + 1, 4, '0', STR_PAD_LEFT);
            }

        $savedata = DB::table($this->table_3)->insert([
                        'item_id'=> $new_level_id,
                        'cat_id' => $catid,
                        'name'    => $name,
                        'sale_price'    => $saleprice,
                        'unit' => $unit
                    ]);
        return true;
    }

    public function update_type_of_stock_item_name_model($itemid, $newname) {
        $updated = DB::table($this->table_3)
            ->where('item_id', $itemid)
            ->update(['name' => $newname]);
        return true;
    }

    public function update_type_of_stock_item_price_model($itemid, $saleprice) {
        $updated = DB::table($this->table_3)
            ->where('item_id', $itemid)
            ->update(['sale_price' => $saleprice]);
        return true;
    }

    public function update_type_of_stock_item_unit_model($itemid, $unit) {
        $updated = DB::table($this->table_3)
            ->where('item_id', $itemid)
            ->update(['unit' => $unit]);
        return true;
    }

    public function delete_main_stock_item_model($itemid) {
        DB::table($this->table_3)->where('item_id' , $itemid)->delete();
        return true;
    }

    public function main_stock_complete_model() {
        $data = DB::table($this->table)
                ->leftJoin('store_main_category', 'store_top_level.level_id', '=', 'store_main_category.level_id')
                ->leftJoin('store_item_table','store_main_category.cat_id','=','store_item_table.cat_id')
                ->select('store_top_level.level_id as master_id','store_top_level.name as master_name','store_main_category.cat_id as cat_id','store_main_category.name as cat_name','store_item_table.item_id as item_id','store_item_table.name as item_name','store_item_table.sale_price as item_price', 'store_item_table.unit as unit')
                ->orderby('store_top_level.level_id', 'asc')
                ->get();
        return $data;
    }

    public function stock_item_table_for_new_purchase_order_model() {
        $topLevelData = DB::table($this->table_3)
                        ->leftJoin('store_main_category', 'store_item_table.cat_id', '=', 'store_main_category.cat_id')
                        ->select('store_item_table.item_id as item_id', 'store_item_table.name as item_name','store_item_table.sale_price as sale_price','store_main_category.name as master_name', 'store_item_table.unit as unit')
                        ->orderBy('store_item_table.item_id', 'asc')
                        ->get();
        
        return $topLevelData;
    }


}