<?php 

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\menu_table as menu;

class menu_top_level extends Controller {

    public function top_level(Request $request) {

        $data = (new menu())->top_level();
        return view('menu_master_input', ['data' => $data]);

    }

    public function save_new_master_input(Request $request) {
        $name = ucfirst(strtolower($request->input('masterinput')));
        $saveindb = (new menu())->insert_new_master_input($name);
        if ($saveindb === true) {
            return redirect()->back()->with('success', 'Master input saved successfully!'); 
        } else {
            return redirect()->back()->with('Empty', 'Data not saved, Try again'); 
        }
        
    }

    public function delete_master_input(Request $request) {
        $masterid = $request->input('level_id');
        $deleteitem = (new menu())->delete_menu_master_input($masterid);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Master input deleted successfully!'); 
        } else {
            return redirect()->back()->with('Empty', 'Try again');
        }
    }

    public function update_master_input_name(Request $request) {
        $masterid = $request->input('level_id');
        $newname = ucfirst(strtolower($request->input('new_name')));
        if(!$masterid || !$newname){
            return response()->json(['success'=>false, 'message'=>'Invalid data']);
        }
        $updated = (new menu())->update_menu_master_input_name($masterid, $newname);
        if ($updated === true) {
            return response()->json(['success'=>true, 'message'=>'Master input updated successfully!']); 
        } else {
            return response()->json(['success'=>false, 'message'=>'Try again']);
        }
    }
    public function menu_item_control(Request $request) {
        $joinmasteranditems = (new menu())->join_menu_master_and_items();
        $toplevel = (new menu())->top_level();
        if ($joinmasteranditems === false or $toplevel === false) {
            return response()->json(['success'=>false, 'message'=>'Database connection lost']);
        }

        return view('menu_category', ['data' => $joinmasteranditems, 'topLevels'=> $toplevel]);
    }

    public function save_new_menu_item(Request $request) {
        $name = ucfirst(strtolower($request->input('itemname')));
        $masterid = $request->input('masterid');
        $insertitemdb = (new menu())->insert_new_menu_item($name, $masterid);
        if ($insertitemdb === true) {
            return redirect()->back()->with('success', 'Category saved successfully!');
        } elseif ($insertitemdb === false) {
            return redirect()->back()->with('Empty', 'Database connection lost!');
        } elseif ($insertitemdb === 'empty') {
            return redirect()->back()->with('Empty', 'Required fields are empty');
        }
    }

    public function delete_menu_item(Request $request) {
        $cat_id = $request->input('cat_id');
        $deleteitem = (new menu())->delete_menu_item($cat_id);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Menu item deleted successfully!');
        }
    }

    public function update_menu_item_name(Request $request){
        $itemid = $request->input('cat_id');
        $newname = ucfirst(strtolower($request->input('new_name')));
        $updatenewname = (new menu())->update_menu_item_name_model($itemid, $newname);
        if ($updatenewname === true) {
            return response()->json(['success'=>true, 'message'=>'Menu item name updated successfully!']);

        }
    }

    public function menu_type_of_items_controller(Request $request) {
        $data = (new menu())->menu_type_of_items_model();
        $topLevelData  = (new menu())->master_and_ctegory_for_menu_item_model();
        return view('menu_type_of_item', ['data' => $data, 'topLevels'=> $topLevelData]);

    }

    public function save_new_menu_item_controller(Request $request) {
        $name = ucfirst(strtolower($request->input('typeitemname')));
        $saleprice = $request->input('saleprice');
        $masterid = $request->input('itemid');
        if (!empty($name) && !empty($masterid) && !empty($saleprice)){
            $savedata = (new menu())->save_new_item_of_menu_modle($name, $saleprice, $masterid);
            if ($savedata === true) {return redirect()->back()->with('success', 'menu type of item saved successfully!');}
        }
        return redirect()->back()->with('Empty', 'Required fields are empty');

    }

    public function update_menu_item_controller(Request $request) {
        $itemid = $request->input('item_id');
        $new_value = $request->input('new_value');
        $field = $request->input('field');

        if(!$itemid || !$field || $new_value === null){
            return response()->json(['success'=>false, 'message'=>'Invalid data']);
        }

        if ($field === 'name') {
            $new_value = ucfirst(strtolower($new_value));
            $insertdb = (new menu())->update_type_of_menu_item_name_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Menu type of item name updated successfully!']);}
        } elseif ($field === 'item_price') {
            $new_value = round($new_value, 2);
            $insertdb = (new menu())->update_type_of_menu_item_price_model($itemid, $new_value);
            if ($insertdb === true) {return response()->json(['success'=>true, 'message' => 'Menu type of item price updated successfully!']);}
        }
    }

    public function delete_main_menu_controller(Request $request) {
        $itemid = $request->input('item_id');
        $deleteitem = (new menu())->delete_main_menu_item_model($itemid);
        if ($deleteitem === true) {
            return redirect()->back()->with('success', 'Main Menu item deleted successfully!');
        }
    }

    public function main_menu_full_controller(Request $request) {
        $data = (new menu())->main_menu_complete_model();
        $grouped = collect($data)->groupBy('master_id');
        return view('menu_main', ['grouped'=>$grouped]);
    }
}