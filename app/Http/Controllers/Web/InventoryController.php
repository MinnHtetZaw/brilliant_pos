<?php

namespace App\Http\Controllers\Web;

use App\From;
use App\Item;
use App\BankAccount;
use Exception;
use App\Category;
use App\Stockcount;
use App\SubCategory;
use App\CountingUnit;
use App\UnitRelation;
use App\UnitConversion;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;
use App\Voucher;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
	protected function getInventoryDashboard()
	{
		return view('Inventory.inv_dashboard');
	}

	protected function categoryList()
	{
		$category_lists =  Category::whereNull("deleted_at")->get();

		return view('Inventory.category_list', compact('category_lists'));
	}

	protected function storeCategory(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'category_code' => 'required',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }


        $user_code = $request->session()->get('user')->user_code;

        try {

            $category = Category::create([
                'category_name' => $request->category_name,
                'created_by' => $user_code,
            ]);

            $category->category_code = sprintf('%03s',$request->category_code);

            $category->save();

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Category.');

            return redirect()->back();
        }


    	alert()->success('Successfully Added');

        return redirect()->route('category_list');
	}

	protected function updateCategory($id, Request $request)
	{
		try {

        	$category = Category::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $category->category_code = $request->category_code;

        $category->category_name = $request->category_name;

        $category->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('category_list');
	}

	protected function deleteCategory(Request $request)
	{
		$id = $request->category_id;

        $category = Category::find($id);

        // $items = Item::where('category_id', $id)->get();

        // foreach ($items as $item) {

        //     foreach ($item->counting_units as $unit) {

        //         $unit->delete();
        //     }
        // }

        $items = Item::where('category_id', $id)->delete();
        $sub_categories = SubCategory::where('category_id', $id)->delete();

        $category->delete();

        return response()->json($category);
	}

	protected function subcategoryList()
	{
		$categories = Category::all();

		$sub_categories = SubCategory::all();

		return view('Inventory.subcategory_list', compact('categories','sub_categories'));
	}

	protected function storeSubCategory(request $request){

	    $validator = Validator::make($request->all(), [
            'sub_category_code' => 'required',
            'sub_category_name' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }


            $sub_category = SubCategory::create([
                'name' => $request->sub_category_name,
                'category_id' => $request->category,
            ]);

            $sub_category->subcategory_code = sprintf('%03s',$request->sub_category_code);

            $sub_category->save();

    	alert()->success('Successfully Added');

        return redirect()->route('subcategory_list');
	}

	protected function updateSubCategory(request $request, $id){

	   try {

        	$sub_category = SubCategory::findOrFail($id);

   		} catch (\Exception $e) {

        	alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

    	}

        $sub_category->subcategory_code = $request->sub_category_code;

        $sub_category->name = $request->sub_category_name;

        $sub_category->save();

        alert()->success('Successfully Updated!');

        return redirect()->route('subcategory_list');

	}

	protected function showSubCategory(request $request){

	    $category_id = $request->category_id;

	    $subcategory = SubCategory::where('category_id', $category_id)->get();

	    return response()->json($subcategory);
	}

    protected function deleteSubCategory(Request $request)
	{
		$id = $request->subcategory_id;

        $subcategory = SubCategory::find($id);

        $items = Item::where('sub_category_id', $id)->delete();

        $subcategory->delete();

        return response()->json($subcategory);
	}
    
	protected function itemList($instockOrPreorder)
	{
        if($instockOrPreorder == 'instock'){   //item_type -0
            $item_lists =  Item::whereNull("deleted_at")->where('item_type',2)->orderBy('category_id', 'ASC')->get();

        }else if($instockOrPreorder == 'preorder')
        {
            $item_lists =  Item::whereNull("deleted_at")->where('item_type',3)->orderBy('category_id', 'ASC')->get();
        }

        // $units=CountingUnit::whereNull("deleted_at")->orderBy('id', 'ASC')->get();

		$categories =  Category::whereNull("deleted_at")->get();


		$sub_categories = SubCategory::all();

		return view('Inventory.item_list', compact('item_lists','categories','sub_categories','instockOrPreorder'));
	}

    protected function item_register(Request $request){
        $categories = Category::all();
        $sub_categories = SubCategory::all();
        return view('Inventory.item_register',compact('categories','sub_categories'));
    }
    
    public function item_search(Request $request){
        
        $item_type = $request->item_type;
        
        $category_id = $request->category_id;
        
        
        $items = Item::where("category_id",$category_id)->where("item_type",$item_type)->get();
        
        return response()->json($items);
    }

	protected function storeItem(Request $request)
	{
		$validator = Validator::make($request->all(), [
            'category' => 'required',
            // 'sub_category' => 'required',
            'item_name' => 'required',
            'item_code' => 'required',
            'selling_price' => 'required',
            'purchase_price' => 'required',
            'stock' =>'required',
            'inlineRadioOptions'=> 'required'
        ]);

      
        if ($validator->fails()) {

            alert()->error('Something Wrong!');

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->user_code;

        if (isset($request->customer_console)) {

        	$customer_console = 0;   //Customer ko pya mar
        }else{

        	$customer_console = 1;	//Customer ko ma pya
        }

        if ($request->hasfile('photo')) {

			$image = $request->file('photo');

			$name = $image->getClientOriginalName();

			$photo_path = $name;

			$image->move('frontend/assets/img', $photo_path);
		}
		else{

			$photo_path = "default.jpg";

		}

            $item = Item::create([
                'item_code' => $request->item_code,
                'item_name' => $request->item_name,
                'created_by' => $user_code,
                // 'photo_path' => $photo_path,
                // 'customer_console' => $customer_console,
                'category_id' => $request->category,
                // 'sub_category_id' => $request->sub_category,
                'stock' => $request->stock,
                'item_type' => $request->inlineRadioOptions,
                'selling_price' =>$request->selling_price,
                'purchase_price' =>$request->purchase_price,
                'sku_code' => $request->sku_code,
                // 'original_code' => $request->original_code,
                // 'description' =>$request->description,
                // 'web_description' => $request->web_description,
                'brand_name' => $request->brand_name,
                'model_size' => $request->model_size,
            ]);

        alert()->success('Successfully Added');

        return redirect()->route('item_list','instock');
	}

    protected function updateItem($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_code' => 'required',
            'item_name' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong!');

            return redirect()->back();
        }

        try {

            $item = Item::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Item Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        if ($request->hasfile('photo_path')) {

            $image = $request->file('photo_path');

            $name = $image->getClientOriginalName();

            $photo_path =  time()."-".$name;

            $image->move(public_path() . '/photo/Item', $photo_path);
        }else{

            $photo_path = $item->photo_path;
        }


        $item->item_code = $request->item_code;

        $item->item_name = $request->item_name;

        $item->category_id = $request->category_id;

        $item->sub_category_id = $request->sub_category_id;

        $item->photo_path = $photo_path;

        $item->save();

        alert()->success('Successfully Updated!');

        return redirect()->back();
    }

    protected function deleteItem(Request $request)
    {
        $id = $request->item_id;

        $item = Item::find($id);

        $counting_units = CountingUnit::where('item_id', $item->id)->get();

        foreach ($counting_units as $unit) {

            $unit->delete();
        }

        $item->delete();

        return response()->json($item);
    }

    protected function bankAccList()
    {
        $bank = BankAccount::all();
        return view('Admin.bank_acc_list',compact('bank'));
    }
    public function editAccount(request $request, $id){

        $account = BankAccount::find($id);

        $account->account_number = $request->acc_number;

        $account->opeing_date = $request->opening_date;

        $account->account_holder_name = $request->holder_name;

        $account->bank_name = $request->bank_name;

        $account->bank_address = $request->bank_address;

        $account->bank_contact = $request->bank_contact;

        $account->balance = $request->balance;

        $account->save();

        return redirect()->route('bank_list');
     }
    protected function store_bank_account(Request $request)
    {
        // dd($request->all());

            $validator = Validator::make($request->all(), [
                 'bank_name' => 'required',
                 'bank_address' => 'required',
                 'bank_contact' => 'required',
                 'acc_number' => 'required',
                 'holder_name' => 'required',
                 'opening_date' => 'required',

             ]);


             if ($validator->fails()){

                 return redirect()->back();
             }

             $account = BankAccount::create([

                 'account_number' => $request->acc_number,
                 'opeing_date' => $request->opening_date,
                 'account_holder_name' => $request->holder_name,
                 'balance' => $request->current_balance,
                 'bank_name' => $request->bank_name,
                 'bank_address' => $request->bank_address,
                 'bank_contact' => $request->bank_contact,

             ]);

             return redirect()->back();



    }
    protected function TransactionList($id)
    {
        // dd("dd");
        $unit = Voucher::with('items')->find($id);
        // dd($unit);
        $bank = BankAccount::all();
        $transaction = Transaction::where('voucher_id',$id)->get();
        // dd($transaction);
        return view('Admin.transaction_list', compact('unit','bank','transaction'));
    }
    protected function store_transaction_now(Request $request)
    {
       
        // dd($time);
        $validator = Validator::make($request->all(), [
            'pay_date' => 'required',
            'pay_time' => 'required',
            'pay_amt' => 'required',
            'remark' => 'required',
        ]);
        

        if ($validator->fails()){
            alert()->error('Fill all the basic fields');
            return redirect()->back();
        }
        $time = date('h:i a', strtotime($request->pay_time));
        // dd($request->all());
        $voucher = Voucher::find($request->vou_id);
        $voucher->prepaid_amount +=$request->pay_amt;
        // dd($voucher->prepaid_amount);
        
        // dd($voucher->total_charges);
        $voucher->collect_amount = $request->collect_amt;
        $voucher->save();
        $transaction = Transaction::create([
            'bank_acc_id' => $request->bank_info,
            'tran_date' => $request->pay_date,
            // 'tran_time' => $time,
            'remark' => $request->remark,
            'pay_amount' => $request->pay_amt,
            'voucher_id' => $request->vou_id,
        ]);
        // dd("done");
        $bank = BankAccount::find($request->bank_info);
        $bank->balance += $request->pay_amt;
        $bank->save();
        if($voucher->payment_type == 1 && $voucher->total_charges <= $voucher->prepaid_amount &&  $voucher->prepaid_clear_flash = 1)
        {
            $voucher->prepaid_clear_flash = 0;
            $voucher->save();
        }
        alert()->success("Successfully Stored Transaction!!");
        return back();

    }
	protected function getUnitList($item_id)
    {

		$units = CountingUnit::where('item_id', $item_id)->whereNull("deleted_at")->get();

        try {

        	$item = Item::findOrFail($item_id);

   		} catch (\Exception $e) {

        	alert()->error("Item Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

    	return view('Inventory.unit_list', compact('units','item'));
	}

	protected function storeUnit(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'current_qty' => 'required',
            'reorder_qty' => 'required',
            'purchase_price' => 'required',
            'normal_price' => 'required',
            'whole_price' => 'required',
            'order_price' => 'required',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {

            $counting_unit = CountingUnit::create([
                'unit_name' => $request->name,
                'current_quantity' => $request->current_qty,
                'reorder_quantity' => $request->reorder_qty,
                'normal_sale_price' => $request->normal_price,
                'whole_sale_price' => $request->whole_price,
                'purchase_price' => $request->purchase_price,
                'order_price' => $request->order_price,
                'item_id' => $request->item_id,
                "normal_fixed_flash"=> $request->normal_fixed ?? 0,
                "normal_fixed_percent"=> $request->normal_fixed ? $request->normal_percent : 0,
                "whole_fixed_flash"=> $request->whole_fixed ?? 0,
                "whole_fixed_percent"=> $request->whole_fixed ? $request->whole_percent : 0,
                "order_fixed_flash"=> $request->order_fixed ?? 0,
                "order_fixed_percent"=> $request->order_fixed ? $request->order_percent : 0,
            ]);

        }
        catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Counting Unit.');

            return redirect()->back();
        }

        alert()->success('Successfully Stored!');

        return redirect()->back();
	}

    protected function updateUnit($id,Request $request)
    {
        try {

            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $unit->unit_name = $request->name;
		$unit->current_quantity= $request->current_quantity;
		$unit->reorder_quantity= $request->reorder_quantity;
		$unit->normal_sale_price= $request->normal_price;
		$unit->whole_sale_price = $request->whole_price;
		$unit->order_price= $request->order_price;
		$unit->purchase_price= $request->purchase_price;
        if(isset($request->normal_fixed_flash)){
            $unit->normal_fixed_flash = $request->normal_fixed_flash;
            $unit->normal_fixed_percent = $request->normal_fixed_percent;
        }
        else{
            $unit->normal_fixed_flash = 0;
            $unit->normal_fixed_percent = 0;
        }
        if(isset($request->whole_fixed_flash)){
            $unit->whole_fixed_flash = $request->whole_fixed_flash;
            $unit->whole_fixed_percent = $request->whole_fixed_percent;
        }
        else{
            $unit->whole_fixed_flash = 0;
            $unit->whole_fixed_percent = 0;
        }
        if(isset($request->order_fixed_flash)){
            $unit->order_fixed_flash = $request->order_fixed_flash;
            $unit->order_fixed_percent = $request->order_fixed_percent;
        }
        else{
            $unit->order_fixed_flash = 0;
            $unit->order_fixed_percent = 0;

        }

        $unit->save();

        alert()->success('Successfully Stored!');

        return redirect()->back();
    }

    protected function updateUnitCode($id, Request $request)
    {

        try {

            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $unit->unit_code = $request->code;

        $unit->save();

        alert()->success('Successfully Stored!');

        return redirect()->back();
    }

    public function itemAssign(Request $request)
    {
        $from = session()->get('from');
        $item_lists =  Item::whereNull("deleted_at")->orderBy('category_id', 'ASC')->with('froms')->get();
        $shops= From::all();
		return view('Inventory.item_assign', compact('item_lists','shops'));
    }
    public function itemAssignShop(Request $request)
    {
        $item_lists =  Item::whereNull("deleted_at")->orderBy('category_id', 'ASC')->with('category')->with("sub_category")->with('froms')->get();
        $shops= From::all();
		return response()->json($item_lists) ;
    }
    protected function updateOriginalCode($id, Request $request)
    {
        try {

            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $unit->original_code = $request->code;

        $unit->save();

        alert()->success('Successfully Stored!');

        return redirect()->back();
    }

    public function itemAssignajax(Request $request)
    {
        try{
            $from = From::find($request->from_id);
            $from->items()->sync($request->item_array);
        }
        catch(Exception){
            return response()->json(0);
        }
        $item_array = $request->item_array;
        for($i=0;$i<count($request->item_array);$i++){
            $counting_u= Item::find($item_array[$i])->counting_units;
            foreach($counting_u as $key=>$count){
                $stock = Stockcount::updateOrCreate([
                    'counting_unit_id'=> $count->id,
                    'from_id'=> $request->from_id,
                ]);
            }
        }

        return response()->json(1);
    }
    protected function deleteUnit(Request $request)
    {
        $id = $request->unit_id;

        $unit = CountingUnit::find($id);

        $unit->delete();

        return response()->json($unit);
    }

    protected function unitRelationList($item_id)
    {

        $unit_relation = UnitRelation::where('item_id', $item_id)->get();

        $item = Item::find($item_id);

        $counting_units = CountingUnit::where('item_id', $item_id)->get();

        return view('Inventory.unit_relation', compact('unit_relation','item','counting_units'));

    }

    protected function storeUnitRelation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'from_unit' => 'required|numeric',
            'to_unit' => 'required|numeric',
            'qty' => 'required|numeric',
        ]);

        if($validator->fails()){

            alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

            return redirect()->route('unit_relation_list', $request->item_id);
        }

        $unit_relation = UnitRelation::where('item_id', request('item_id'))
            ->where('from_unit', request('from_unit'))
            ->where('to_unit', request('to_unit'))
            ->first();

        $unit_relation_rev = UnitRelation::where('item_id', request('item_id'))
            ->where('from_unit', request('to_unit'))
            ->where('to_unit', request('from_unit'))
            ->first();

        if(!empty($unit_relation) || !empty($unit_relation_rev)){

            alert()->error('This Relation has already Exist!');

            return redirect()->route('unit_relation_list', $request->item_id);

        }else{

            try {

                $unit_relation = UnitRelation::create([
                        "item_id" => $request->item_id,
                        "from_unit" => $request->from_unit,
                        "to_unit" => $request->to_unit,
                        "quantity" => $request->qty,
                ]);

            } catch (\Exception $e) {

                alert()->error('Something Wrong! When Unit Realation.');

                return redirect()->back();
            }

            alert()->success('Successfully Added!');

            return redirect()->route('unit_relation_list', $request->item_id);
        }
    }

    protected function updateUnitRelation($id, Request $request)
    {
        try {

            $category = Category::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Category Not Found!")->persistent("Close!");

            return redirect()->back();

        }
    }

    protected function deleteUnitRelation(Request $request)
    {
        dd("Hello");
    }

    protected function convertUnit($unit_id)
    {

        $unit = UnitRelation::find($unit_id);
        dd($unit);
        return view ('Inventory.unit_convert', compact('unit'));
    }

    protected function ajaxConvertResult(Request $request)
    {

        $unit_id = $request->unit_id;

        $from = $request->from;

        $to = $request->to;

        $qty = $request->qty;

        $unit = UnitRelation::find($unit_id);

        $result_qty_one = $qty * $unit->quantity;

        $from_unit_balance = $unit->to_unit_detail->current_quantity - $qty;

        $to_unit_balance = $unit->from_unit_detail->current_quantity + $result_qty_one;

        $to_unit = CountingUnit::find($request->to);

        $to_unit->current_quantity = $to_unit_balance;

        $to_unit->save();

        $from_unit = CountingUnit::find($request->from);

        $from_unit->current_quantity = $from_unit_balance;

        $from_unit->save();

        $unit_conversion_log = UnitConversion::create([
            "item_id" => $unit->item_id,
            "from_unit_id" => $request->from,
            "from_unit_quantity" => $from_unit_balance,
            "to_unit_id" => $request->to,
            "to_unit_quantity" => $to_unit_balance,
        ]);

        return response()->json([
            'from_unit_quantity' => $from_unit_balance,
            'from_unit' => $from_unit->unit_name,
            'to_unit_quantity' => $to_unit_balance,
            'to_unit' => $to_unit->unit_name,
        ]);

    }

    protected function convertCountUnit(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'unit_id' => 'required',
            'result_qty' => 'required',
            'result_unit' => 'required',
            'from_unit_id' => 'required',
            'from_unit_qty' => 'required',
            'to_unit_id' => 'required',
            'to_unit_qty' => 'required',
        ]);

        if($validator->fails()){

            alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

            return redirect()->back();
        }

        $to_unit = CountingUnit::find($request->to_unit_id);

        $to_unit->current_quantity = $request->to_unit_qty;

        $to_unit->save();

        $from_unit = CountingUnit::find($request->from_unit_id);

        $from_unit->current_quantity = $request->from_unit_qty;

        $from_unit->save();

        $unit_conversion_log = UnitConversion::create([
            "item_id" => $request->item_id,
            "from_unit_id" => $request->from_unit_id,
            "from_unit_quantity" => $request->from_unit_qty,
            "to_unit_id" => $request->to_unit_id,
            "to_unit_quantity" => $request->to_unit_qty,
        ]);


        alert()->success("Successfully Converted!");

        return redirect()->route('count_unit_list', ['item_id' => $request->item_id]);
    }

    protected function AjaxGetItem(Request $request){

        $shop_id = $request->shop_id;

       $items= From::find($shop_id)->items()->with('category')->with('sub_category')->with('counting_units')->with('counting_units.stockcount')->get();

        return response()->json($items);
    }

    protected function AjaxGetCountingUnit(Request $request){

        $item_id = $request->item_id;
        $item = Item::findOrfail($item_id);
        return response()->json($item);
    }
    protected function getunitprice(Request $request){

        $unit_id = $request->unit_id;
        $counting_unit = CountingUnit::findOrfail($request->unit_id);
        return response()->json($counting_unit);
    }
}