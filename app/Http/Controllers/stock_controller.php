<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\stock_model as stock;
use PhpOption\None;

class stock_controller extends Controller {
    public function stock_master_controller(Request $request) {

        $data = (new stock())->stock_top_level_model();
        return view('stock_master_input', ['data' => $data]);

    }

    public function save_new_master_input_stock_controller(Request $request) {
        $name = ucfirst(strtolower($request->input('masterinput')));
        $saveindb = (new stock())->insert_new_master_input_stock_model($name);
        if ($saveindb === true) {
            return redirect()->back()->with('success', 'Stock master input saved successfully!'); 
        } else {
            return redirect()->back()->with('Empty', 'Data not saved, Try again'); 
        }
    }

    public function delete_master_input_stock_controller(Request $request) {
        $masterid = $request->input('level_id');
        $deleteitem = (new stock())->delete_stock_master_input_model($masterid);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Stock master deleted successfully!'); 
        } else {
            return redirect()->back()->with('Empty', 'Try again');
        }
    }

    public function update_master_input_stock_name_controller(Request $request) {
        $masterid = $request->input('level_id');
        $newname = ucfirst(strtolower($request->input('new_name')));
        if(!$masterid || !$newname){
            return response()->json(['success'=>false, 'message'=>'Invalid data']);
        }
        $updated = (new stock())->update_stock_master_input_name_model($masterid, $newname);
        if ($updated === true) {
            return response()->json(['success'=>true, 'message'=>'Master input updated successfully!']); 
        } else {
            return response()->json(['success'=>false, 'message'=>'Try again']);
        }
    }

    public function stock_item_controller(Request $request) {
        $joinmasteranditems = (new stock())->join_stock_master_and_items_model();
        $toplevel = (new stock())->stock_top_level_model();
        if ($joinmasteranditems === false or $toplevel === false) {
            return response()->json(['success'=>false, 'message'=>'Database connection lost']);
        }

        return view('stock_category', ['data' => $joinmasteranditems, 'topLevels'=> $toplevel]);
    }

    public function save_new_stock_item_controller(Request $request) {
        $name = ucfirst(strtolower($request->input('itemname')));
        $masterid = $request->input('masterid');
        $insertitemdb = (new stock())->insert_new_stock_item_model($name, $masterid);
        if ($insertitemdb === true) {
            return redirect()->back()->with('success', 'Category saved successfully!');
        } elseif ($insertitemdb === false) {
            return redirect()->back()->with('Empty', 'Database connection lost!');
        } elseif ($insertitemdb === 'empty') {
            return redirect()->back()->with('Empty', 'Required fields are empty');
        }
    }

    public function delete_stock_item_controller(Request $request) {
        $cat_id = $request->input('cat_id');
        $deleteitem = (new stock())->delete_stock_item_model($cat_id);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Stock item deleted successfully!');
        }
    }

    public function update_stock_item_name_controller(Request $request){
        $itemid = $request->input('cat_id');
        $newname = ucfirst(strtolower($request->input('new_name')));
        $updatenewname = (new stock())->update_stock_item_name_model($itemid, $newname);
        if ($updatenewname === true) {
            return response()->json(['success'=>true, 'message'=>'Menu item name updated successfully!']);

        }
    }

    public function stock_type_of_items_controller(Request $request) {
        $data = (new stock())->stock_type_of_items_model();
        $topLevelData = (new stock())->master_and_ctegory_for_stock_item_model();
        return view('stock_type_of_item', ['data' => $data, 'topLevels' => $topLevelData]);

    }

    public function save_new_stock_type_of_item_controller(Request $request) {
        $name = ucfirst(strtolower($request->input('typeitemname')));
        $saleprice = $request->input('saleprice');
        $masterid = $request->input('itemid');
        $unit = $request->input('unit');
        if (!empty($name) && !empty($masterid) && !empty($saleprice) && !empty($unit)){
            $save_in_db = (new stock())->save_new_item_of_stock_modle($name, $saleprice, $unit, $masterid);
            if ($save_in_db === true) {return redirect()->back()->with('success', 'Stock type of item saved successfully!');}
        }
        return redirect()->back()->with('Empty', 'Required fields are empty');
    }

    public function update_stock_item_controller(Request $request) {
        $itemid = $request->input('item_id');
        $new_value = $request->input('new_value');
        $unit = $request->input('unit');
        $field = $request->input('field');
        if(!$itemid || !$field || $new_value === null){
            return response()->json(['success'=>false, 'message'=>'Invalid data']);
        }

        if ($field === 'name') {
            $new_value = ucfirst(strtolower($new_value));
            $insertdb = (new stock())->update_type_of_stock_item_name_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Stock type of item name updated successfully!']);}
        } elseif ($field === 'item_price') {
            $new_value = round($new_value, 2);
            $insertdb = (new stock())->update_type_of_stock_item_price_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Stock type of item price updated successfully!']);}
        } elseif ($field === 'unit') {
            $insertdb = (new stock())->update_type_of_stock_item_unit_model($itemid, $unit);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Stock type of item unit updated successfully!']);}
        }
    }


    public function delete_main_stock_controller(Request $request) {
        $itemid = $request->input('item_id');
        $deleteitem = (new stock())->delete_main_stock_item_model($itemid);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Main Stock item deleted successfully!');
        }
    }

    public function main_stock_full_controller(Request $request) {
        $data = (new stock())->main_stock_complete_model();
        $grouped = collect($data)->groupBy('master_id');
        return view('stock_main', ['grouped'=>$grouped]);
    }

    
}
