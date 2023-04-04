<?php

namespace App\Http\Controllers\Web;

use App\Item;
use App\User;
use Datetime;
use App\Order;
use App\Fbpage;
use App\Voucher;
use App\Customer;
use App\Delivery;
use App\Employee;
use Carbon\Carbon;
use App\Arriveditems;
use App\CountingUnit;
use App\Deliveredorder;
use App\Canceledorder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use stdClass;
use Maatwebsite\Excel\Excel;
use App\Exports\DeliveredOrdersHistoryExport;

class OrderController extends Controller
{
    private $excel;


    public function __construct(Excel $excel){
        $this->excel = $excel;
    }

    protected function getOrderPanel(){

    	return view('Order.order_panel');
    }

    protected function getOrderPage($type){

    	$order_lists = Order::where('status',$type)->get();

        $employee_lists = Employee::all();

    	return view('Order.order_page', compact('order_lists','type','employee_lists'));
    }

    protected function storeCustomerOrder(Request $request){

        $now = new DateTime;

        $today = strtotime($now->format('d-m-Y'));

        $validator = Validator::make($request->all(), [
            'address' => 'required',
            'phone' => 'required',
            'item' => 'required',
            'employee' => 'required',
            'order_date' => 'required|after_or_equal:today',
            'grand_total' => 'required',
            'customer_id' => 'required',
        ]);

        if ($validator->fails()) {

            return response()->json(['error' => 'Something Wrong! Validation Error'], 404);
        }

        $user = session()->get('user');

        $items = json_decode($request->item);

        $grand = json_decode($request->grand_total);

        $total_quantity = $grand->total_qty;

        $total_amount = $grand->sub_total;

        $customer = Customer::find($request->customer_id);

        $order_format_date = date('Y-m-d', strtotime($request->order_date));

        $deli_format_date = date('Y-m-d H:i', strtotime($request->delivered_date));

        try {

             $order = Order::create([
                'address' => $request->address,
                'name' => $customer->user->name,
                'phone' => $request->phone,
                'total_quantity' => $total_quantity,
                'est_price' => $total_amount,
                'order_date' => $order_format_date,
                'delivered_date' => $deli_format_date,
                'customer_id' => $customer->id,
                'employee_id' => $request->employee,
                'status' => 4,
            ]);

            $order->order_number = "ORD-".sprintf("%04s", $order->id);

            $order->save();

            foreach ($items as $item) {

                $order->counting_unit()->attach($item->id, ['quantity' => $item->order_qty]);

            }

            $voucher = Voucher::create([
                'sale_by' => $user->id,
                'total_price' =>  $total_amount,
                'total_quantity' => $total_quantity,
                'voucher_date' => $deli_format_date,
                'type' => 2,
                'status' => 0,
                'order_id' => $order->id,
                'sales_customer_id' => 0,
                'sales_customer_name' => "",
            ]);

            $voucher->voucher_code = "VOU-".date('dmY')."-".sprintf("%04s", $voucher->id);

            $voucher->save();

            foreach ($items as $unit) {

                $voucher->counting_unit()->attach($unit->id, ['quantity' =>  $unit->order_qty,'price' => $unit->selling_price]);

                $counting_unit = CountingUnit::find($unit->id);

                $balance_qty = ($counting_unit->current_quantity - $unit->order_qty);

                $counting_unit->current_quantity = $balance_qty;

                $counting_unit->save();

            }

            return response()->json($voucher);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Something Wrong! When Store Customer Order'], 404);

        }

    }

    protected function getOrderDetailsPage($id){

        try {

            $order = Order::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Order Not Found!")->persistent("Close!");

            return redirect()->back();
        }

        return view('Order.order_details', compact('order'));
    }

    protected function changeOrderStatus(Request $request){

        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error('Something Wrong! Validation Error!');

            return redirect()->back();
        }

        $user = session()->get('user');

    	try {

        	$order = Order::findOrFail($request->order_id);

   		} catch (\Exception $e) {

        	alert()->error("Order Not Found!")->persistent("Close!");

            return redirect()->back();
    	}

