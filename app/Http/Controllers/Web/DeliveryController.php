<?php

namespace App\Http\Controllers\Web;

use App\From;
use App\Item;
use App\User;
use DateTime;
use App\Voucher;
use App\Category;
use App\Customer;
use App\Discount;
use App\Employee;
use App\Stockcount;
use App\Packagetype;
use App\SubCategory;
use App\Wayplanning;
use App\CountingUnit;
use App\DiscountMain;
use App\SalesCustomer;
use App\Deliveryreceive;
use Dotenv\Regex\Success;
use Illuminate\Http\Request;
use App\SaleCustomerCreditlist;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
    {

    public function wayPlaningForm()
    {
        $deliverorder = Deliveryreceive::all();
        return view('Delivery.wayplaning',compact('deliverorder'));
    }

    public function wayPlaningLists()
    {
        $wayplanningLists = Wayplanning::all();
        return view('Delivery.wayplaningList',compact('wayplanningLists'));
    }
    public function deliveryOrderReceiveStore(Request $request)
    {
        $request->validate([
            "customerphone" => "required",
            "pickup" =>  "required",
            "pickupaddress" =>  "required",
            "pick_charges" =>  "required",
            "contactn_at_pickup" =>  "required",
            "contactp_at_pickup" =>  "required",
            "destination_add" =>  "required",
            "destination_township" =>  "required",
            "deliverycharges" =>  "required",
            "nameDestination" =>  "required",
            "contactph" =>  "required",
            "package_type"=>"required",
            "dimension" => "required",
            "pick_delivery"=> "required",
            "qty"=>"required"
		]);

        $qty =explode(',',$request->qty,-1);
        $dimension =explode(',',$request->dimension,-1);
        $pick_delivery =explode(',',$request->pick_delivery,-1);
        $qty =explode(',',$request->qty,-1);
        $price =explode(',',$request->price,-1);

         $deliverorder = Deliveryreceive::create([
            "customer_name"=>$request->customername,
            "customer_phone"=>$request->customerphone,
            "pick_delivery"=>$request->pickup ,
            "pickup_address"=>$request->pickupaddress,
            "pickup_township_id"=>$request->pickuptownship,
            "pickup_charges"=>$request->pick_charges ,
            "contactname_at_pickup"=>$request->contactn_at_pickup,
            "contactphone_at_pickup"=>$request->contactp_at_pickup ,
            "destination_address"=>$request->destination_add,
            "township_id"=>$request->destination_township,
            "delivery_charges"=>$request->deliverycharges,
            "contactname_at_destination"=>$request->nameDestination,
            "contactphone_at_destination"=>$request->contactph,
        ]);

        $count = count($pick_delivery);
        for ($i=0; $i < $count; $i++) {
            $deliverorder->packagelists()->attach($pick_delivery[$i], ['dimension' => $dimension[$i],'pickup_delivery'=> $pick_delivery[$i],'qty'=> $qty[$i],'price'=>$price[$i]]);
        }
        alert()->success('Success!');
        return back();

    }
    public function wayplanningstore(Request $request)
    {
        $request->validate([
            "wayno" => "required",
            "delivery_id" => "required",
            "date" => "required",
            "pickup" => "required",
            "township_id" => "required",
            "start_time" => "required",
            "end_time" => "required",
		]);
        $wayplanning = Wayplanning::create([
            "wayno" => $request->wayno,
            "delivery_id" => $request->delivery_id,
            "date" => $request->date,
            "pick_delivery" => $request->pickup,
            "township_id" => $request->township_id,
            "start_time" => $request->start_time,
            "end_time" => $request->end_time,
        ]);
        alert()->success("Successfully created");
        return back();
    }
    public function getshopList(Request $request)
    {
        $request->session()->put('ShopOrWh','shop');
    	return view('Admin.shoplists');
    }
    public function SalePage(Request $request,$id)
    {
        $request->session()->put('from',$id);
        $request->session()->put('ShopOrWh','shop');

        $adminpass = User::find(1)->password;
        // dd($adminpass);
        $role= $request->session()->get('user')->role;
        if($role=='Sale_Person'){
            $item_from= $request->session()->get('user')->from_id;
      }
      else {
        $item_from= $request->session()->get('from');
      }
      $warehouses=From::where('id',$item_from)->orWhere('id',3)->orWhere('id',4)->orWhere('id',5)->get();



        $name= $request->session()->get('from');

        $froms=From::find($id);
        $categories=[];
        $items = $froms->items()->with('category')->with('sub_category')->get();

        // foreach ($items as $item) {

        //     if (!isset($result[$item->category->id])){
        //         $result[$item->category->id] = $item->category;
        //     }
        // }
        // $categories = array_values($result);
        $categories = Category::all();
        $sub_categories = SubCategory::all();

        $employees = Employee::all();

        $date = new DateTime('Asia/Yangon');
        $customers = Customer::all();

        $today_date = strtotime($date->format('d-m-Y H:i'));
        $fItems =Item::with('category')->with('sub_category')->get();
        $salescustomers = SalesCustomer::all();
        $last_voucher = Voucher::get()->last();

        if(empty($last_voucher)){
            $voucher_code = "VOU-".date('dmY')."-".sprintf("%04s", (1+ 1));
        }else{
            $voucher_code =  "VOU-".date('dmY')."-".sprintf("%04s", ($last_voucher->id + 1));

        }


        // $today_date = $date->format('d-m-Y H:i');
        // dd($today_date);
    	return view('Sale.sale_page',compact('voucher_code','salescustomers','adminpass','fItems','warehouses','items','categories','employees','today_date','sub_categories','customers'));

    }
    public function storetestVoucher(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item' => 'required',
            'grand' => 'required',
            'location_flag' => 'required',
            'page_name' => 'required',
            'customer_address' => 'required',
            'customer_phone' => 'required',
            'customer_name' => 'required',
            'orderDate' => 'required',
        ]);


        if ($validator->fails()) {

            return response()->json([
                'status' => 0,
            ]);
        }

        $user = session()->get('user');

        if($request->editvoucher != 0 ){
            $check_voucher = Voucher::findOrfail($request->editvoucher);
            $voucher_code = $check_voucher->voucher_code;
            $units = $check_voucher->items;
            foreach($units as $unit){
                $current_qty = Item::findOrfail($unit->id);
                $balanceReservedQty = $current_qty->reserve_qty - $unit->pivot->quantity;
                $current_qty->reserve_qty = $balanceReservedQty ;
                $current_qty->save();
            }
            $deleted = DB::table('vouchers')->where('id', $request->editvoucher)->delete();
        }
        else{
            $voucher_code = $request->voucher_code;
        }

        // try {

        $date = new DateTime('Asia/Yangon');

        $today_date = $date->format('d-m-Y h:i:s');

        $orderDate = $request->orderDate;

        $today_time = $date->format('g:i A');

        $items = json_decode($request->item);

        $grand = json_decode($request->grand);

        $total_quantity = $grand->total_qty;

        $total_amount = $grand->sub_total;
        // $agent = new \Jenssegers\Agent\Agent;

        // $is_mobile = $agent->isDesktop();

        // $is_mobile = $agent->isMobile();

        if($request->payment_type == 1){ //prepaid full
            $prepaid_clear_flash = 1;
        }else{
            $prepaid_clear_flash = 0;
        }

        if($request->order_type == 2){
            $order_type = 0;
        }else if($request->order_type == 3){
            $order_type = 1;
        }else{
            $order_type = $request->order_type;
        }

        //check reserve_qty not greater than stock
        foreach ($items as $item) {

            if($request->order_type==0 || $request->order_type == 2) {// if instock-item to increase reserve_qty
                $origin_item = Item::find($item->id);
                $new_reserve_qty = ($origin_item->reserve_qty + $item->order_qty);
                if($new_reserve_qty<=$origin_item->stock){
                    $origin_item->reserve_qty = $new_reserve_qty;
                    $origin_item->save();
                }else{
                    return response()->json([
                        'status' => 0,
                        'message'=> 'Reserve Qty ပြည့်သွားပါပြီ'
                    ]);
                }

            }

        }
        $voucher = Voucher::create([
            'voucher_code' => $voucher_code,
            'order_date' => $orderDate,
            'order_type' => $order_type,
            'location_flag' => $request->location_flag,
            'customer_name'=> $request->customer_name,
            'customer_phone'=> $request->customer_phone,
            'customer_address'=> $request->customer_address,
            'payment_type'=> $request->payment_type,
            'prepaid_clear_flash'=> $prepaid_clear_flash,
            'total_quantity' => $total_quantity,
            'item_charges' =>  $total_amount,
            'delivery_charges'=> $request->deliveryfee,
            'total_charges' => (int)$total_amount + (int)$request->deliveryfee,
            'status' => 0,
            'prepaid_amount'=> 0,
            'collect_amount'=>(int)$total_amount + (int)$request->deliveryfee,
            'sale_by' => $user->id,
            'page_id' =>$request->page_name,
            'delivery_expense'=> $request->delivery_expense
        ]);

        //  return response()->json("here");
        if($request->order_type == 0){
            $item_status = 3;
        }else{
            $item_status = 0;
        }
            foreach ($items as $item) {

                $voucher->items()->attach($item->id, ['quantity' => $item->order_qty,'price' => $item->selling_price,'status'=> $item_status,'remark' => $item->remark]);

            }


        $role= $request->session()->get('user')->role;

        $items = Item::where('item_type',$request->order_type)->with('category')->with('sub_category')->get();

        $last_voucher = Voucher::get()->last();

        $voucher_code =  "VOU-".date('dmY')."-".sprintf("%04s", ($last_voucher->id + 1));
        return response()->json([
            'status' => 1,
            'voucher'=>$voucher,
            'voucher_code' => $voucher_code,
            'items' =>$items,
        ]);

        // } catch (\Exception $e) {

        //     return response()->json([
        //         'status' => 0,
        //         'message'=> 'Order တင်ခြင်း မအောင်မြင်ပါ'
        //     ]);

        // }
    }
    public function getItemA5(Request $request)
    {
        // dd($request->items);
        $items = json_decode(json_encode($request->items));
        // dd($items);
        return response()->json($items);
    }
}
