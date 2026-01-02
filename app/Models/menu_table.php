<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class menu_table extends Model{

    protected $table = 'top_level';
    protected $table_2 = 'main_category';
    protected $table_3 = 'item_table';

    public function top_level() {
        $alltoplevel = DB::table($this->table)->get();
        return $alltoplevel;
    }

    public function insert_new_master_input($name) {
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

    public function delete_menu_master_input($masterid) {
        DB::table($this->table)->where('level_id' , $masterid)->delete();
        return true;
    }

    public function update_menu_master_input_name($masterid, $newname) {
        DB::table($this->table)->where('level_id', $masterid)->update(['name' => $newname]);
        return true;
    }

    public function join_menu_master_and_items() {
        $data = DB::table($this->table_2)
            ->leftJoin('top_level', 'main_category.level_id', '=', 'top_level.level_id')
            ->select('main_category.*', 'top_level.name as master_name')
            ->orderBy('main_category.cat_id', 'asc')
            ->get();
        return $data;
    }

    public function insert_new_menu_item($name, $masterid) {
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
    
    public function delete_menu_item($itemid) {
        DB::table($this->table_2)->where('cat_id' , $itemid)->delete();
        return true;
    }

    public function update_menu_item_name_model($itemid, $itemnewname) {
        DB::table($this->table_2)->where('cat_id', $itemid)->update(['name' => $itemnewname]);
        return true; 
    }

    public function menu_type_of_items_model() {
        $data = DB::table($this->table_3)
               ->leftJoin('main_category', 'item_table.cat_id','=', 'main_category.cat_id')
               ->leftJoin('top_level', 'main_category.level_id','=', 'top_level.level_id')
               ->select('item_table.item_id as item_id','item_table.name as item_name','item_table.sale_price as item_price', 'main_category.name as category_name', 'top_level.name as master_name')
               ->orderBy('item_table.item_id','asc')
               ->get();
        return $data;
    }

    public function master_and_ctegory_for_menu_item_model(){
        $data = DB::table($this->table_2)
                        ->leftJoin('top_level', 'main_category.level_id', '=', 'top_level.level_id')
                        ->select('main_category.cat_id as cat_id', 'main_category.name as cat_name','top_level.name as master_name')
                        ->orderBy('main_category.cat_id', 'asc')
                        ->get();
        return $data;
    }

    public function save_new_item_of_menu_modle($name, $saleprice, $catid) {
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
                        'sale_price'    => $saleprice
                    ]);
        return true;
    }

    public function update_type_of_menu_item_name_model($itemid, $newname) {
        $updated = DB::table($this->table_3)
            ->where('item_id', $itemid)
            ->update(['name' => $newname]);
        return true;
    }

    public function update_type_of_menu_item_price_model($itemid, $saleprice) {
        $updated = DB::table($this->table_3)
            ->where('item_id', $itemid)
            ->update(['sale_price' => $saleprice]);
        return true;
    }

    public function delete_main_menu_item_model($itemid) {
        DB::table($this->table_3)->where('item_id' , $itemid)->delete();
        return true;
    }

    public function main_menu_complete_model() {
        $data = DB::table($this->table)
                ->leftJoin('main_category', 'top_level.level_id', '=', 'main_category.level_id')
                ->leftJoin('item_table','main_category.cat_id','=','item_table.cat_id')
                ->select('top_level.level_id as master_id','top_level.name as master_name','main_category.cat_id as cat_id','main_category.name as cat_name','item_table.item_id as item_id','item_table.name as item_name','item_table.sale_price as item_price')
                ->orderby('top_level.level_id', 'asc')
                ->get();
        return $data;
    }

    public function item_table_for_new_order_model() {
        $topLevelData = DB::table($this->table_3)
                        ->leftJoin('main_category', 'item_table.cat_id', '=', 'main_category.cat_id')
                        ->select('item_table.item_id as item_id', 'item_table.name as item_name','item_table.sale_price as sale_price','main_category.name as master_name')
                        ->orderBy('item_table.item_id', 'asc')
                        ->get();
        
        return $topLevelData;
    }
}