        if ($order->status == 1 ) {

            if (is_null($request->delivered_date)) {

                alert()->error("Something Wrong! Delivered Date Can't be Empty!")->persistent("Close!");

                return redirect()->back();
            }
            else{

                $order->status = 2;

                $order->delivered_date = $request->delivered_date;

                $order->save();

                alert()->success('Successfully Changed');

                return redirect()->back();

            }

        }elseif ($order->status == 2 || $order->status == 3) {

            if (is_null($request->delivered_date) && is_null($request->employee)) {

                alert()->error("Something Wrong! Delivered Date and Delivery Person Can't be Empty!")->persistent("Close!");

                return redirect()->back();
            }
            else{

                $total = 0;

                $order->status = 4;

                $order->employee_id = $request->employee;

                $order->delivered_date = $request->delivered_date;

                $order->save();

                $customer = Customer::find($order->customer_id);

                if ($customer->customer_level == 1) {

                    foreach ($order->counting_unit as $unit) {

                        $total += ($unit->pivot->quantity * $unit->normal_sale_price);
                    }

                }
                elseif ($customer->customer_level == 2) {

                    foreach ($order->counting_unit as $unit) {

                        $total += ($unit->pivot->quantity * $unit->whole_sale_price);
                    }
                }
                else {

                    foreach ($order->counting_unit as $unit) {

                        $total += ($unit->pivot->quantity * $unit->order_price);
                    }
                }

                $voucher = Voucher::create([
                    'sale_by' => $user->id,
                    'total_price' =>  $total,
                    'total_quantity' => $order->total_quantity,
                    'voucher_date' => $request->delivered_date,
                    'type' => 2,
                    'status' => 0,
                    'order_id' => $order->id,
                ]);

                $voucher->voucher_code = "VOU-".date('dmY')."-".sprintf("%04s", $voucher->id);

                $voucher->save();


                 if ($customer->customer_level == 1) {

                    foreach ($order->counting_unit as $unit) {

                        $voucher->counting_unit()->attach($unit->id, ['quantity' => $unit->pivot->quantity,'price' => $unit->normal_sale_price]);

                        $counting_unit = CountingUnit::find($unit->id);

                        $balance_qty = ($counting_unit->current_quantity - $unit->pivot->quantity);

                        $counting_unit->current_quantity = $balance_qty;

                        $counting_unit->save();

                    }

                }
                elseif ($customer->customer_level == 2) {

                    foreach ($order->counting_unit as $unit) {

                        $voucher->counting_unit()->attach($unit->id, ['quantity' => $unit->pivot->quantity,'price' => $unit->whole_sale_price]);

                        $counting_unit = CountingUnit::find($unit->id);

                        $balance_qty = ($counting_unit->current_quantity - $unit->pivot->quantity);

                        $counting_unit->current_quantity = $balance_qty;

                        $counting_unit->save();

                    }
                }
                else {

                    foreach ($order->counting_unit as $unit) {

                        $voucher->counting_unit()->attach($unit->id, ['quantity' => $unit->pivot->quantity,'price' => $unit->order_price]);

                        $counting_unit = CountingUnit::find($unit->id);

                        $balance_qty = ($counting_unit->current_quantity - $unit->pivot->quantity);

                        $counting_unit->current_quantity = $balance_qty;

                        $counting_unit->save();

                    }
                }

                alert()->success('Successfully Changed');

                return view('Order.order_voucher', compact('voucher','order'));
            }

        }else{

            alert()->error('Something Wrong! Order is Delivered!');

            return redirect()->back();
        }
    }

    protected function getOrderHistoryPage(){

        $from_date = date("Y-m-d");
        $to_date = date("Y-m-d");
        $voucher_lists = Voucher::where('type', 2)->whereBetween('voucher_date',[$from_date,$to_date])->get();

        $customer_lists = array();

        foreach($voucher_lists as $voucher){
            $customer_name = Order::find($voucher->order_id)->name;
            $customer_lists[$voucher->id] = $customer_name;
        }

        return view('Order.order_history_page', compact('voucher_lists','customer_lists'));
    }

    public function validateData($request)
    {
        return  Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',
        ]);
    }

    protected function searchOrderVoucherHistory(Request $request){

        // $validator = $this->validateData($request);

        if ($this->validateData($request)->fails()) {

            alert()->error('Something Wrong!');

            return redirect()->back();
        }

        $voucher_lists = Voucher::where('type', 2)->whereBetween('voucher_date', [$request->from, $request->to])->get();

        $customer_lists = array();

        foreach($voucher_lists as $voucher){
            $customer_name = Order::find($voucher->order_id)->name;
            $customer_lists[$voucher->id] = $customer_name;
        }

        return view('Order.order_history_page', compact('voucher_lists','customer_lists'));
    }

    protected function getVoucherDetails(Request $request, $id){

        try {

            $voucher = Voucher::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Order Not Found!")->persistent("Close!");

            return redirect()->back();
        }

        return view('Order.order_voucher', compact('voucher'));
    }
    protected function getArrivedOrders(Request $request){
        $role= $request->session()->get('user')->role;
// arrived order is for preorders ,
    //$role = "Owner";

        $cdate = new DateTime('Asia/Yangon');

        $current_month = $cdate->format('m');

        $current_month_year = $cdate->format('Y');

        $start = new Carbon('first day of this month');

        $start_date = $start->format('Y-m-d');

        $current_Date = $cdate->format('Y-m-d');

        $mkt_staffs = User::where('role','Marketing')->get();

        if($role=="Marketing"){
            $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
            $fb_pages = $request->session()->get('user')->employee->fbpages;
        }
        else{   //owner
            $fb_Ids= Fbpage::get()->pluck('id')->toArray();
            $fb_pages =Fbpage::all();
        }
        $voucher_lists =Voucher::where('page_id',$fb_Ids)->whereMonth('order_date',$current_month)->whereYear('order_date',$current_month_year)->where('order_type',1)->orderBy('id','desc')->with('fbpage')->with('items')->whereHas('items',function($item){
            return $item->where('purchase_id','!=',0)->OrwhereIn('status',[2,1,5]);
        })->get();
        // $voucher_lists = Voucher::where('order_type',1)->take(2)->get();
        $purchase_lists = DB::table('item_purchase')->get();
        // dd($voucher_lists);
        // return $voucher_lists;
        $radio = 0;
        return view('DeliveryOrder.arrived_orders',compact('voucher_lists','purchase_lists','fb_pages','start_date','current_Date','mkt_staffs','radio'));
    }

    protected function getCanceledOrders(Request $request){
        $role= $request->session()->get('user')->role;
// arrived order is for preorders ,
    //$role ="Owner";

        $cdate = new DateTime('Asia/Yangon');

        $current_month = $cdate->format('m');

        $current_month_year = $cdate->format('Y');

        $start = new Carbon('first day of this month');

        $start_date = $start->format('Y-m-d');

        $current_Date = $cdate->format('Y-m-d');

        $mkt_staffs = User::where('role','Marketing')->get();

        if($role=="Marketing"){
            $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
            $fb_pages = $request->session()->get('user')->employee->fbpages;
        }
        else{   //owner
            $fb_Ids= Fbpage::get()->pluck('id')->toArray();
            $fb_pages =Fbpage::all();
        }


        $cancel_lists = CanceledOrder::whereMonth('date',$current_month)->whereYear('date',$current_month_year)->orderBy('id','desc')->get();


        $cancel_orderids = array();
        foreach($cancel_lists as $cancel_order){
            array_push($cancel_orderids,$cancel_order->order_id);
        }

        $voucher_lists =Voucher::whereIn('id',$cancel_orderids)->with('items')->get();

        $purchase_lists = DB::table('item_purchase')->get();
        return view('DeliveryOrder.canceled_orders',compact('voucher_lists','purchase_lists','fb_pages','start_date','current_Date','mkt_staffs'));
    }

    protected function getReturnedOrders(Request $request){
        $role= $request->session()->get('user')->role;
// arrived order is for preorders ,
    //$role = 'Owner';

        $cdate = new DateTime('Asia/Yangon');

        $current_month = $cdate->format('m');

        $current_month_year = $cdate->format('Y');

        $start = new Carbon('first day of this month');

        $start_date = $start->format('Y-m-d');

        $current_Date = $cdate->format('Y-m-d');

        $mkt_staffs = User::where('role','Marketing')->get();



         $return_order = new stdClass();
        $return_order->total_delivery_expense = 0;
        $return_order->total_collect_amount = 0;


        $return_vouchers = Voucher::with('fbpage')->with('deliveryorders')->whereHas('deliveryorders',function($deliveryorder){
            $cdate = new DateTime('Asia/Yangon');
            return $deliveryorder->where('status',1)->whereMonth('deliveredorder_voucher.date',$cdate->format('m'))->whereYear('deliveredorder_voucher.date',$cdate->format('Y'));
        })->with('delivery')->get();


        $return_vouchers_count = count($return_vouchers);

        foreach($return_vouchers as $voucher){
            $return_order->total_delivery_expense += $voucher->delivery_expense;
            $return_order->total_collect_amount += $voucher->collect_amount;
        }


        return view('Order.return_orders',compact('return_vouchers','return_order','return_vouchers_count','start_date','current_Date'));
    }


    protected function arrivedItems(Request $request){
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'arrived_item_ids' => 'required',
        ]);
        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $arrived_item_ids = json_decode($request->arrived_item_ids);

        $total_qty = 0;

        $date = new DateTime('Asia/Yangon');

        $current_Date = $date->format('Y-m-d');

        $today_date = $date;

        $user_code = $request->session()->get('user')->id;

        try {

            foreach($arrived_item_ids as $item){
                $voucher_item = explode("-", $item);
                $item_qty =$voucher_item[2];
                $total_qty += $item_qty;
            }

            $purchase = Arriveditems::create([
                'total_quantity' => $total_qty,
                'arrived_date' => $today_date,
                'arrived_by' => $user_code,
            ]);

            foreach($arrived_item_ids as $item){
                $voucher_item = explode("-", $item);
                $voucher_id =$voucher_item[0];
                $item_id =$voucher_item[1];
                $voucher = Voucher::find($voucher_id);
                $voucher->items()->updateExistingPivot($item_id, array('status' => 2, 'arriveditem_id'=>$purchase->id), false);

                $preOdrArrived = 0;
                $preOdrOutofstock = 0;
                foreach($voucher->items as $item){
                    if($item->status == 'Arrived' || $item->status == 'Out of stock' ){
                        $preOdrArrived +=1;
                    }
                     if($item->status == 'Out of stock'){
                        $preOdrOutofstock +=1;
                    }
                }
                 if($preOdrArrived==count($voucher->items)){
                    $voucher->status = 2 ;
                    $voucher->status_change_date = $current_Date;
                }
                else if($preOdrOutofstock==count($voucher->items)){
                    $voucher->status = 5 ;
                    $voucher->status_change_date = $current_Date;
                }

                $voucher->save();
            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect()->back();

        // return redirect()->route('arrived_list');
    }
    protected function outofstockItems(Request $request){

        $validator = Validator::make($request->all(), [
            'outofstock_item_ids' => 'required',
        ]);
        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $outofstock_item_ids = json_decode($request->outofstock_item_ids);

        $date = new DateTime('Asia/Yangon');

        $current_Date = $date->format('Y-m-d');

        try {

            foreach($outofstock_item_ids as $item){
                $voucher_item = explode("-", $item);
                $voucher_id =$voucher_item[0];
                $item_id =$voucher_item[1];
                $voucher = Voucher::find($voucher_id);
                $voucher->items()->updateExistingPivot($item_id, array('status' => 5), false);
                $outofstock_item = DB::table('item_voucher')->where('item_id',$item_id)->first();
                $item_price = $outofstock_item->price;
                $outofstock_amount = $item_price * $outofstock_item->quantity;
                $voucher->item_charges = $voucher->item_charges - $outofstock_amount;
                $voucher->total_charges = $voucher->total_charges - $outofstock_amount;
                $voucher->collect_amount = $voucher->collect_amount - $outofstock_amount;

                $preOdrArrived = 0;
                $preOdrOutofstock = 0;
                foreach($voucher->items as $item){
                    if($item->status == 'Arrived' || $item->status == 'Out of stock' ){
                        $preOdrArrived +=1;
                    }
                     if($item->status == 'Out of stock'){
                        $preOdrOutofstock +=1;
                    }
                }
                 if($preOdrArrived==count($voucher->items) && $preOdrOutofstock!=count($voucher->items)){
                    $voucher->status = 2 ;
                    $voucher->status_change_date = $current_Date;

                }
                else if($preOdrArrived==count($voucher->items)  && $preOdrOutofstock==count($voucher->items)){
                    $voucher->status = 5 ;
                    $voucher->status_change_date = $current_Date;

                }
                else if($preOdrArrived!=count($voucher->items)  && $preOdrOutofstock==count($voucher->items)){
                    $voucher->status = 5 ;
                    $voucher->status_change_date = $current_Date;

                }
                $voucher->save();
            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect()->route('purchase_list');
    }
    protected function getPendingOrders(Request $request){
        $role= $request->session()->get('user')->role;
        //$role = "Owner";

        $cdate = new DateTime('Asia/Yangon');

        $current_month = $cdate->format('m');

        $current_month_year = $cdate->format('Y');

        $start = new Carbon('first day of this month');

        $start_date = $start->format('Y-m-d');

        $current_Date = $cdate->format('Y-m-d');

        $mkt_staffs = User::where('role','Marketing')->get();

        if($role=="Marketing"){
            $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
            $fb_pages = $request->session()->get('user')->employee->fbpages;
        }
        else{   //owner
            $fb_Ids= Fbpage::get()->pluck('id')->toArray();
            $fb_pages =Fbpage::all();
        }
//2-arrived -4-pack,5 -outof stock
        $voucher_lists =Voucher::where('page_id',$fb_Ids)->whereMonth('order_date',$current_month)->whereYear('order_date',$current_month_year)->where('order_type',1)->orderBy('id','desc')->with('fbpage')->with('items')->whereIn('status',[2,4])->get();

        $deliveries = Delivery::all();
        $purchase_lists = DB::table('item_purchase')->get();
        $radio = 0;
        return view('DeliveryOrder.pending_orders',compact('voucher_lists','purchase_lists','deliveries','current_Date','start_date','fb_pages','mkt_staffs','radio'));
    }
    public function getOrders(Request $request)
    {
        // 'instockOrPreorder':instockOrPreorder,
        // 'fb_page':fb_page,
        // 'current_Date':current_Date
        // dd($request->all());
        $order_type = $request->order_type;
        $date_type = $request->date_type;
        $date_column = 'order_date';
        $role= $request->session()->get('user')->role;
        //$role = 'Owner';

        if($role=="Marketing"){
            if($request->fb_page == 0){   //all
                $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();

            }else{
                $fb_Ids = array($request->fb_page);
            }
            // $fb_pages = $request->session()->get('user')->employee->fbpages;    // view
        }
        else{
            if($request->mkt_staff == 0 && $request->fb_page == 0){   //all
                $fb_Ids= Fbpage::all()->pluck('id')->toArray();
            }elseif($request->mkt_staff != 0 && $request->fb_page == 0){

                $fb_Ids = Fbpage::where('employee_id',$request->mkt_staff)->get()->pluck('id')->toArray();

            }else{
                $fb_Ids = array($request->fb_page);
            }
            // $fb_pages =Fbpage::all();      //  view

        }

        if($date_type == 1){
            $date_column = 'status_change_date';
        }

        if($order_type == 1){     //pending orders
            $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereNotNull($date_column)->whereBetween($date_column,[$request->from,$request->to])->where('order_type',$order_type)->orderBy('id','desc')->whereIn('status',[2,4])->with('fbpage')->with('items')->get();
            // dd($voucher_lists);
        }
        elseif($order_type == 2){   // payment-complete
            $voucher_lists =Voucher::orderBy('id','desc')->whereIn('page_id',$fb_Ids)->whereNotNull($date_column)->whereBetween($date_column,[$request->from,$request->to])->where('order_type',$order_type)->where('prepaid_clear_flash',0)->whereIn('status',[2,4])->with('fbpage')->with('items')->get();
            // $voucher_lists =Voucher::where('page_id',$fb_Ids)->whereDate('order_date',$request->current_Date)->where('order_type',$order_type)->orderBy('id','desc')->where('prepaid_clear_flash',0)->orwhere('payment_type','!=',1)->where('status',[2,4])->with('items')->get();
        }
        elseif($order_type == 3){   // payment-incomplete //payment_type-1-prepaid full
             $voucher_lists =Voucher::orderBy('id','desc')->whereIn('page_id',$fb_Ids)->whereNotNull($date_column)->whereBetween($date_column,[$request->from,$request->to])->where('order_type',$order_type)->where('prepaid_clear_flash',1)->whereIn('status',[2,4])->with('fbpage')->with('items')->get();
        }
        elseif($order_type == 4){     //all orders
//0-ordersave,2-arrived ,3-deliver-4-pack,5 -outof stock
            $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereNotNull($date_column)->whereBetween($date_column,[$request->from,$request->to])->where('order_type',$order_type)->orderBy('id','desc')->whereIn('status',[0,1,2,3,4,5,6,7])->with('fbpage')->with('items')->get();
        }

        // $cdate = new DateTime('Asia/Yangon');

        // $current_month = $cdate->format('m');

        // $current_month_year = $cdate->format('Y');

        // $start = new Carbon('first day of this month');

        // $start_date = $start->format('Y-m-d');

        // $current_Date = $cdate->format('Y-m-d');

        // $mkt_staffs = User::where('role','Marketing')->get();

        // $deliveries = Delivery::all();
        // $purchase_lists = DB::table('item_purchase')->get();
        // $radio = $request->data_type;
        // // dd($voucher_lists);
        // return view('DeliveryOrder.pending_orders',compact('voucher_lists','purchase_lists','deliveries','current_Date','start_date','fb_pages','mkt_staffs','radio'));

        return response()->json([
            'order_lists' => $voucher_lists,
            'order_type' => $order_type,
        ]);
    }

    protected function getAllOrdersForReview(Request $request){


        if($request->mkt_staff == 0 && $request->fb_page == 0){   //all
                $fb_Ids= Fbpage::all()->pluck('id')->toArray();
            }elseif($request->mkt_staff != 0 && $request->fb_page == 0){

                $fb_Ids = Fbpage::where('employee_id',$request->mkt_staff)->get()->pluck('id')->toArray();

            }else{
                $fb_Ids = array($request->fb_page);
            }

            $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type',$request->order_type)->orderBy('id','desc')->whereIn('status',[0,1,2,3,4,5,6,7])->with('items')->get();


                $delivered_voucher_lists = Voucher::with('delivery')->with('deliveryorders')->whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type',$request->order_type)->orderBy('id','desc')->where('status',3)->with('items')->get();

                $returned_voucher_lists = Voucher::with('delivery')->with('deliveryorders')->whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type',$request->order_type)->orderBy('id','desc')->where('status',6)->with('items')->get();

                 $outofstock_voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type',$request->order_type)->orderBy('id','desc')->whereIn('status',[0,1,2,3,4,5,6,7])->with('items')->whereHas('items',function($item){
            return $item->where('status',5);
        })->get();


            return response()->json( ["voucher_lists"=>$voucher_lists, "delivered_voucher_lists" => $delivered_voucher_lists,
            "returned_voucher_lists" => $returned_voucher_lists,
            "outofstock_voucher_lists" => $outofstock_voucher_lists]);

    }


    protected function deliveredOrders(Request $request){

        $validator = Validator::make($request->all(), [
            'delivered_order_ids' => 'required',
            'deliverId' => 'required',
            'deliverDate' => 'required',
            'remark' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status'=> 0,
                'voucher_lists' => null
            ]);
        }

        $delivered_order_ids = json_decode($request->delivered_order_ids);

         $date = new DateTime('Asia/Yangon');

        $current_Date = $date->format('Y-m-d');

        try {
            $total_order_qty = 0;
            $total_collect_amount=0;   //collectamount
            $total_delivery_expense = 0;
            $total_received_amount = 0;   //collect-deliveryexpense
            foreach($delivered_order_ids as $voucherId){
                $voucher = Voucher::find($voucherId);
                $total_collect_amount += $voucher->collect_amount;
                $total_delivery_expense += $voucher->delivery_expense;
                $total_received_amount += $voucher->collect_amount - $voucher->delivery_expense;
                $total_order_qty += $voucher->total_quantity;
                $voucher->status = 3 ;//delivered status
                $voucher->status_change_date = $current_Date;
                $voucher->save();
                //for total_sale_count
                foreach($voucher->items as $item){
                    $origin_item = Item::find($item->id);
                    $origin_item->total_sale_qty +=  $item->pivot->quantity;
                    $origin_item->save();
                }
            }
            $delivered_order = Deliveredorder::create([
                'delivery_id' => $request->deliverId,
                'date' =>  $request->deliverDate,
                'remark' => $request->remark,
                'total_order_qty' => $total_order_qty,
                'total_collect_amount' => $total_collect_amount,
                'total_received_amount' => $total_received_amount,
                'total_delivery_expense' => $total_delivery_expense,
            ]);

            $delivered_order->vouchers()->attach($delivered_order_ids);

        } catch (\Exception $e) {

            return response()->json([
                'status'=> 0,
                'voucher_lists' => null
            ]);
        }

        $voucher_lists =Voucher::orderBy('id','desc')->where('prepaid_clear_flash',0)->where('status',2)->orwhere('status',4)->with('items')->get();

        return response()->json([
            'status'=> 1,
            'voucher_lists' => $voucher_lists
        ]);
    }

    protected function canceledOrders(Request $request){

        $validator = Validator::make($request->all(), [
            'canceled_order_ids' => 'required',
            'cancelDate' => 'required',
            'cancelRemark' => 'required',
            'adminCode' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {

            if($request->type == 1){
            return response()->json([
                'status'=> 1,
                'voucher_lists' => null
            ]);
            }else{
                alert()->error('Require Input Field');
                return back();
            }
        }

        if($request->adminCode != "ADMINBRL2022")
        {
            if($request->type == 1){
            return response()->json([
                'status'=> 2,
                'voucher_lists' => null
            ]);
            }else{
                //alert()->error('Wrong Admin Code');
                return back()->withMessage('Wrong Admin Code');
            }
        }

        if($request->type == 1){
        $canceled_order_ids = json_decode($request->canceled_order_ids);
        }else if($request->type == 2){
            $canceled_order_ids = array($request->canceled_order_ids);
        }

         $date = new DateTime('Asia/Yangon');

        $current_Date = $date->format('Y-m-d');

        try {
              //collect-deliveryexpense
            foreach($canceled_order_ids as $orderId){
                $voucher = Voucher::find($orderId);
                $voucher->status = 8;
                $voucher->status_change_date = $current_Date;
                $voucher->save();
                $canceled_order = Canceledorder::create([
                'order_id' => $orderId,
                'remark' => $request->cancelRemark,
                'date' =>  $request->cancelDate,
            ]);
                if($request->type == 2){
                    $deliverorder_voucher = DB::table('deliveredorder_voucher')->where('deliveredorder_id',$request->delivery_order_id)->where('voucher_id',$orderId);
        $deliverorder_voucher_update = $deliverorder_voucher->update(['status' => 2,'date'=> $request->cancelDate,'return_remark'=>$request->cancelRemark]);
                }
            }

        } catch (\Exception $e) {
            if($request->type == 1){
            return response()->json([
                'status'=> 3,
                'voucher_lists' => null
            ]);
            }else{
                alert()->error('Error in cancelling order!');
                return back();
            }
        }

        $voucher_lists =Voucher::orderBy('id','desc')->whereBetween('order_date',[$current_Date,$current_Date])->where('prepaid_clear_flash',1)->where('status',2)->orwhere('status',4)->with('fbpage')->with('items')->get();

        if($request->type == 1){
        return response()->json([
            'status'=> 4,
            'voucher_lists' => $voucher_lists
        ]);
        }else{
            alert()->success('Order Cancel Success!');
            return back();
        }
    }

    protected function redeliverOrder(Request $request){

        $validator = Validator::make($request->all(), [
            'delivered_order_id' => 'required',
            'deliverId' => 'required',
            'deliverDate' => 'required',
            'remark' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status'=> 0,
                'message' => 'Error in Validation'
            ]);
        }

        $delivered_order_id = json_decode($request->delivered_order_id);

        try {
            $total_order_qty = 0;
            $total_collect_amount=0;   //collectamount
            $total_delivery_expense = 0;
            $total_received_amount = 0;   //collect-deliveryexpense

                $voucher = Voucher::find($delivered_order_id);
                $total_collect_amount += $voucher->collect_amount;
                $total_delivery_expense += $voucher->delivery_expense;
                $total_received_amount += $voucher->collect_amount - $voucher->delivery_expense;
                $total_order_qty += $voucher->total_quantity;
                $voucher->status = 3 ;     //delivered status
                $voucher->save();
                //for total_sale_count
                // foreach($voucher->items as $item){
                //     $origin_item = Item::find($item->id);
                //     $origin_item->total_sale_qty +=  $item->pivot->quantity;
                //     $origin_item->save();
                // }

            $delivered_order = Deliveredorder::create([
                'delivery_id' => $request->deliverId,
                'date' =>  $request->deliverDate,
                'remark' => $request->remark,
                'total_order_qty' => $total_order_qty,
                'total_collect_amount' => $total_collect_amount,
                'total_received_amount' => $total_received_amount,
                'total_delivery_expense' => $total_delivery_expense,
            ]);

            $delivered_order->vouchers()->attach($delivered_order_id);

        } catch (\Exception $e) {

            return response()->json([
                'status'=> 0,
                'message' => 'Error in ReDelivery'
            ]);
        }

        // $voucher_lists =Voucher::orderBy('id','desc')->where('prepaid_clear_flash',0)->where('status',2)->orwhere('status',4)->with('items')->get();

        return response()->json([
            'status'=> 1,
            'message' => 'Successfully ReDelivered!'
        ]);
    }

    protected function deliveryordersLists(Request $request){

        $date = new DateTime('Asia/Yangon');

        $from_date = $date->format('Y-m-d');
        $to_date = $date->format('Y-m-d');

        $delivery_lists = Deliveredorder::where('date',$from_date)->get()->groupBy('delivery_id');


        $deliveryListArr = [];
        foreach($delivery_lists as $list){


            $delivery =[];
            foreach($list as $key=>$listvoucher){

        $vouchers = Deliveredorder::where('date',$from_date)->where('id',$listvoucher->id)->get();
        $voucher_count = 0;
        foreach($vouchers as $voucher){
            $voucher_count +=count($voucher->vouchers);
        }

                $delivery['key'] = $key;
                $delivery['delivery_id'] = $listvoucher->delivery_id;
                $delivery['delivery'] = $listvoucher->delivery;
                $delivery['date'] = $listvoucher->date;
                if(isset($delivery['total_order_qty'])){
                    $delivery['total_order_qty'] += $listvoucher->total_order_qty;
                }
                else{
                    $delivery['total_order_qty'] = $listvoucher->total_order_qty;
                }
                if(isset($delivery['total_order_count'])){
                    $delivery['total_order_count'] == $voucher_count;
                }
                else{
                    $delivery['total_order_count'] = $voucher_count;
                }
                if(isset($delivery['total_collect_amount'])){
                    $delivery['total_collect_amount'] += $listvoucher->total_collect_amount;
                }
                else{
                    $delivery['total_collect_amount'] = $listvoucher->total_collect_amount;
                }
                if(isset($delivery['total_delivery_expense'])){
                    $delivery['total_delivery_expense'] += $listvoucher->total_delivery_expense;
                }
                else{
                    $delivery['total_delivery_expense'] = $listvoucher->total_delivery_expense;
                }
            }
            array_push($deliveryListArr,$delivery);
        }
        return view('DeliveryOrder.delivered_order_lists', compact('deliveryListArr','from_date','to_date'));
    }
    protected function deliveryordersListsByDate(Request $request){

        $from_date = $request->from;
        $to_date = $request->to;
        $delivery_lists = Deliveredorder::whereBetween('date',[$from_date,$to_date])->get()->groupBy('delivery_id');


        $deliveryListArr = [];
        foreach($delivery_lists as $list){
            $delivery =[];
            foreach($list as $key=>$listvoucher){

                $vouchers = Deliveredorder::whereBetween('date',[$from_date,$to_date])->where('id',$listvoucher->id)->get();
                $voucher_count = 0;
                foreach($vouchers as $voucher){
                    $voucher_count +=count($voucher->vouchers);
                }

                $delivery['key'] = $key;
                $delivery['delivery_id'] = $listvoucher->delivery_id;
                $delivery['delivery'] = $listvoucher->delivery;
                $delivery['date'] = $listvoucher->date;
                if(isset($delivery['total_order_qty'])){
                    $delivery['total_order_qty'] += $listvoucher->total_order_qty;
                }

                else{
                    $delivery['total_order_qty'] = $listvoucher->total_order_qty;
                }
                if(isset($delivery['total_order_count'])){
                    $delivery['total_order_count'] += $voucher_count;
                }
                else{
                    $delivery['total_order_count'] = $voucher_count;
                }
                if(isset($delivery['total_collect_amount'])){
                    $delivery['total_collect_amount'] += $listvoucher->total_collect_amount;
                }
                else{
                    $delivery['total_collect_amount'] = $listvoucher->total_collect_amount;
                }
                if(isset($delivery['total_delivery_expense'])){
                    $delivery['total_delivery_expense'] += $listvoucher->total_delivery_expense;
                }
                else{
                    $delivery['total_delivery_expense'] = $listvoucher->total_delivery_expense;
                }
            }
            array_push($deliveryListArr,$delivery);
        }
        return view('DeliveryOrder.delivered_order_lists', compact('deliveryListArr','from_date','to_date'));
    }

    protected function deliveredOrderHistoryExport(Request $request,$from,$to,$id){
        return $this->excel->download(new DeliveredOrdersHistoryExport($from,$to,$id),'delivered_order_history.xlsx');
    }

    public function getItemsOrderType(Request $request)
    {

        $items = Item::with('category')->where('item_type',$request->order_type)->with('sub_category')->get();

        // return response()->json([
        //     'items'=>$items
        // ]);

        return response()->json($items);

    }
    public function addDeliveryName(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'delivery_name' => 'required',
        ]);
        if ($validator->fails()) {

            return response()->json([
                'status'=> 0,
                'delivery_lists' => null
            ]);
        }

        $delivery = Delivery::create([
            'name'=> $request->delivery_name
        ]);

        $delivery_lists = Delivery::all();

        return response()->json([
            'status'=> 1,
            'delivery_lists' => $delivery_lists
        ]);
    }

    protected function delivery_order_details(Request $request){

        $validator = Validator::make($request->all(), [
            'delivery_id' => 'required',
            'from' => 'required',  //date
        ]);
        if ($validator->fails()) {

            alert()->error('Fill all basic fields! ');
            return back();
        }

        // "delivery_id" => "1"
        // "from" => "2022-03-31"
        // "to" => "2022-04-08"
        // "total_order_qty" => "3"
        // "total_collect_amount" => "0"
        // "total_delivery_expense" => "4500"
        // "delivery" => "Shwe Ngar Mg"
        $delivery_order = new stdClass();
        $delivery_order->delivery = $request->delivery;
        //$delivery_order->total_delivery_expense = $request->total_delivery_expense;
        //$delivery_order->total_collect_amount = $request->total_collect_amount;
        $delivery_order->total_order_qty = $request->total_order_qty;
        $delivery_order->delivery_id = $request->delivery_id;
        $delivery_order->from = $request->from;
        $delivery_order->to = $request->to;
        $delivery_order->id = $request->delivery_id;
        $delivery_order->total_order_count = $request->total_order_count;


        $delivery_orders = Deliveredorder::whereBetween('date',[$request->from,$request->to])->with('delivery')->where('delivery_id',$request->delivery_id)->withCount('vouchers')->get();

        $delivery_order_lists = [];
        $total_collect_amount = 0;
        $total_delivery_expense = 0;
        foreach($delivery_orders as $de){
            $vouchers = $de->vouchers()->wherePivot('status',0)->with('fbpage')->get();
            foreach($vouchers as $voucher){
                array_push($delivery_order_lists,$voucher);
                $total_collect_amount += $voucher->collect_amount;
                $total_delivery_expense += $voucher->delivery_expense;
            }
        }
         $delivery_order->total_collect_amount = $total_collect_amount;
        $delivery_order->total_delivery_expense = $total_delivery_expense;


        $return_order = new stdClass();
        $return_order->total_delivery_expense = 0;
        $return_order->total_collect_amount = 0;
        $delivery_return_lists = [];
        foreach($delivery_orders as $de){
            $return_vouchers = $de->vouchers()->wherePivot('status',1)->with('fbpage')->get();
            foreach($return_vouchers as $vou){
                $return_order->total_delivery_expense += $vou->delivery_expense;
                $return_order->total_collect_amount += $vou->collect_amount;
                array_push($delivery_return_lists,$vou);
            }
        }
        $delivery_order_lists_count = count($delivery_order_lists);

        $delivery_return_lists_count = count($delivery_return_lists);

        $deliveries = Delivery::all();

        return view('DeliveryOrder.delivery_order_detail', compact('delivery_order','delivery_order_lists','delivery_order_lists_count','return_order','delivery_return_lists','delivery_return_lists_count','deliveries'));
    }

    protected function returnOrderLists(Request $request){
        $validator = Validator::make($request->all(), [
            'from' => 'required',
            'to' => 'required',  //date
        ]);
        if ($validator->fails()) {

            alert()->error('Fill all basic fields! ');
            return back();
        }



        $return_vouchers = Voucher::with('fbpage')->with('deliveryorders')->whereHas('deliveryorders',function($deliveryorder){
           global $request;
           $from = $request->from;
           $to = $request->to;
            return $deliveryorder->where('status',1)->whereBetween('deliveredorder_voucher.date',[$from,$to]);
        })->with('delivery')->get();

        return response()->json($return_vouchers);

    }


    protected function orderListsOrderType(Request $request)
    {

        $role= $request->session()->get('user')->role;
        //$role = 'Owner';

        if($role=="Marketing"){
            if($request->fb_page == 0){   //all
                $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();

            }else{
                $fb_Ids = $request->fb_page;
            }
            $voucher_lists =Voucher::where('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->with('items.purchases')->get();

        }
        else{
            if($request->mkt_staff == 0 && $request->fb_page == 0){   //all
                $fb_Ids= Fbpage::all()->pluck('id')->toArray();
                $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->with('items.purchases')->get();

            }elseif($request->mkt_staff != 0 && $request->fb_page == 0){
                //$mkt_staff= User::findOrFail($request->mkt_staff);
                $fb_Ids = Fbpage::where('employee_id',$request->mkt_staff)->get()->pluck('id')->toArray();
                $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->with('items.purchases')->get();

            }else{
                $fb_Ids = $request->fb_page;
                $voucher_lists =Voucher::where('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->with('items.purchases')->get();
            }

        }
        // dd($voucher_lists->toArray());
        // $date = new DateTime('Asia/Yangon');

        // $current_Date = $date->format('Y-m-d');

        // $current_month = $date->format('m');

        // $current_month_year = $date->format('Y');


        // $mkt_staffs = User::where('role','Marketing')->get();

        // $role= $request->session()->get('user')->role;
        // //$role="Owner";

        // if($role=="Marketing"){
        //     $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();

        //     $fb_pages = $request->session()->get('user')->employee->fbpages;
        // }
        // else{

        //     $fb_pages =Fbpage::all();
        // }
        // $purchase_lists = DB::table('item_purchase')->get();

        // $search_sales = 0;

        // return "Hello world!";
        // dd($voucher_lists);
        // return view('Sale.sale_history_page',compact('search_sales','voucher_lists','purchase_lists','current_Date','fb_pages','mkt_staffs'));
        // $voucher_lists =Voucher::where('order_type',$request->order_type)->orderBy('id','desc')->with('items')->with('items.purchases')->get();
        return response()->json($voucher_lists);

    }

    public function searchCanceledOrders(Request $request)
    {
        $role= $request->session()->get('user')->role;
        //$role = 'Owner';

        $cancel_lists = CanceledOrder::whereBetween('date',[$request->from,$request->to])->orderBy('id','desc')->get();

                $cancel_orderids = array();
            foreach($cancel_lists as $cancel_order){
                array_push($cancel_orderids,$cancel_order->order_id);
            }

            if($request->mkt_staff == 0 && $request->fb_page == 0){   //all
                $fb_Ids= Fbpage::all()->pluck('id')->toArray();

                $voucher_lists =Voucher::whereIn('id',$cancel_orderids)->whereIn('page_id',$fb_Ids)->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->get();

            }elseif($request->mkt_staff != 0 && $request->fb_page == 0){
                //$mkt_staff= User::findOrFail($request->mkt_staff);
                $fb_Ids = Fbpage::where('employee_id',$request->mkt_staff)->get()->pluck('id')->toArray();
                $voucher_lists =Voucher::whereIn('id',$cancel_orderids)->whereIn('page_id',$fb_Ids)->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->get();

            }else{
                $fb_Ids = $request->fb_page;
                $voucher_lists =Voucher::whereIn('id',$cancel_orderids)->where('page_id',$fb_Ids)->where('order_type', $request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->get();
            }

            $cdate = new DateTime('Asia/Yangon');

            $current_month = $cdate->format('m');

            $current_month_year = $cdate->format('Y');

            $start = new Carbon('first day of this month');

            $start_date = $start->format('Y-m-d');

            $current_Date = $cdate->format('Y-m-d');

            $mkt_staffs = User::where('role','Marketing')->get();

            if($role=="Marketing"){

                $fb_pages = $request->session()->get('user')->employee->fbpages;
            }
            else{   //owner

                $fb_pages =Fbpage::all();
            }

            $purchase_lists = DB::table('item_purchase')->get();
            return view('DeliveryOrder.canceled_orders',compact('voucher_lists','purchase_lists','fb_pages','start_date','current_Date','mkt_staffs'));

        // return response()->json($voucher_lists);
    }

    public function arrivedOrderLists(Request $request)
    {
        // dd($request->all());
        $radio = $request->data_type;
        // dd($radio);
        $role= $request->session()->get('user')->role;
        //$role = "Owner";

        if($role=="Marketing"){
            if($request->fb_page == 0){   //all
                $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();

            }else{
                $fb_Ids = array($request->fb_page);
            }
        }
        else{
            if($request->mkt_staff == 0 && $request->fb_page == 0){   //all
                $fb_Ids= Fbpage::all()->pluck('id')->toArray();
            }elseif($request->mkt_staff != 0 && $request->fb_page == 0){

                $fb_Ids = Fbpage::where('employee_id',$request->mkt_staff)->get()->pluck('id')->toArray();

            }else{
                $fb_Ids = array($request->fb_page);
            }

        }

        if($request->date_type == 0){
        $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereBetween('order_date',[$request->from,$request->to])->where('order_type',$request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->whereHas('items',function($item){
            return $item->where('purchase_id','!=',0)->OrwhereIn('status',[1,2,5]);
        })->get();

        }else{
            $voucher_lists =Voucher::whereIn('page_id',$fb_Ids)->whereNotNull('status_change_date')->whereBetween('status_change_date',[$request->from,$request->to])->where('order_type',$request->order_type)->orderBy('id','desc')->with('fbpage')->with('items')->whereHas('items',function($item){
            return $item->where('purchase_id','!=',0)->OrwhereIn('status',[1,2,5]);
            })->get();
        }
        return response()->json($voucher_lists);

        // $cdate = new DateTime('Asia/Yangon');

        // $current_month = $cdate->format('m');

        // $current_month_year = $cdate->format('Y');

        // $start = new Carbon('first day of this month');

        // $start_date = $start->format('Y-m-d');

        // $current_Date = $cdate->format('Y-m-d');

        // $mkt_staffs = User::where('role','Marketing')->get();

        // if($role=="Marketing"){
        //     $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
        //     $fb_pages = $request->session()->get('user')->employee->fbpages;
        // }
        // else{   //owner
        //     $fb_Ids= Fbpage::get()->pluck('id')->toArray();
        //     $fb_pages =Fbpage::all();
        // }
        // // $voucher_lists = Voucher::where('order_type',1)->take(2)->get();
        // $purchase_lists = DB::table('item_purchase')->get();
        // // dd($voucher_lists);
        // // return $voucher_lists;
        // return view('DeliveryOrder.arrived_orders',compact('voucher_lists','purchase_lists','fb_pages','start_date','current_Date','mkt_staffs','radio'));
    }


    public function get_delivery_order_details(Request $request)
    {
     return redirect()->route('deliveryordersLists');

    }
    protected function packedOrders(Request $request){
        $validator = Validator::make($request->all(), [
            'packed_order_ids' => 'required',
        ]);
        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $packed_order_ids = json_decode($request->packed_order_ids);

        $date = new DateTime('Asia/Yangon');

        $current_Date = $date->format('Y-m-d');
        try {

            foreach($packed_order_ids as $voucher_id){
                $voucher = Voucher::find($voucher_id);

                foreach($voucher->items as $item){
                    $current_stock = $item->stock;  //4
                    $reserve_stock = $item->reserve_qty;
                    $adjust_current_stock = $current_stock - $item->pivot->quantity;
                    $adjust_reserve_stock = $reserve_stock - $item->pivot->quantity;
                    if($adjust_current_stock <0){
                        alert()->error('Stock မရှိပါ.');

                        return redirect()->back();
                    }
                    if($adjust_reserve_stock <0){
                        alert()->error('Stock မရှိပါ.');

                        return redirect()->back();
                    }
                    $item->stock = $adjust_current_stock;
                    $item->reserve_qty = $adjust_reserve_stock;
                    $item->save();

                    if($item->pivot->status==3){
                        $item->pivot->status= 4;
                        $item->pivot->save();
                    }
                }
                // $voucher->items()->updateExistingPivot($item_id, array('status' => 5), false);

                $voucher->status = 4 ;   //packed
                $voucher->status_change_date = $current_Date;

                $voucher->save();
            }
        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return back();
    }
    public function addReserveQty(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required',
            'reserve_qty' => 'required',
        ]);
        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }
        try{

            $item = Item::findOrfail($request->item_id);
            $new_reserve_qty = $item->reserve_qty + $request->reserve_qty;
            if($new_reserve_qty > $item->stock){
                alert()->error('Reserve Qty greater than current stock.');

                return redirect()->back();
            }else {
                $item->reserve_qty = $new_reserve_qty;
                $item->save();
            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Reserve Qty Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return back();

    }
}
