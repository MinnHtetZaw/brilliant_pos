<?php

namespace App\Http\Controllers\Web;

use App\From;
use App\Category;
use App\Purchase;
use App\Itemadjust;
use App\Stockcount;
use App\CountingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Item;
use Exception;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    protected function getStockPanel()
    {
    	return view('Stock.stock_panel');
    }

    protected function getStockCountPage(Request $request)
    {
        $instockItemType = 2;
       $items= Item::with('category')->with('sub_category')->where('item_type',$instockItemType)->get();
    	return view('Stock.stock_count_page', compact('items'));
    }

    protected function itemadjust(Request $request)
    {

        $role= $request->session()->get('user')->role;
        if($role=='Sale_Person'){

            $item_from= $request->session()->get('user')->from_id;

      }
      else {
        $item_from= $request->session()->get('from');
      }
       $items= From::find($item_from)->items()->with('category')->with('sub_category')->with('counting_units')->with('counting_units.stockcount')->get();

        $shops = From::all();
    	return view('Itemadjust.create_itemadjust', compact('items','shops'));
    }

    protected function getstocklists(Request $request)
    {
    //     $role= $request->session()->get('user')->role;
    //     if($role=='Sale_Person'){

    //         $item_from= $request->session()->get('user')->from_id;

    //   }
    //   else {
    //     $item_from= $request->session()->get('from');
    //   }
    //   $items= From::find($item_from)->items()->with('category')->with('sub_category')->with('counting_units')->with('counting_units.stockcount')->get();

    //     $shops = From::all();
        $items = Item::all();
    	return view('Stock.stock_lists', compact('items'));
    }

    protected function getStockPricePage()
    {
        $instockItemType = 0;
        $items= Item::with('category')->with('sub_category')->where('item_type',$instockItemType)->get();

    	return view('Stock.stock_price_page', compact('items'));
    }

    protected function getStockReorderPage(Request $request)
    {
        $role= $request->session()->get('user')->role;

       $items= Item::with('category')->with('sub_category')->get();
       //TODO
        dd($items);
        $shops = From::all();
    	return view('Stock.reorder_page', compact('items','shops'));
    }

    protected function updateStockCount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required',
            'item_code' => 'required',
            'unit_name' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back();
        }

        $item_id = $request->unit_id;

        try {

            $item = Item::findOrFail($item_id);

        } catch (\Exception $e) {

            alert()->error("Item Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $item->item_code = $request->item_code;

        $item->item_name = $request->unit_name;

        $item->save();

        alert()->success('Successfully Updated!');

        return redirect()->back();
    }

    protected function updateStockPrice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required',
            'purchase_price' => 'required',
            'normal_sale_price' => 'required',
            'whole_sale_price' => 'required',
            'order_price' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back();
        }

        $id = $request->unit_id;

        try {

            $unit = CountingUnit::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Counting Unit Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $unit->purchase_price = $request->purchase_price;

        $unit->normal_sale_price = $request->normal_sale_price;

        $unit->whole_sale_price = $request->whole_sale_price;

        $unit->order_price = $request->order_price;

        $unit->save();

        alert()->success('Successfully Updated!');

        return redirect()->back();
    }
    public function stockUpdateAjax(Request $request)
    {
        try {
        $item = Item::findOrfail($request->item_id);
        $item->stock = $request->stock_qty;
        $item->save();
        } catch (\Exception $e) {
        return response()->json(0);
        }

        return response()->json($item);
    }

    public function reservestockUpdateAjax(Request $request)
    {
        try {
        $item = Item::findOrfail($request->item_id);
        $item->reserve_qty = $request->reserve_qty;
        $item->save();
        } catch (\Exception $e) {
        return response()->json(0);
        }

        return response()->json($item);
    }

    public function reservestockResetAjax(Request $request)
    {
        try {
        $items = Item::all();
        foreach($items as $item){
        $item->reserve_qty = 0;
        $item->save();
        }
        } catch (\Exception $e) {
        return response()->json(0);
        }

        return response()->json($items);
    }

    public function priceUpdateAjax(Request $request)
    {
        try {
        $item = Item::findOrfail($request->item_id);
        $item->purchase_price = $request->purchase_price;
        $item->save();
        } catch (\Exception $e) {
        return response()->json(0);
        }

        return response()->json($item);
    }
    public function purchaseUpdateAjax(Request $request)
    {
        $purchase = Purchase::findOrfail($request->purchase_id);

        $diff_qty = $request->new_qty - $request->olderqty;

        $unit = DB::table('counting_unit_purchase')->where('counting_unit_id', $request->unit_id)->where('purchase_id',$request->purchase_id)->update(['quantity' => $request->new_qty]);


        $unit_first = DB::table('counting_unit_purchase')->where('counting_unit_id', $request->unit_id)->where('purchase_id',$request->purchase_id)->first();

        // $unit->quantity = $diff_qty;

        $diff_total= ($diff_qty) * $unit_first->price;

        $purchase_new_total = $purchase->total_price + ($diff_total);

        try {

            $purchase->total_price = $purchase_new_total;
            $purchase->save();

        } catch (Exception $e) {
            return response()->json(0);
        }

        try {
            $update_stock = Stockcount::where('counting_unit_id',$request->unit_id)->where('from_id',1)->first();

        } catch (Exception $e) {
            return response()->json(0);

        }

        $balanced_qty = $update_stock->stock_qty + ($diff_qty);

        $update_stock->stock_qty= $balanced_qty;

        $update_stock->save();

        return response()->json($update_stock);

    }
    public function itemadjustLists(Request $request)
    {
        $item_from= $request->session()->get('from');

        $item_adjusts =  Itemadjust::where('from_id',$item_from)->get();

        return view('Itemadjust.itemadjust',compact('item_adjusts'));
    }
    public function itemadjustAjax(Request $request)
    {
        $userid = session()->get('user')->id;
        if($request->plusminus == 'plus'){
            $balanced_qty = (int)$request->currentqty + (int) $request->adjust_qty;
            $adjust_qty = $request->adjust_qty;
        }
        elseif($request->plusminus == 'minus'){

            $balanced_qty = (int)$request->currentqty - (int) $request->adjust_qty;
            $adjust_qty = -$request->adjust_qty;


        }
        $stock = Stockcount::updateOrCreate([
            'counting_unit_id'=> $request->unit_id,
            'from_id'=> $request->shop_id,
        ],
        [
            'stock_qty' => $balanced_qty,
        ]
        );

        $item_adjust = Itemadjust::create([
            "counting_unit_id" => $request->unit_id,
            "oldstock_qty" => $request->currentqty,
            "adjust_qty" => $adjust_qty,
            "newstock_qty" => $balanced_qty,
            "from_id" => $request->shop_id,
            "user_id" => $userid
        ]);

        if($stock){
            return response()->json($stock);
        }
        else{
            return response()->json(0);
        }
    }
}
