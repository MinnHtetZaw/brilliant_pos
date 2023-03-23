<?php

namespace App\Http\Controllers\Web;

use App\BankAccount;
use App\From;
use App\Item;
use App\User;
use DateTime;
use App\Order;
use Exception;
use App\Expense;
use App\Voucher;
use App\Category;
use App\Customer;
use App\Employee;
use App\Purchase;
use App\StockPurchase;
use App\Supplier;
use App\PayCredit;
use Carbon\Carbon;
use App\FixedAsset;
use App\Stockcount;
use App\Itemrequest;
use App\CountingUnit;
use App\SalesCustomer;
use App\ShareholderList;
use App\SupplierPayCredit;
use App\Capitaltransaction;
use App\Deliveredorder;
use App\Fbpage;
use App\GeneralInformation;
use App\SupplierCreditList;
use App\Imports\ItemsImport;
use Illuminate\Http\Request;
use App\SaleCustomerCreditlist;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\SaleCustomerList;
use App\Transaction;

class AdminController extends Controller {

    protected function getAdminDashboard(){

	   return view('Admin.admin_panel');
	}

	protected function expenseList(request $request){

	    $expenses = Expense::all();

	    return view('Admin.expense', compact('expenses'));
	}

	protected function storeExpense(request $request){

	       $validator = Validator::make($request->all(), [
            "type" => "required",
            "title" => "required",
            "description" => "required",
            "amount" => "required",
            "profit_loss_flag" => "required",

        ]);

        if($validator->fails()){

            alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

            return redirect()->back();
        }

        $item = Expense::create([
                'type' => $request->type,
                'period' => $request->period,
                'date' => $request->date,
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'profit_loss_flag' => $request->profit_loss_flag,
        ]);

        return redirect()->back();
	}

    protected function page_list(){

        // $employee = Employee::all();
        $pages = Fbpage::with('employee')->with('employee.user')->get();

         $users = User::where('role','Marketing')->get();
		return view('Admin.page_list',compact('users','pages'));
	}

    protected function delete_page($id)
	{
        // dd($id);
        Fbpage::find($id)->delete();
        alert()->success('Deleted Success!!!');
        return redirect()->back();
	}

    protected function update_page(Request $request)
	{
        // dd($request->page_id);
        $update_page = Fbpage::find($request->page_id);

        return response()->json($update_page);
	}

    protected function store_page(Request $request){
        $validator = Validator::make($request->all(), [
            "page_code" => "required",
            "page_name" => "required",
            "employee_id" => "required",
        ]);

        if($validator->fails()){

            alert()->error('အချက်အလက် များ မှားယွင်း နေပါသည်။');

            return redirect()->back();
        }
        if ($request->hasfile('logo')) {
			$image = $request->file('logo');

			$name = $image->getClientOriginalName();

			$photo_path = 'image/logo/'.$name;

			$image->move('public/image/logo', $name);
		}
		else{

			$photo_path = "image/logo/default.jpg";

		}
        Fbpage::create([
           'name' => $request->page_name,
           'code' => $request->page_code,
           'logo'=> $photo_path,
           'employee_id' => $request->employee_id,
        ]);
        alert()->success('Added Successfully!!!!');
        return redirect()->back();

    }

    protected function store_update_page(Request $request){

        $update_page = Fbpage::find($request->page_id);
        // dd($update_page);
        $update_page->name = $request->page_name;
        $update_page->code = $request->page_code;
        $update_page->employee_id = $request->employee_id;
        if ($request->hasfile('logo')) {
			$image = $request->file('logo');

			$name = $image->getClientOriginalName();

			$photo_path = 'image/logo/'.$name;

			$image->move('public/image/logo', $name);

            $update_page->logo = $photo_path;

		}
        $update_page->save();
        alert()->success('Updated Page Successfully!!');
        return redirect()->back();
    }
    
    protected function getInStockPurchaseHistory(Request $request){

        $purchase_lists = StockPurchase::all();

        return view('Purchase.instock_purchase_lists', compact('purchase_lists'));
    }
    
    protected function createInStockPurchaseHistory(){

        
        $items = Item::where("item_type",2)->get();
        $item_ids=[];
        //$counting_units=[];
        foreach ($items as $item){
            array_push($item_ids,$item->id);
        }
        
        $categories = Category::all();

        $supplier = Supplier::all();
        
         $last_voucher = StockPurchase::count();
        if($last_voucher != null){
            $purchase_number =  "PRN-" .date('y') . sprintf("%02s", (intval(date('m')) + 1)) . sprintf("%02s", ($last_voucher+ 1));
        }else{
            $purchase_number =  "PRN-" .date('y') . sprintf("%02s", (intval(date('m')) + 1)) .sprintf("%02s", 1);
        }
        
        return view('Purchase.create_instock_purchase', compact('items','categories','supplier','purchase_number'));
    }
    
    protected function storeInStockPurchaseHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'purchase_number' => 'required',
            'purchase_date' => 'required',
            'purchase_remark' => 'required',
            'supp_name' => 'required',
            'unit' => 'required',
            'price' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->id;

        $unit = $request->unit;

        $price = $request->price;

        $qty = $request->qty;
        
       // $type = $request->type;

        $total_qty = 0;

        $total_price = 0;

        $psub_total = 0;

        // foreach($price as $p){
        //     foreach($qty as $q){
        //         $psub_total = $p * $q;
        //         $total_price += $psub_total;
        //     }
        // }
        
        for($count = 0; $count < count($unit); $count++){
            $psub_total = $price[$count] * $qty[$count];
            $total_price += $psub_total;
        }

        foreach ($qty as $q) {

            $total_qty += $q;
        }
        $supplier = Supplier::find($request->supp_name);
        if($request->pay_method == 1)
        {

        $supplier->credit_amount +=  $request->credit_amount;
        $supplier->save();
        }
        try {

            $purchase = StockPurchase::create([
                'purchase_number' => $request->purchase_number,
                'supplier_name' => $supplier->name,
                'supplier_id' => $request->supp_name,
                'total_quantity' => $total_qty,
                'total_price' => $total_price,
                'purchase_date' => $request->purchase_date,
                'purchase_remark' => $request->purchase_remark,
                'purchase_by' => $user_code,
                'credit_amount' => $request->credit_amount,
            ]);

            if($request->pay_method == 1)
            {

                $supplier_credit = SupplierCreditList::create([
                    'supplier_id' => $request->supp_name,
                    'purchase_id' => $purchase->id,
                    'credit_amount' => $request->credit_amount,
                ]);
            }


            for($count = 0; $count < count($unit); $count++){

                
                $purchase->item()->attach($unit[$count], ['quantity' => $qty[$count], 'price' => $price[$count]]);

                 $item = Item::find($unit[$count]);
                 
                //$stockcount = Stockcount::where('from_id',1)->where('counting_unit_id',$unit[$count])->first();

                $balance_qty = ($item->stock + $qty[$count]);

                // $stockcount->stock_qty = $balance_qty;

                // $stockcount->save();
                 $item->stock = $balance_qty;

                 $item->save();
            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Purchase Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect()->route('instock_purchase_list');
    }
    
    protected function getInStockPurchaseHistoryDetails($id){

        try {

            $purchase = StockPurchase::findOrFail($id);
            $items = DB::table('item_stock_purchase')->where('stock_purchase_id',$id)->get();
            $purchase_items = [];
            foreach($items as $item){
                $it = Item::findOrfail($item->item_id);
                $purchase_stock = DB::table('item_stock_purchase')->where('stock_purchase_id',$id)->where('item_id',$item->item_id)->first();
                $it->purchase_stock = $purchase_stock->quantity;
                array_push($purchase_items,$it);
            }
        } catch (\Exception $e) {

            alert()->error('Something Wrong! Purchase Cannot be Found.');

            return redirect()->back();
        }

        return view('Purchase.instock_purchase_details', compact('purchase','purchase_items'));

    }

	protected function getEmployeeList(){

        $employee = Employee::all();

        $froms = From::all();
		return view('Admin.employee_list', compact('employee','froms'));
	}


    protected function storeSalesCustomer(Request $request){
            $sales_customer = SalesCustomer::create([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'credit_amount' => $request->credit_amount,

                ]);
                $last_row= DB::table('sales_customers')->orderBy('id', 'DESC')->first();
              // $last_row=SalesCustomer::last();
              //dd($last_row);
                Session::flash('data',$last_row);




        return response()->json([
                "success" => 1,
                "message" => "Customer is successfully added",
                "last_row"=>$last_row,

            ]);


    }

    public function shopnameEdit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shopID' => 'required',
            'shopname' =>'required'
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }
        try {
            $from = From::findOrfail($request->shopID);
        } catch (\Exception $e) {
            alert()->error("Cannot Find shop");

            return redirect()->back();
        }

            $from->name = $request->shopname;
            $from->save();

            alert()->success("Successfully Update");

            return redirect()->back();
        dd($request->all());
    }
    protected function getSalesCustomerList(){
        $salescustomer = SalesCustomer::all();
        return response()->json($salescustomer);
    }

    protected function getSalesCustomerWithID(Request $request){

        $salescustomerwID = SalesCustomer::findOrFail($request->customer_id);

        $cust_credit = SaleCustomerCreditlist::where('sales_customer_id',$request->customer_id)->first();

        return response()->json([
            'sale_credit' => $cust_credit,

            'sale_cust' => $salescustomerwID]);
        }
    public function employeeupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'email' => 'required|unique:App\User,email',
            'email' => 'required|email',
            'phone' => 'required',
            'employee_code'=> 'required'
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }
        try {
            $employee = Employee::findOrfail($request->employee_id);
            $employee->phone = $request->phone;
            $employee->save();

            $employee->user->user_code = $request->employee_code;
            $employee->user->email = $request->email;
            $employee->user->name = $request->name;
            if($request->enable_access){
            $give_access = 1;
            $employee->user->enable_access = $give_access;
            }
            if($request->password){
               
                $employee->user->password = bcrypt($request->password);
            }
            $employee->user->save();

        } catch (\Exception $e) {
            
            alert()->error("Fail when updating the employee");

            return redirect()->back();
        }

        alert()->success("Successfully Update");

        return redirect()->back();
        
    }

    public function purchaseDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'purchase_id' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }


        // try {

        $purchase =Purchase::findOrfail($request->purchase_id);

        $purchase_units= $purchase->counting_unit;

        foreach($purchase_units as $unit){

            $current_stock= Stockcount::where("counting_unit_id",$unit->id)->where('from_id',1)->first();

            $balance_qty = $current_stock->stock_qty - $unit->pivot->quantity;
            if($balance_qty <0) {

            alert()->error("Stock ပြန်နုတ်ရန် မလုံလောက်ပါ..");

            return redirect()->back();
        }
            $current_stock->stock_qty = $balance_qty;

            $current_stock->save();

            $counting_units_delete= DB::table('counting_unit_purchase')->where('counting_unit_id', $unit->id)->where('purchase_id',$purchase->id)->delete();




        }
            // $purchase->counting_unit()->delete();


            $delete_credit = SupplierCreditList::where('purchase_id',$purchase->id)->first();
            $delete_credit->delete();
            $purchase->delete();
        // } catch (Exception $e) {

            // alert()->error("ဖျက်မရပါ..");

            // return redirect()->back();
        // }

        alert()->success("Successfully Deleted");

        return redirect()->route('purchase_list');

    }
  protected function storeEmployee(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|unique:App\User,email',
            'password' => 'required',
            'phone' => 'required',
            'role' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Hash::make($request->password),
                'role' => $request->role,
                'prohibition_flag' => 1,
                'photo_path' => "user.jpg",
                'from_id'=> 0,
            ]);

            $user->user_code = "SHW-".sprintf('%03s', $user->id);

            $user->save();

            $employee = Employee::create([
                'phone' => $request->phone,
                'user_id' => $user->id,
            ]);

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When Creating Emplyee.');

            return redirect()->back();
        }

        alert()->success('Successfully Added');

        return redirect()->route('employee_list');
    }

    protected function getEmployeeDetails($id){

        try {

            $employee = Employee::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Employee Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        return view('Admin.employee_details', compact('employee'));
    }

	protected function getCustomerList(){

        $customer_lists = Customer::all();


		return view('Admin.customer_list', compact('customer_lists'));
    }

    // public function getSalesCustomerCreditList($id){
    //     $sale_customer_lists = SalesCustomer::all();
    //     return view('Sale.sale_customer_lists',compact('sale_customer_lists'));
    // }


    public function show_sale_customer_credit_list(){
        $sale_customer = SalesCustomer::where('credit_amount','<>',0)->get();
        $credit_list = SaleCustomerCreditList::all();
        // dd($credit_list);
        return view('Sale.sale_customer_lists',compact('sale_customer','credit_list'));

    }

    public function show_supplier_credit_lists()
    {

        $supplier_credit_list = Supplier::all();
        return view('Admin.supplier_credit_list',compact('supplier_credit_list'));
    }

    public function add_supplier(Request $request){
        $suppliers = Supplier::all();
        return view('Admin.add_supplier',compact('suppliers'));
    }

    public function store_supplier(Request $request){
            // dd($request->all());
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone_number' => 'required',
            ]);

            $suppliers = Supplier::create([
                 'name' => $request->name,
                 'phone_number' => $request->phone_number,
            ]);

        alert()->success('successfully stored Supplier Data');
        return back();
    }

    public function supplier_credit($id)
    {

        $supplier = Supplier::find($id);
        $creditlist = SupplierCreditList::all();
        $credit = SupplierCreditList::where('supplier_id',$id)->get();
       $paypay = SupplierPayCredit::where('supplier_id',$id)->get();
    //    dd($credit);
       return view('Admin.supplier_credit_detail',compact('credit','supplier','paypay','creditlist'));
    }

    public function store_eachPaidSupplier(Request $request)
    {
        // dd($request->all());
        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'email' => 'required|unique:App\User,email',
        //     'password' => 'required',
        //     'phone' => 'required',
        //     'role' => 'required',
        // ]);

        // if ($validator->fails()) {

        //     alert()->error("Something Wrong! Validation Error");

        //     return redirect()->back();
        // }
        $sale_customer = Supplier::find($request->supid);

        $credit_list = SupplierCreditlist::where('purchase_id',$request->purchase_id)->where('supplier_id',$request->supid)->first();

        $pay = $credit_list->credit_amount - $request->payamt;
        $credit_list->credit_amount = $pay;
        $sale_customer->credit_amount = $pay;
        $sale_customer->save();
        $credit_list->paid_status = 0;
        $credit_list->save();
        if($pay == 0)
        {
            $credit_list->paid_status =1;
            $credit_list->save();
            $pay_credit = SupplierPayCredit::create([
                'supplier_id' => $request->supid,
                'purchase_id' => $request->purchase_id,
                'left_amount' => $pay,
                'description' => $request->dest,
                'voucher_id'=>$request->vou_id,
                'pay_amount' => $request->payamt,
                'pay_date' => $request->paydate,
                'paid_status' => 1,
            ]);
        }
        elseif($pay != 0)
        {
            $credit_list->paid_status =0;
            $credit_list->save();
            $pay_credit = SupplierPayCredit::create([
                'supplier_id' => $request->supid,
                'purchase_id' => $request->purchase_id,
                'left_amount' => $pay,
                'description' => $request->dest,
                'voucher_id'=>$request->vou_id,
                'pay_amount' => $request->payamt,
                'pay_date' => $request->paydate,
                'paid_status' => 0,
            ]);
        }
        // dd($pay);
        if($pay == 0){
            $paycre = SupplierPayCredit::where('purchase_id',$request->purchase_id)->get();

            foreach($paycre as $paycree)
            {
            $paycree->paid_status = 1;

            $paycree->save();
            }
        }

        $supplier = SalesCustomer::find($request->supid);
        $creditlist = SupplierCreditlist::all();
        $credit = SupplierCreditlist::where('supplier_id',$request->supid)->get();
        $paypay = SupplierPayCredit::where('supplier_id',$request->supid)->get();

        return back()->with(compact('paypay','supplier','creditlist','credit'));

    }

    public function store_allSupplierPaid(Request $request,$id)
    {
        $SID = Supplier::find($id);

        if($SID->credit_amount == 0){
            $SID->status = 1;
            $SID->save();
        }
        $purchase = SupplierCreditlist::where('supplier_id',$id)->where('paid_status',0)->get();
        $pay_amount = $request->repay;
        $supplier = Supplier::find($id);
        if($supplier->credit_amount == 0)
        {
            $supplier->status = 1;
            $supplier->save();
        }
        $saletotal = $supplier->credit_amount - $pay_amount;
        $supplier->credit_amount = $saletotal;
        $supplier->save();
        $variable = 0;
        foreach($purchase as $purchases)
        {
         $repaycreditvoucher = SupplierPayCredit::where('purchase_id',$purchases->purchase_id)->first();
        $paypay = PayCredit::where('sale_customer_id',$id)->first();
        $last = $purchases->credit_amount - $pay_amount;

        if($last > 0)
        {
            $last = $last;
        }
        else{
            $last = $last * -1;
        }
        if($purchases->credit_amount <= $pay_amount)
        {


            if($repaycreditvoucher == null)
            {
                // dd("hello");
                if($purchases->credit_amount <= $request->repay)
                {
                    $begin_amt = $purchases->credit_amount;
                }
                else{
                    $begin_amt = $pay_amount;
                }
                $purchases->credit_amount = 0;
                $purchases->paid_status = 1;
                $purchases->save();

                    $paycredit = SupplierPayCredit::create([
                        'supplier_id' => $id,
                        'left_amount' => 0,
                        'description' => $request->remark,
                        'purchase_id'=>$purchases->purchase_id,

                        'pay_amount' => $begin_amt,
                        'pay_date' => $request->repaydate,
                        'paid_status' => 1,

                         ]);



            }
            else{
                // dd("hello2");
                if($purchases->credit_amount <= $request->repay)
                {
                    $begin_amout = $purchases->credit_amount;
                }
                else{
                    $begin_amtout = $pay_amount;
                }
                $purchases->credit_amount = 0;
                $purchases->paid_status = 1;
                $purchases->save();
                $paycredit = SupplierPayCredit::create([
                    'supplier_id' => $id,
                    'left_amount' => 0,
                    'description' => $request->remark,
                    'purchase_id'=>$purchases->purchase_id,

                    'pay_amount' => $begin_amout,
                    'pay_date' => $request->repaydate,
                    'paid_status' => 1,

                     ]);
        $change_status = SupplierCreditlist::where('purchase_id',$purchases->purchase_id)->first();
        if($change_status->credit_amount == 0)
        {
            // dd("hello0000");
        $paycredd = SupplierPayCredit::where('purchase_id',$change_status->purchase_id)->get();

        foreach($paycredd as $paycreedd)
        {
        $insertone = 1;
        $paycreedd->paid_status = 1;
            // dd($paycreedd->voucher_status);
        $paycreedd->save();
        // dd($paycreedd->voucher_status);

        }
        }

            }



        $pay_amount = $last;

        }


        else
        {
            // dd($purchases->purchase_id);

                $purchases->credit_amount = $last;
            $purchases->paid_status = 0;
            $purchases->save();


            $paycredit = SupplierPayCredit::create([
                'supplier_id' => $id,
                'left_amount' => $last,
                'description' => $request->remark,
                'purchase_id'=>$purchases->purchase_id,
                'pay_amount' => $pay_amount,
                'pay_date' => $request->repaydate,
                'paid_status' => 0,

        ]);

        // dd("end");


        $pay_amount = 0;
        }


        }
        // end foreach
        // $change_status = SaleCustomerCreditlist::where('voucher_id',$voucher->voucher_id)->first();
        // if($change_status->credit_amount == 0)
        // {

        // $paycredd = PayCredit::where('voucher_id',$change_status->voucher_id)->get();

        // foreach($paycredd as $paycreedd)

        // $paycreedd->voucher_status = 1;

        // $paycreedd->save();


        // }


        return back();
    }

    public function getPurchase_Info(Request $request)
    {
        // dd($request->credit_list_id);
        $credit_list = SupplierCreditList::find($request->credit_list_id);
        $purchase = Purchase::find($credit_list->purchase_id);
        // dd($purchase);
        return response()->json([$purchase]);
    }

    public function getsell_end_info(Request $request)
    {
        $asset = FixedAsset::find($request->asset_id);
        if($asset != null)
        {
            $has_asset = 1;
        }
        else
        {
            $has_asset = 2;
        }
        // dd($has_asset);
        return response()->json([
            'flag' => $asset->sell_or_end_flag,
            'has_asset' => $has_asset,
            'asset' => $asset,
        ]);
    }

    public function showFixasset(){
        $fixed_asset = FixedAsset::all();
        $nowdate = new DateTime('Asia/Yangon');
        $realdate = $nowdate->format('Y-m-d');
        $fillyear = [];
        $filldate =[];

        foreach($fixed_asset as $fday)
        {
            // dd($realdate."-------".$fday->future_day);
            if($realdate == $fday->future_day)
            {
                array_push($filldate,$fday->id);
            }
        }
        // dd($filldate);
        foreach($fixed_asset as $fyear)
        {
            if($realdate == $fyear->future_date)
            {
                array_push($fillyear,$fyear->id);
            }
        }


// dd($fillyear);
foreach($filldate as $fdate)
{
    $change_date = FixedAsset::find($fdate);
    $change_date->depriciation_total = $change_date->daily_depriciation +  $change_date->depriciation_total;
    $change_date->current_value = $change_date->current_value - $change_date->daily_depriciation;
    $futureDay=date('Y-m-d', strtotime('+1 day', strtotime($change_date->start_date)));
    $change_date->future_day = $futureDay;
    $change_date->save();
}
foreach($fillyear as $fyear)
{
    $change_all = FixedAsset::find($fyear);
    $change_all->depriciation_total = $change_all->yearly_depriciation +  $change_all->depriciation_total;
    $change_all->current_value = $change_all->current_value - $change_all->yearly_depriciation;
    $change_all->used_years +=1;
    $futureDate=date('Y-m-d', strtotime('+1 year', strtotime($change_all->start_date)));
    $change_all->future_date = $futureDate;
    $change_all->save();
}
$done = FixedAsset::all();
// dd($done);
// dd($change_all);
return view('Admin.fixasset',compact('fixed_asset','done'));
    }

    public function show_capitalPanel()
    {
        $fix_arr =[];
        $sale_credit_arr = [];
        $supp_credit_arr = [];
        $fixed = FixedAsset::all();
        $sale_credit = SalesCustomer::all();
        $supplier_credit = Supplier::all();
        foreach($supplier_credit as $sup)
        {
            array_push($supp_credit_arr,$sup->credit_amount);
        }
        $total_sup_credit = array_sum($supp_credit_arr);
        foreach($sale_credit as $sale_credits)
        {
            array_push($sale_credit_arr,$sale_credits->credit_amount);
        }
        $total_sale_credit = array_sum($sale_credit_arr);
        foreach($fixed as $asset)
        {
            array_push($fix_arr,$asset->current_value);
        }
        $current_asset = array_sum($fix_arr);
        // dd($current_asset);
        $general_info = GeneralInformation::all();
        // dd($general_info);
        $nowdate = new DateTime('Asia/Yangon');
        $realdate = $nowdate->format('Y-m-d');

        foreach($general_info as $general)
        {
            // dd($realdate."-----".$general->future_year);
            if($realdate == $general->future_year)
            {
                // dd($general->start_capital);
                $total_capital = ($general->current_capital) + (($general->reinvest)/100);
                $general->current_capital = $total_capital;
                // dd($general->current_capital);
                $fut_year=date('Y-m-d', strtotime('+1 year', strtotime($general->future_year)));
                $general->future_year = $fut_year;
                $general->save();
            }
        }
// dd("s");
        $general_information = GeneralInformation::all();
        $share_holder = ShareholderList::all();
        $transition = Capitaltransaction::all();
        return view('Admin.capital_panel',compact('transition','share_holder','general_information','current_asset','total_sale_credit','total_sup_credit'));
    }

    public function store_capitalInfo(Request $request)
    {
        // dd($request->name[0]);
        // dd($request->all());

            $current_equity =  ($request->start_capital + $request->cashin + $request->currentasset +$request->sale_credit) - ($request->sup_credit);
            // dd($current_equity);
            // dd(count($request->amount));
        if($request->general_id == null)
        {
            $general_info = GeneralInformation::create([
                'bussiness_name' => $request->buss_name,
                'business_type' => $request->buss_type,
                'total_starting_capital' => $request->start_capital,
                'number_shareholder' => $request->sharer,
                'old_holder' =>  $request->sharer,
                'current_capital' => $request->start_capital,
                'current_fixedasset' => $request->currentasset ,
                'current_cash' => $request->cashin,
                'current_equity' => $current_equity,
                'reinvest_percent' => $request->reinvest,
            ]);

            $future_year=date('Y-m-d', strtotime('+1 year', strtotime($general_info->created_at)));
            $general_info->future_year = $future_year;
            $general_info->save();

            // dd($request->name[0]);
            $length = count($request->name);
            // $i = 0;
                for($i = 0;$i<$length;$i++)
                {

                    $shareholder_store = ShareholderList::create([
                        'general_information_id' => $general_info->id,
                        'name' =>  $request->name[$i],
                        'nrc_passport' =>  $request->nrc[$i],
                        'position' => $request->position[$i],
                        'share_percent' =>  $request->amount[$i],
                        'devident_percent' => $request->divid[$i],
                        'capital_amount' => $request->start_capital,
                    ]);
                }


            // dd("stop");
            alert()->success("Successfully Stored Capital's General Information!");
            return back();
        }
        else if($request->general_id != null)
        {

            $general = GeneralInformation::find($request->general_id);
            $future_year=date('Y-m-d', strtotime('+1 year', strtotime($general->created_at)));
            $general->bussiness_name =  $request->buss_name;
            $general->business_type = $request->buss_type;
            $general->total_starting_capital = $request->start_capital;
            $general->number_shareholder = $request->sharer;
            // $general->old_holder = 2;
            $general->current_capital = $request->start_capital;
            $general->current_fixedasset = $request->currentasset;
            $general->current_cash = $request->cashin;
            $general->current_equity = $current_equity;
            $general->reinvest_percent = $request->reinvest;
            $general->future_year = $future_year;
            $general->save();

            $length = count($request->name);

            if($request->number_shareholder == $general->old_holder)
            {
                // dd("equal");
                for($i = 0;$i<$length;$i++)
                {
                    $shareHolder = ShareholderList::where('id',$request->sharer_id[$i])->first();
                    $shareHolder->general_information_id = $request->general_id;
                    $shareHolder->name = $request->name[$i];
                    $shareHolder->nrc_passport =  $request->nrc[$i];
                    $shareHolder->position = $request->position[$i];
                    $shareHolder->share_percent = $request->amount[$i];
                    $shareHolder->devident_percent = $request->divid[$i];
                    $shareHolder->capital_amount = $request->start_capital;
                    $shareHolder->save();


                }
            }
            elseif($request->number_shareholder != $general->old_holder)
            {
                for($k = 0;$k<count($request->name);$k++)
                {

                    $holder = ShareholderList::find($request->sharer_id[$k]);
                    if($holder != null)
                    {
                        $holder->general_information = $request->general_id;
                        $holder->name = $request->name[$k];
                        $holder->nrc_passport = $request->nrc[$k];
                        $holder->position = $request->position[$k];
                        $holder->share_percent = $request->amount[$k];
                        $holder->devident_percent = $request->divid[$k];
                        $holder->capital_amount = $request->start_capital;
                    }
                    else
                    {
                        $shareholder_store = ShareholderList::create([
                            'general_information_id' => $request->general_id,
                            'name' =>  $request->name[$k],
                            'nrc_passport' =>  $request->nrc[$k],
                            'position' => $request->position[$k],
                            'share_percent' =>  $request->amount[$k],
                            'devident_percent' => $request->divid[$k],
                            'capital_amount' => $request->start_capital,
                        ]);
                    }
                }

                // for($k = 0;$k<count($request->name)-1;$k++)
                // {

                //     $holder = ShareholderList::find($request->sharer_id[$k]);
                //     $holder->delete();
                // }
                // for($k = 0;$k<count($request->name);$k++)
                // {

                //     $shareholder_store = ShareholderList::create([
                //         'general_information_id' => $request->general_id,
                //         'name' =>  $request->name[$k],
                //         'nrc_passport' =>  $request->nrc[$k],
                //         'position' => $request->position[$k],
                //         'share_percent' =>  $request->amount[$k],
                //         'devident_percent' => $request->divid[$k],
                //         'capital_amount' => $request->start_capital,
                //     ]);
                // }
            }

            alert()->success("Successfully Updated Capital's General Information!");
            return back();
        }

    }

    public function addasset(){
        return view('Admin.addasset');
    }

    public function storeAsset(Request $request){
        //  dd($request->all());
         if($request->exist_asset == 1){
             $used_year = $request->used_year;
             $total_dep = $request->depriciation_total;
         }
         elseif($request->exist_asset == 2){
            $used_year = 0;
            $total_dep = 0;
        }


            $futureDate=date('Y-m-d', strtotime('+1 year', strtotime($request->start_date)));
            $futureDay=date('Y-m-d', strtotime('+1 day', strtotime($request->start_date)));
            $daily_dep = $request->year_depriciation/365;


        // if($request->sell)

        // dd( $used_year );
        $asset = FixedAsset::create([
            'name' => $request->asset_name ,
            'type' => $request->type ,
            'description' => $request->asset_description  ,
            'initial_purchase_price' => $request->purchase_initial_price ,
            'salvage_value' => $request->salvage_value ,
            'use_life' => $request->use_life ,
            'yearly_depriciation' => $request->year_depriciation ,
            'existing_flag' => $request->exist_asset ,
            'used_years' => $used_year,
            'depriciation_total' => $total_dep ,
            'current_value' => $request->current_value ,
            'start_date' => $request->start_date ,
            'future_date'=> $futureDate,
            'daily_depriciation' => $daily_dep,
            'future_day' => $futureDay
        ]);
        alert()->success('successfully stored Asset Data');
        return redirect()->route('fixasset');
    }

    public function storeSellEnd(Request $request){
        //  dd($request->all());
        // $request->
        $fixed_asset = FixedAsset::find($request->id);
        // dd($fixed_asset->profit_loss_status);
        if($request->sell_price > $request->current_value){
            $fixed_asset->profit_loss_status = 1;
        }
        elseif($request->sell_price < $request->current_value){
            $fixed_asset->profit_loss_status = 2;
        }
        if($request->exist_asset == 1){
            $fixed_asset->sell_or_end_flag = 1;
        }
        if($request->exist_asset == 2){
            $fixed_asset->sell_or_end_flag = 2;
        }

            $fixed_asset->sell_price = $request->sell_price;
            $fixed_asset->sell_date = $request->sell_date;
            $fixed_asset->profit_loss_value = $request->profit_loss;
            $fixed_asset->end_remark = $request->remark;
            $fixed_asset->end_date = $request->end_date;
            $fixed_asset->save();


            return redirect()->route('fixasset');


    }

    public function store_reinvest_info(Request $request){
        // dd($request->all());
        $general = GeneralInformation::find($request->general_id);
        if($request->reinvest_type == 1 && $request->proof == 3)
        {

            $general->current_capital +=$request->reinvest_amount;
            $general->current_cash -=$request->reinvest_amount;
            $general->save();
            $trans = Capitaltransaction::create([
                'type' => 1,
                'amount' => $request->reinvest_amount,
                'date' => $request->reinvest_date,
                'source' => $request->reinvest_type,

            ]);

            // dd($real_amt);
            alert()->success("Successfully Reinvest Cash Transition !!");
            return back();
        }
        elseif($request->reinvest_type == 2 && $request->proof == 3)
        {


            $general->current_capital +=$request->reinvest_amount;

            $general->save();
            $trans = Capitaltransaction::create([
                'type' => 1,
                'amount' => $request->reinvest_amount,
                'date' => $request->reinvest_date,
                'source' => $request->reinvest_type,
                'remark' => $request->reinvest_remark,
            ]);

            // dd($real_amt);
            alert()->success("Successfully Reinvest Other Transition !!");
            return back();
        }




    }

    public function store_withdraw_info(Request $request){
        // dd($request->all());
        $general = GeneralInformation::find($request->general_id);
        if($request->withdraw_type == 1 && $request->proof == 4)
        {

            $general->current_cash +=$request->withdraw_amount;
            $general->current_capital -=$request->withdraw_amount;
            $general->save();
            $trans = Capitaltransaction::create([
                'type' => 2,
                'amount' => $request->withdraw_amount,
                'date' => $request->withdraw_date,
                'source' => $request->withdraw_type,
                'remark' => $request->withdraw_remark,
            ]);
            alert()->success("Successfully Withdraw Cash Transition !!");
            return back();

        }
        elseif($request->withdraw_type == 2 && $request->proof == 4)
        {

            $general->current_capital -=$request->withdraw_amount;
            $general->save();
            $trans = Capitaltransaction::create([
                'type' => 2,
                'amount' => $request->withdraw_amount,
                'date' => $request->withdraw_date,
                'source' => $request->withdraw_type,
                'remark' => $request->withdraw_remark,
            ]);
            alert()->success("Successfully Withdraw Other Transition !!");
            return back();
        }
    }

    // public function history(){
    //     $voucher_id = SaleCustomerCreditlist::
    //     dd($voucher_id);
    //     return view('Sale.voucher_details',compact('voucher_id'));

    // }
           // public function history(){
               // $voucher_id = SaleCustomerLists::select('voucher_id')->get();
               // dd($voucher_id);
            //    $id=$request->all();
            //    dd($id);
               // return view('Sale.voucher_details',compact('voucher_id'));
               // }

    // function index(){
    //     $sale_customer_lists = SalesCustomer::all();
    //     // dd($sale_customer_lists);

    //     return view('Sale.sale_customer_lists',compact('sale_customer_lists'));
    // }
    public function credit($id){

       // $str = json_encode($sale_customer_id);

       //$credit_id = SalesCustomer::
       $salecustomer = SalesCustomer::find($id);
       $creditlist = SaleCustomerCreditlist::all();
       $credit = SaleCustomerCreditlist::where('sales_customer_id',$id)->get();

    //    dd($credit);
      $paypay = PayCredit::where('sale_customer_id',$id)->get();
        return view('Sale.credit_detail',compact('creditlist','credit','salecustomer','paypay'));



    }

    protected function store_eachPaid(Request $request)
    {
        // dd($request->all());
        $sale_customer = SalesCustomer::find($request->scid);

        $credit_list = SaleCustomerCreditlist::where('voucher_id',$request->vou_id)->where('sales_customer_id',$request->scid)->first();

        $pay = $credit_list->credit_amount - $request->payamt;
        $credit_list->credit_amount = $pay;
        $sale_customer->credit_amount = $pay;
        $sale_customer->save();
        $credit_list->paid_status = 0;
        $credit_list->save();
        if($pay == 0)
        {
            $credit_list->paid_status =1;
            $credit_list->save();
            $pay_credit = PayCredit::create([
                'sale_customer_id' => $request->scid,
                'left_amount' => $pay,
                'description' => $request->dest,
                'voucher_id'=>$request->vou_id,
                'pay_amount' => $request->payamt,
                'pay_date' => $request->paydate,
                'paid_status' => 1,
            ]);
        }
        elseif($pay != 0)
        {
            $credit_list->paid_status =0;
            $credit_list->save();
            $pay_credit = PayCredit::create([
                'sale_customer_id' => $request->scid,
                'left_amount' => $pay,
                'description' => $request->dest,
                'voucher_id'=>$request->vou_id,
                'pay_amount' => $request->payamt,
                'pay_date' => $request->paydate,
                'paid_status' => 0,
            ]);
        }
        // dd($pay);
        if($pay == 0){
            $paycre = PayCredit::where('voucher_id',$request->vou_id)->get();

            foreach($paycre as $paycree)
            {
            $paycree->paid_status = 1;

            $paycree->save();
            }
        }

        $salecustomer = SalesCustomer::find($request->scid);
        $creditlist = SaleCustomerCreditlist::all();
        $credit = SaleCustomerCreditlist::where('sales_customer_id',$request->scid)->get();
        $paypay = PayCredit::where('sale_customer_id',$request->scid)->get();

        return back()->with(compact('paypay','salecustomer','creditlist','credit'));
    }

    protected function store_allPaid(Request $request,$id)
    {
        // dd($request->all());
        $SID = SalesCustomer::find($id);

        if($SID->credit_amount == 0){
            $SID->status = 1;
            $SID->save();
        }
        $vouchers = SaleCustomerCreditlist::where('sales_customer_id',$id)->where('paid_status',0)->get();
        $pay_amount = $request->repay;
        $saleCustomer = SalesCustomer::find($id);
        if($saleCustomer->credit_amount == 0)
        {
            $saleCustomer->status = 1;
            $saleCustomer->save();
        }
        $saletotal = $saleCustomer->credit_amount - $pay_amount;
        $saleCustomer->credit_amount = $saletotal;
        $saleCustomer->save();
        $variable = 0;
        foreach($vouchers as $voucher)
        {
         $repaycreditvoucher = PayCredit::where('voucher_id',$voucher->voucher_id)->first();
        $paypay = PayCredit::where('sale_customer_id',$id)->first();
        $last = $voucher->credit_amount - $pay_amount;

        if($last > 0)
        {
            $last = $last;
        }
        else{
            $last = $last * -1;
        }
        if($voucher->credit_amount <= $pay_amount)
        {


            if($repaycreditvoucher == null)
            {
                // dd("hello");
                if($voucher->credit_amount <= $request->repay)
                {
                    $begin_amt = $voucher->credit_amount;
                }
                else{
                    $begin_amt = $pay_amount;
                }
                $voucher->credit_amount = 0;
                $voucher->paid_status = 1;
                $voucher->save();

                    $paycredit = PayCredit::create([
                        'sale_customer_id' => $id,
                        'left_amount' => 0,
                        'description' => $request->remark,
                        'voucher_id'=>$voucher->voucher_id,

                        'pay_amount' => $begin_amt,
                        'pay_date' => $request->repaydate,
                        'paid_status' => 1,

                         ]);



            }
            else{
                // dd("hello2");
                if($voucher->credit_amount <= $request->repay)
                {
                    $begin_amout = $voucher->credit_amount;
                }
                else{
                    $begin_amtout = $pay_amount;
                }
                $voucher->credit_amount = 0;
                $voucher->paid_status = 1;
                $voucher->save();
                $paycredit = PayCredit::create([
                    'sale_customer_id' => $id,
                    'left_amount' => 0,
                    'description' => $request->remark,
                    'voucher_id'=>$voucher->voucher_id,

                    'pay_amount' => $begin_amout,
                    'pay_date' => $request->repaydate,
                    'paid_status' => 1,

                     ]);
        $change_status = SaleCustomerCreditlist::where('voucher_id',$voucher->voucher_id)->first();
        if($change_status->credit_amount == 0)
        {
            // dd("hello0000");
        $paycredd = PayCredit::where('voucher_id',$change_status->voucher_id)->get();

        foreach($paycredd as $paycreedd)
        {
        $insertone = 1;
        $paycreedd->paid_status = 1;
            // dd($paycreedd->voucher_status);
        $paycreedd->save();
        // dd($paycreedd->voucher_status);

        }
        }

            }



        $pay_amount = $last;

        }


        else
        {
            // dd("djfd");

                $voucher->credit_amount = $last;
            $voucher->paid_status = 0;
            $voucher->save();


            $paycredit = PayCredit::create([
                'sale_customer_id' => $id,
                'left_amount' => $last,
                'description' => $request->remark,
                'voucher_id'=>$voucher->voucher_id,
                'pay_amount' => $pay_amount,
                'pay_date' => $request->repaydate,
                'paid_status' => 0,

        ]);




        $pay_amount = 0;
        }


        }
        // end foreach
        // $change_status = SaleCustomerCreditlist::where('voucher_id',$voucher->voucher_id)->first();
        // if($change_status->credit_amount == 0)
        // {

        // $paycredd = PayCredit::where('voucher_id',$change_status->voucher_id)->get();

        // foreach($paycredd as $paycreedd)

        // $paycreedd->voucher_status = 1;

        // $paycreedd->save();


        // }


        return back();
    }

	protected function storeCustomer(Request $request){

		$validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'level' => 'required',
        ]);

        if ($validator->fails()) {

        	alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $count_cus = count(Customer::all());



        if ($count_cus == 40) {

            alert()->error("Your Customer Count is full!");

            return redirect()->back();

        } else {


                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => \Hash::make($request->password),
                    'role' => "Customer",
                    'prohibition_flag' => 1,
                    'photo_path' => "user.jpg",
                ]);

                $user->user_code = "SHW-CUS-".sprintf('%03s', $user->id);

                $user->save();

                $customer = Customer::create([
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'allow_credit' => $request->allow_credit = "on"?1:0,
                    'customer_level' => $request->level,
                    'user_id' => $user->id,
                ]);



            alert()->success('Successfully Added');

            return redirect()->route('customer_list');
        }
	}

    protected function getCustomerDetails($id){

        try {

            $customer = Customer::findOrFail($id);

        } catch (\Exception $e) {

            alert()->error("Customer Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $order_lists = Order::where('customer_id', $customer->id)->get();

       return view('Admin.customer_details', compact('customer','order_lists'));
    }

    protected function updateCustomer(Request $request, $id){

        $validator = Validator::make($request->all(), [
			'name' => 'required',
			'email' => 'required',
			'phone' => 'required',
			'address' => 'required',
		]);

		if ($validator->fails()) {

			return $this->sendFailResponse("Something Wrong! Validation Error.", "400");
		}

        try {

            $customer = Customer::findOrFail($id);

            $user = User::findOrFail($customer->user_id);

        } catch (\Exception $e) {

            alert()->error("Customer Not Found!")->persistent("Close!");

            return redirect()->back();

        }

        $user->name = $request->name;

        $user->email = $request->email;

        $user->save();

        $customer->phone = $request->phone;

		$customer->address = $request->address;

		$customer->save();

		alert()->success("Successfully Updated Customer!");

		return redirect()->route('customer_list');
    }

    protected function changeCustomerLevel(Request $request){

        try {

            $customer = Customer::findOrFail($request->customer_id);

            $customer->customer_level = $request->level;

            $customer->save();

            alert()->success('Successfully Updated');

            return redirect()->back();

        } catch (\Exception $e) {

            alert()->error("Customer Not Found!")->persistent("Close!");

            return redirect()->back();

        }
    }

    protected function getCustomerInfo(Request $request){

        $customer = Customer::where('id',$request->customer_id)->with('user')->first();

        return response()->json($customer);
    }

    protected function getPurchaseHistory(Request $request){

        $purchase_lists = Purchase::orderBy('id','desc')->get();

        return view('Purchase.purchase_lists', compact('purchase_lists'));
    }

    protected function createPurchaseHistory(){

        $froms=From::find(1);
        // $items = $froms->items()->with('counting_units')->with("counting_units.stockcount")->get();
        $items = Item::with('counting_units')->with("counting_units.stockcount")->get();
        $supplier = Supplier::all();
        return view('Purchase.create_purchase', compact('items','supplier'));
    }

    protected function getPurchaseHistoryDetails($id){

        try {

            $purchase = Purchase::findOrFail($id);
            $items = DB::table('item_voucher')->where('purchase_id',$id)->get();
            $purchase_items = [];
            foreach($items as $item){
                $it = Item::findOrfail($item->item_id);
                $purchase_stock = DB::table('item_voucher')->where('purchase_id',$id)->where('item_id',$item->item_id)->first();
                $it->purchase_stock = $purchase_stock->quantity;
                array_push($purchase_items,$it);
            }
        } catch (\Exception $e) {

            alert()->error('Something Wrong! Purchase Cannot be Found.');

            return redirect()->back();
        }

        return view('Purchase.purchase_details', compact('purchase','purchase_items'));

    }

    protected function storePurchaseHistory(Request $request){
        $validator = Validator::make($request->all(), [
            'supplier_name' => 'required',
            'pur_item_total' => 'required',
            'pur_item_ids' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $purchse_items = json_decode($request->pur_item_ids);

        $total_qty = 0;

        $date = new DateTime('Asia/Yangon');
         
        $current_Date = $date->format('Y-m-d');

        $today_date = $date;

        $user_code = session()->get('user')->id;

        try {

            foreach($purchse_items as $item){
                $voucher_item = explode("-", $item);
                $item_qty =$voucher_item[2]; 
                $total_qty += $item_qty;
            }
            
            $purchase = Purchase::create([
                'supplier_name' => $request->supplier_name,
                'total_quantity' => $total_qty,
                'total_price' => $request->pur_item_total,
                'purchase_date' => $today_date,
                'purchase_by' => $user_code,
            ]);

            foreach($purchse_items as $item){
                $voucher_item = explode("-", $item);
                $voucher_id =$voucher_item[0]; 
                $item_id =$voucher_item[1]; 
                $voucher = Voucher::find($voucher_id);
                $voucher->items()->updateExistingPivot($item_id, array('status' => 1, 'purchase_id'=>$purchase->id), false);

                $preOdrPurchasing = 0;
    
                foreach($voucher->items as $item){
                    if($item->status == 'Purchasing'){
                        $preOdrPurchasing +=1;
                    }
                }
                if($preOdrPurchasing == count($voucher->items)){
                    $voucher->status = 1 ;
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

    protected function getTotalSalenAndProfit(Request $request){
        return view('Admin.financial_panel');
    }
    
    protected function getMarketingReview(Request $request){
        
         $cdate = new DateTime('Asia/Yangon');
         
         $current_Date = $cdate->format('Y-m-d');
         
         $mkt_staffs = User::where('role','Marketing')->get();
         
         $fb_pages =Fbpage::all();
         
        
        return view('Sale.marketing_review',compact('current_Date','fb_pages','mkt_staffs'));
    }

    protected function getTotalSaleReport(Request $request){

        $type = $request->type;

        $total_instock_sales = 0;
        
        $total_instock_COGS = 0;
        
        $total_preorder_sales = 0;

        $total_gross_profit = 0;
        
        $instock_vouchers_final = [];
        $preorder_vouchers_final = [];

        $delivery_received_amt = 0;
        $delivery_received_vouchers =[];
        $purchase_amt = 0;
        $purchase_lists = [];
        $transaction_amt = 0;
        $transaction_lists = [];
        $total_expense = 0;
        if($type == 1){

            $daily = date('Y-m-d', strtotime($request->value));
            
            $instock_vouchers = Voucher::where('order_type',0)->whereDate('order_date',$daily)->with('fbpage')->get();
            
            $preorder_vouchers = Voucher::where('order_type',1)->whereDate('order_date',$daily)->with('fbpage')->get();
            
            if($instock_vouchers != null){
            foreach($instock_vouchers as $voucher){
                $total_instock_sales += $voucher->total_charges;
                array_push($instock_vouchers_final,$voucher);
                foreach ($voucher->items as $item) {

                    $total_instock_COGS += ($item->purchase_price * $item->pivot->quantity);

                    $total_gross_profit += ($item->pivot->price * $item->pivot->quantity) - ($item->purchase_price * $item->pivot->quantity);
                }
            }
            }
            
            if($preorder_vouchers != null){
            foreach($preorder_vouchers as $voucher){
                $total_preorder_sales += $voucher->total_charges;
                array_push($preorder_vouchers_final,$voucher);
            }
            }
            

            $deliverorders = Deliveredorder::whereDate('date',$daily)->get();
            foreach($deliverorders as $deliverorder){
                foreach($deliverorder->vouchers as $voucher){
                $delivery_received_voucher = Voucher::with('delivery')->with('deliveryorders')->find($voucher->id);
                array_push($delivery_received_vouchers, $delivery_received_voucher);
                }
                $delivery_received_amt += $deliverorder->total_received_amount;
            }

            $daily_purchases = Purchase::whereDate('purchase_date', $daily)->get();
            foreach($daily_purchases as $daily_purchase){
                array_push($purchase_lists,$daily_purchase);
                $purchase_amt += $daily_purchase->total_price;
            }


            $daily_transactions = Transaction::whereDate('tran_date', $daily)->get();
            foreach($daily_transactions as $daily_transaction){
                $transaction = Transaction::with('voucher')->with('voucher.fbpage')->with('bank_account')->find($daily_transaction->id);
                array_push($transaction_lists,$transaction);
                $transaction_amt += $daily_transaction->pay_amount;
            }

            $expenses_daily_amount=0;
            $expenses_weekly_amount=0;
            $expenses_monthly_amount=0;
            $expenses_indate_amount=0;
            $expenses_daily= Expense::where('profit_loss_flag',1)->where('period',1)->get();

            foreach($expenses_daily as $exp_daily){
                $expenses_daily_amount+=$exp_daily->amount;
            }
          
                    $indate= Expense::where('profit_loss_flag',1)->where('type',2)->whereDate('date',$request->value)->get();
            foreach($indate as $exp_indate){
                $expenses_indate_amount+=$exp_indate->amount;
            }
            $expenses_weekly= Expense::where('profit_loss_flag',1)->where('period',2)->get();
            foreach($expenses_weekly as $exp_weekly){
                $expenses_weekly_amount+=ceil($exp_weekly->amount/7);
            }
            $expenses_monthly= Expense::where('profit_loss_flag',1)->where('period',3)->get();
            foreach($expenses_monthly as $exp_monthly){
                $expenses_monthly_amount+=ceil($exp_monthly->amount/30);
            }

            $total_expense= $expenses_daily_amount+ $expenses_weekly_amount + $expenses_monthly_amount+ $expenses_indate_amount; 
        }
        elseif($type == 2){
            $expenses_daily_amount=0;
            $expenses_weekly_amount=0;
            $expenses_monthly_amount=0;
            $expenses_indate_amount=0;
            $week_count = $request->value;

            $start_month = date('Y-m-d',strtotime('first day of this month'));

            if ($week_count == 1) {

               
                $start_date = date('Y-m-d',strtotime('first day of this month'));
                
                $end_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));
             
                
            } elseif ($week_count == 2) {
                
                $start_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));
                
                $end_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));

            } elseif ($week_count == 3) {

                $start_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));
                
                $end_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));

            } else {

                $start_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));
                
                $end_date = date('Y-m-d',strtotime('last day of this month'));
            }
            
            $instock_vouchers = Voucher::where('order_type',0)->whereBetween('order_date',[$start_date,$end_date])->with('fbpage')->get();
            
            $preorder_vouchers = Voucher::where('order_type',1)->whereBetween('order_date',[$start_date,$end_date])->with('fbpage')->get();
            
            if($instock_vouchers != null){
            foreach($instock_vouchers as $voucher){
                $total_instock_sales += $voucher->total_charges;
                array_push($instock_vouchers_final,$voucher);
                foreach ($voucher->items as $item) {

                    $total_instock_COGS += ($item->purchase_price * $item->pivot->quantity);

                    $total_gross_profit += ($item->pivot->price * $item->pivot->quantity) - ($item->purchase_price * $item->pivot->quantity);
                }
            }
            }
            
            if($preorder_vouchers != null){
            foreach($preorder_vouchers as $voucher){
                $total_preorder_sales += $voucher->total_charges;
                array_push($preorder_vouchers_final,$voucher);
            }
            }
            
            $deliverorders = Deliveredorder::whereBetween('date', [$start_date, $end_date])->get();
            
            foreach($deliverorders as $deliverorder){
                foreach($deliverorder->vouchers as $voucher){
                $delivery_received_voucher = Voucher::with('delivery')->with('deliveryorders')->find($voucher->id);
                array_push($delivery_received_vouchers, $delivery_received_voucher);
                }
                $delivery_received_amt += $deliverorder->total_received_amount;
            }

            $daily_purchases = Purchase::whereBetween('purchase_date',[$start_date, $end_date])->get();
            foreach($daily_purchases as $daily_purchase){
                array_push($purchase_lists,$daily_purchase);
                $purchase_amt += $daily_purchase->total_price;
            }


            $daily_transactions = Transaction::whereBetween('tran_date',[$start_date, $end_date])->get();
            foreach($daily_transactions as $daily_transaction){
                $transaction = Transaction::with('voucher')->with('voucher.fbpage')->with('bank_account')->find($daily_transaction->id);
                array_push($transaction_lists,$transaction);
                $transaction_amt += $daily_transaction->pay_amount;
            }

            $indate= Expense::where('profit_loss_flag',1)->where('type',2)->whereBetween('date',[$start_date, $end_date])->get();
            foreach($indate as $exp_indate){
                $expenses_indate_amount+=$exp_indate->amount;
            }
            $expenses_daily= Expense::where('profit_loss_flag',1)->where('period',1)->get();

            foreach($expenses_daily as $exp_daily){
                $expenses_daily_amount+=7*($exp_daily->amount);
            }
            $expenses_weekly= Expense::where('profit_loss_flag',1)->where('period',2)->get();
            foreach($expenses_weekly as $exp_weekly){
                $expenses_weekly_amount+=$exp_weekly->amount;
            }
            $expenses_monthly= Expense::where('profit_loss_flag',1)->where('period',3)->get();
            foreach($expenses_monthly as $exp_monthly){
                $expenses_monthly_amount+=ceil($exp_monthly->amount/4);
            }

            $total_expense= $expenses_daily_amount+ $expenses_weekly_amount + $expenses_monthly_amount+ $expenses_indate_amount;
     
        }
        else{

            $monthly = $request->value;
            
            $instock_vouchers = Voucher::where('order_type',0)->whereMonth('order_date',$monthly)->with('fbpage')->get();
            
             $preorder_vouchers = Voucher::where('order_type',1)->whereMonth('order_date',$monthly)->with('fbpage')->get();
            

            if($instock_vouchers != null){
             foreach($instock_vouchers as $voucher){
                 $total_instock_sales += $voucher->total_charges;
                 array_push($instock_vouchers_final,$voucher);
                 foreach ($voucher->items as $item) {

                     $total_instock_COGS += ($item->purchase_price * $item->pivot->quantity);

                    $total_gross_profit += ($item->pivot->price * $item->pivot->quantity) - ($item->purchase_price * $item->pivot->quantity);
                 }
             }
            }
            
            if($preorder_vouchers != null){
            foreach($preorder_vouchers as $voucher){
                 $total_preorder_sales += $voucher->total_charges;
                array_push($preorder_vouchers_final,$voucher);
            }
            }
            
            $deliverorders = Deliveredorder::whereMonth('date', $monthly)->get();
            
            foreach($deliverorders as $deliverorder){
                foreach($deliverorder->vouchers as $voucher){
                $delivery_received_voucher = Voucher::with('delivery')->with('deliveryorders')->find($voucher->id);
                array_push($delivery_received_vouchers, $delivery_received_voucher);
                }
                $delivery_received_amt += $deliverorder->total_received_amount;
            }


            $daily_purchases = Purchase::whereMonth('purchase_date', $monthly)->get();
            foreach($daily_purchases as $daily_purchase){
                array_push($purchase_lists,$daily_purchase);
                $purchase_amt += $daily_purchase->total_price;
            }


            $daily_transactions = Transaction::whereMonth('tran_date', $monthly)->get();
            foreach($daily_transactions as $daily_transaction){
                $transaction = Transaction::with('voucher')->with('voucher.fbpage')->with('bank_account')->find($daily_transaction->id);
                array_push($transaction_lists,$transaction);
                $transaction_amt += $daily_transaction->pay_amount;
            }
            
            $expenses_daily_amount=0;
            $expenses_weekly_amount=0;
            $expenses_monthly_amount=0;
            $expenses_indate_amount=0;

            $expenses_daily= Expense::where('profit_loss_flag',1)->where('period',1)->get();

            foreach($expenses_daily as $exp_daily){
                $expenses_daily_amount+=30*($exp_daily->amount);
            }
            $indate= Expense::where('profit_loss_flag',1)->where('type',2)->whereMonth('date',$request->value)->get();
            foreach($indate as $exp_indate){
                $expenses_indate_amount+=$exp_indate->amount;
            }
            $expenses_weekly= Expense::where('profit_loss_flag',1)->where('period',2)->get();
            foreach($expenses_weekly as $exp_weekly){
                $expenses_weekly_amount+=4*($exp_weekly->amount);
            }
            $expenses_monthly= Expense::where('profit_loss_flag',1)->where('period',3)->get();
            foreach($expenses_monthly as $exp_monthly){
                $expenses_monthly_amount+=$exp_monthly->amount;
            }

            $total_expense= $expenses_daily_amount+ $expenses_weekly_amount + $expenses_monthly_amount+ $expenses_indate_amount;
        }
        return response()->json([
            "total_sales" => ['total_instock_sales' => $total_instock_sales,
            'total_preorder_sales' => $total_preorder_sales,
            'total_instock_COGS' => $total_instock_COGS,
            'total_gross_profit' => $total_gross_profit,
            'instock_vouchers_final' => $instock_vouchers_final,
            'preorder_vouchers_final' => $preorder_vouchers_final
                ],
            "delivery_received" => ['delivery_received_amt'=>$delivery_received_amt,'delivery_received_vouchers'=> $delivery_received_vouchers],
            "purchases" => ['purchase_amt'=>$purchase_amt,'purchase_lists'=> $purchase_lists],
            "transactions" => ['transaction_amt'=>$transaction_amt,'transaction_lists'=> $transaction_lists],
            "total_expense" => ['total_expense'=>$total_expense,'expenses_daily_amount'=> $expenses_daily_amount,'expenses_weekly_amount'=> $expenses_weekly_amount,'expenses_monthly_amount'=>$expenses_monthly_amount,'expenses_indate_amount'=>$expenses_indate_amount],
        ]);
    }
    
    protected function getTotalSaleReportv2(Request $request){

        $type = $request->type;

        $total_instock_sales = 0;
        
        $total_instock_COGS = 0;
        
        $total_preorder_sales = 0;

        $total_gross_profit = 0;
        
        $instock_vouchers_final = [];
        $preorder_vouchers_final = [];
        
        $delivery_received_amt = 0;
        $delivery_received_vouchers =[];
        $purchase_amt = 0;
        $purchase_lists = [];
        $transaction_amt = 0;
        $transaction_lists = [];
        $total_expense = 0;
        
        $num_days = 0;
        
        if($type == 1){

            $start_daily = date('Y-m-d', strtotime($request->start_value));
            
            $end_daily = date('Y-m-d', strtotime($request->end_value));
            
            $instock_vouchers = Voucher::where('order_type',0)->whereBetween('order_date',[$start_daily,$end_daily])->with('fbpage')->get();
            
            $preorder_vouchers = Voucher::where('order_type',1)->whereBetween('order_date',[$start_daily,$end_daily])->with('fbpage')->get();
            
            if($instock_vouchers != null){
            foreach($instock_vouchers as $voucher){
                $total_instock_sales += $voucher->item_charges;
                array_push($instock_vouchers_final,$voucher);
                foreach ($voucher->items as $item) {

                    $total_instock_COGS += ($item->purchase_price * $item->pivot->quantity);

                    $total_gross_profit += ($item->pivot->price * $item->pivot->quantity) - ($item->purchase_price * $item->pivot->quantity);
                }
            }
            }
            
            if($preorder_vouchers != null){
            foreach($preorder_vouchers as $voucher){
                $total_preorder_sales += $voucher->total_charges;
                array_push($preorder_vouchers_final,$voucher);
            }
            }            

            $deliverorders = Deliveredorder::whereBetween('date',[$start_daily,$end_daily])->get();
            foreach($deliverorders as $deliverorder){
                foreach($deliverorder->vouchers as $voucher){
                $delivery_received_voucher = Voucher::with('delivery')->with('deliveryorders')->find($voucher->id);
                array_push($delivery_received_vouchers, $delivery_received_voucher);
                }
                $delivery_received_amt += $deliverorder->total_received_amount;
            }

            $daily_purchases = Purchase::whereBetween('purchase_date', [$start_daily,$end_daily])->get();
            foreach($daily_purchases as $daily_purchase){
                array_push($purchase_lists,$daily_purchase);
                $purchase_amt += $daily_purchase->total_price;
            }


            $daily_transactions = Transaction::whereBetween('tran_date', [$start_daily,$end_daily])->get();
            foreach($daily_transactions as $daily_transaction){
                $transaction = Transaction::with('voucher')->with('voucher.fbpage')->with('bank_account')->find($daily_transaction->id);
                array_push($transaction_lists,$transaction);
                $transaction_amt += $daily_transaction->pay_amount;
            }
            
            
            $start = strtotime($start_daily);
            $end = strtotime($end_daily);
            if($start != $end){
            $num_days = ceil(abs($end-$start)/86400) + 1;
            }else{
                $num_days = 1;
            }
            

            $expenses_daily_amount=0;
            $expenses_weekly_amount=0;
            $expenses_monthly_amount=0;
            $expenses_indate_amount=0;
            $expenses_daily= Expense::where('profit_loss_flag',1)->where('period',1)->get();

            foreach($expenses_daily as $exp_daily){
                $expenses_daily_amount+=$exp_daily->amount;
            }
          
                    $indate= Expense::where('profit_loss_flag',1)->where('type',2)->whereBetween('date',[$request->start_value,$request->end_value])->get();
            foreach($indate as $exp_indate){
                $expenses_indate_amount+=$exp_indate->amount;
            }
            $expenses_weekly= Expense::where('profit_loss_flag',1)->where('period',2)->get();
            foreach($expenses_weekly as $exp_weekly){
                $expenses_weekly_amount+=ceil($exp_weekly->amount/7) * $num_days;
            }
            $expenses_monthly= Expense::where('profit_loss_flag',1)->where('period',3)->get();
            foreach($expenses_monthly as $exp_monthly){
                $expenses_monthly_amount+=ceil($exp_monthly->amount/30) * $num_days;
            }

            $total_expense= $expenses_daily_amount+ $expenses_weekly_amount + $expenses_monthly_amount+ $expenses_indate_amount; 
        }
        elseif($type == 2){
            $expenses_daily_amount=0;
            $expenses_weekly_amount=0;
            $expenses_monthly_amount=0;
            $expenses_indate_amount=0;
            $week_count = $request->value;

            $start_month = date('Y-m-d',strtotime('first day of this month'));

            if ($week_count == 1) {

               
                $start_date = date('Y-m-d',strtotime('first day of this month'));
                
                $end_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));
             
                
            } elseif ($week_count == 2) {
                
                $start_date = date('Y-m-d', strtotime("+1 week" , strtotime($start_month)));
                
                $end_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));

            } elseif ($week_count == 3) {

                $start_date = date('Y-m-d', strtotime("+2 week" , strtotime($start_month)));
                
                $end_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));

            } else {

                $start_date = date('Y-m-d', strtotime("+3 week" , strtotime($start_month)));
                
                $end_date = date('Y-m-d',strtotime('last day of this month'));
            }
            $deliverorders = Deliveredorder::whereBetween('date', [$start_month, $end_date])->get();
            
            foreach($deliverorders as $deliverorder){
                foreach($deliverorder->vouchers as $voucher){
                $delivery_received_voucher = Voucher::with('delivery')->with('deliveryorders')->find($voucher->id);
                array_push($delivery_received_vouchers, $delivery_received_voucher);
                }
                $delivery_received_amt += $deliverorder->total_received_amount;
            }

            $daily_purchases = Purchase::whereBetween('purchase_date',[$start_month, $end_date])->get();
            foreach($daily_purchases as $daily_purchase){
                array_push($purchase_lists,$daily_purchase);
                $purchase_amt += $daily_purchase->total_price;
            }


            $daily_transactions = Transaction::whereBetween('tran_date',[$start_month, $end_date])->get();
            foreach($daily_transactions as $daily_transaction){
                $transaction = Transaction::with('voucher')->with('voucher.fbpage')->with('bank_account')->find($daily_transaction->id);
                array_push($transaction_lists,$transaction);
                $transaction_amt += $daily_transaction->pay_amount;
            }

            $indate= Expense::where('profit_loss_flag',1)->where('type',2)->whereBetween('date',[$start_month, $end_date])->get();
            foreach($indate as $exp_indate){
                $expenses_indate_amount+=$exp_indate->amount;
            }
            $expenses_daily= Expense::where('profit_loss_flag',1)->where('period',1)->get();

            foreach($expenses_daily as $exp_daily){
                $expenses_daily_amount+=7*($exp_daily->amount);
            }
            $expenses_weekly= Expense::where('profit_loss_flag',1)->where('period',2)->get();
            foreach($expenses_weekly as $exp_weekly){
                $expenses_weekly_amount+=$exp_weekly->amount;
            }
            $expenses_monthly= Expense::where('profit_loss_flag',1)->where('period',3)->get();
            foreach($expenses_monthly as $exp_monthly){
                $expenses_monthly_amount+=ceil($exp_monthly->amount/4);
            }

            $total_expense= $expenses_daily_amount+ $expenses_weekly_amount + $expenses_monthly_amount+ $expenses_indate_amount;
     
        }
        else{

            $monthly = $request->value;
            
            $deliverorders = Deliveredorder::whereMonth('date', $monthly)->get();
            
            foreach($deliverorders as $deliverorder){
                foreach($deliverorder->vouchers as $voucher){
                $delivery_received_voucher = Voucher::with('delivery')->with('deliveryorders')->find($voucher->id);
                array_push($delivery_received_vouchers, $delivery_received_voucher);
                }
                $delivery_received_amt += $deliverorder->total_received_amount;
            }


            $daily_purchases = Purchase::whereMonth('purchase_date', $monthly)->get();
            foreach($daily_purchases as $daily_purchase){
                array_push($purchase_lists,$daily_purchase);
                $purchase_amt += $daily_purchase->total_price;
            }


            $daily_transactions = Transaction::whereMonth('tran_date', $monthly)->get();
            foreach($daily_transactions as $daily_transaction){
                $transaction = Transaction::with('voucher')->with('voucher.fbpage')->with('bank_account')->find($daily_transaction->id);
                array_push($transaction_lists,$transaction);
                $transaction_amt += $daily_transaction->pay_amount;
            }
            
            $expenses_daily_amount=0;
            $expenses_weekly_amount=0;
            $expenses_monthly_amount=0;
            $expenses_indate_amount=0;

            $expenses_daily= Expense::where('profit_loss_flag',1)->where('period',1)->get();

            foreach($expenses_daily as $exp_daily){
                $expenses_daily_amount+=30*($exp_daily->amount);
            }
            $indate= Expense::where('profit_loss_flag',1)->where('type',2)->whereMonth('date',$request->value)->get();
            foreach($indate as $exp_indate){
                $expenses_indate_amount+=$exp_indate->amount;
            }
            $expenses_weekly= Expense::where('profit_loss_flag',1)->where('period',2)->get();
            foreach($expenses_weekly as $exp_weekly){
                $expenses_weekly_amount+=4*($exp_weekly->amount);
            }
            $expenses_monthly= Expense::where('profit_loss_flag',1)->where('period',3)->get();
            foreach($expenses_monthly as $exp_monthly){
                $expenses_monthly_amount+=$exp_monthly->amount;
            }

            $total_expense= $expenses_daily_amount+ $expenses_weekly_amount + $expenses_monthly_amount+ $expenses_indate_amount;
        }
        return response()->json([
            "total_sales" => ['total_instock_sales' => $total_instock_sales,
            'total_preorder_sales' => $total_preorder_sales,
            'total_instock_COGS' => $total_instock_COGS,
            'total_gross_profit' => $total_gross_profit,
            'instock_vouchers_final' => $instock_vouchers_final,
            'preorder_vouchers_final' => $preorder_vouchers_final
                ],
            "delivery_received" => ['delivery_received_amt'=>$delivery_received_amt,'delivery_received_vouchers'=> $delivery_received_vouchers],
            "purchases" => ['purchase_amt'=>$purchase_amt,'purchase_lists'=> $purchase_lists],
            "transactions" => ['transaction_amt'=>$transaction_amt,'transaction_lists'=> $transaction_lists],
            "total_expense" => ['total_expense'=>$total_expense,'expenses_daily_amount'=> $expenses_daily_amount,'expenses_weekly_amount'=> $expenses_weekly_amount,'expenses_monthly_amount'=>$expenses_monthly_amount,'expenses_indate_amount'=>$expenses_indate_amount],
            "num_days" => $num_days,
        ]);
    }

    protected function changeCustomerPassword(Request $request){

    	$validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'new_password' => 'required',
        ]);

        if ($validator->fails()) {

        	return $this->sendFailResponse("Something Wrong! Validation Error.", "400");
        }

        $user = User::find($request->user_id);

        // $current_pw = $request->current_password;

        // if(!\Hash::check($current_pw, $user->password)){

        //     return $this->sendFailResponse("Something Wrong! Password doesn't match.", "400");
        // }

        $has_new_pw = \Hash::make($request->new_password);

        $user->password = $has_new_pw;

        $user->save();

        return response()->json([
                "user_code"=> $user->user_code,
            ]);
    }
    public function getFixedAssets(Request $request)
    {

        return view('Admin.fixedassetlists');
    }

    public function getnewAsset()
    {
        return view('Admin.newasset');
    }
    public function mobileprint(Request $request)
    {
            $mobile_print = Voucher::where('from_id',$request->from_id)->where('is_mobile',1)->where("is_print",1)->with('counting_unit')->with('counting_unit.item')->with('user')->orderBy('id','desc')->first();
            if($mobile_print){
                return response()->json($mobile_print);
            }else{
                return response()->json(null);
            }
    }
    protected function itemrequestlists(Request $request){
       $role= $request->session()->get('user')->role;
       //$role="Owner";
       if($role=="Owner"){
        $request_lists = Itemrequest::orderBy('id','desc')->get();
       }
       else{
        $from_id = $request->session()->get('from');
        $request_lists = Itemrequest::where("from_id",$from_id)->orderBy('id','desc')->get();
       }

        return view('Itemrequest.itemrequestlists', compact('request_lists'));
    }
    protected function getRequestHistoryDetails($id){

        try {

            $itemrequest = Itemrequest::findOrFail($id);

            $froms=From::find(1);
            $items = $froms->items()->with('counting_units')->with("counting_units.stockcount")->get();
        } catch (\Exception $e) {

            alert()->error('Something Wrong! Item Request Cannot be Found.');

            return redirect()->back();
        }

        return view('Itemrequest.requestdetail', compact('itemrequest','items'));

    }
    public function create_itemrequest(Request $request)
    {

        $from_id = $request->session()->get('from');
        $froms=From::find($from_id);
        $items = $froms->items()->with('counting_units')->with("counting_units.stockcount")->get();
        return view('Itemrequest.create_itemrequest', compact('items'));

    }
    protected function store_itemrequest(Request $request){

        $validator = Validator::make($request->all(), [
            'itemrequest_date' => 'required',
            'unit' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) {

            alert()->error("Something Wrong! Validation Error");

            return redirect()->back();
        }

        $user_code = $request->session()->get('user')->id;

        $unit = $request->unit;

        $qty = $request->qty;

        $total_qty = 0;

        foreach ($qty as $q) {

            $total_qty += $q;
        }

        try {
            $itemrequest = itemrequest::create([
                'request_by' => $request->session()->get('user')->id,
                'total_quantity' => $total_qty,
                'date' => $request->itemrequest_date,
                'from_id' => $request->session()->get('from'),
            ]);


            for($count = 0; $count < count($unit); $count++){

                $itemrequest->counting_units()->attach($unit[$count], ['quantity' => $qty[$count]]);

                // $counting_unit = CountingUnit::find($unit[$count]);

                // $balance_qty = ($counting_unit->current_quantity + $qty[$count]);

                // $counting_unit->current_quantity = $balance_qty;

                // $counting_unit->itemrequest_price = $price[$count];

                // $counting_unit->save();

            }

        } catch (\Exception $e) {

            alert()->error('Something Wrong! When itemrequest Store.');

            return redirect()->back();
        }

        alert()->success("Success");

        return redirect()->route('itemrequestlists');
    }
    public function requestitemssend(Request $request)
    {
        $sentqty= $request->sentqty;
        $counting_units= $request->counting_units;
        $itemrequest = Itemrequest::findOrfail($request->itemrequest_id);


        try {

        foreach($itemrequest->counting_units as $unit){
        $key = array_search ($unit->pivot->counting_unit_id, $counting_units);

        $shop_origin = Stockcount::where('counting_unit_id',$unit->pivot->counting_unit_id)->where('from_id',1)->whereNull('deleted_at')->first();

        if($sentqty[$key]<=$shop_origin->stock_qty){
            $itemrequest->counting_units()->updateExistingPivot($unit->pivot->counting_unit_id,['send_quantity'=>$sentqty[$key]]);
            //update origin stock
            // $shop_origin= DB::table('stockcounts')->where('counting_unit_id',$unit->pivot->counting_unit_id)->where('from_id',1)->whereNull('deleted_at')->first();
            // $balance_qty = $shop_origin->stock_qty-$sentqty[$key];


            $balance_qty = $shop_origin->stock_qty-$sentqty[$key];
            $shop_origin->stock_qty = $balance_qty;
            $shop_origin->save();

            //update request shop's stock

            $requestshop= Stockcount::where('counting_unit_id',$unit->pivot->counting_unit_id)->where('from_id',$request->shop_id)->whereNull('deleted_at')->first();
            $balance_qty = $requestshop->stock_qty+$sentqty[$key];
            $requestshop->stock_qty = $balance_qty;
            $requestshop->save();
        }

        }
        } catch (Exception $e) {
            alert()->error("No Instocks");
            return back();
        }


        $itemrequest->status=1;
        $itemrequest->save();
        alert()->success("Successfull send");
        return back();

    }
    public function purchasepriceUpdate(Request $request)
    {
        try{
            $counting_unit = CountingUnit::findOrfail($request->unit_id);
        } catch (\Exception $e) {
            return response()->json(0);
        }
        $counting_unit->update([
            'purchase_price' => $request->purchase_price,
            'normal_sale_price' => $request->normal_price,
            'whole_sale_price' => $request->whole_price,
            'order_price' => $request->order_price,
         ]);

         return response()->json($counting_unit);

    }
    public function execelImport(Request $request)
    {
        $this->validate($request, [
            'select_file' => 'required|mimes:xls,xlsx'
         ]);
        //  try{

         Excel::import(new ItemsImport,request()->file('select_file'));

        // } catch (\Exception $e) {
        // alert()->error("Something Went Wrong!");
        //  return back();

        // }
        alert()->success("Success");
        return back();
    }
    public function delete_units(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "unit_ids" => "required",
            "multi_delete" => "required",
        ]);

        if($validator->fails()){
            return response()->json(0);
        }

        if($request->multi_delete ==1){
            foreach($request->unit_ids as $item_id){
            $item = Item::find($item_id);
            $item->delete();
            }
        }
        else{
            $item = Item::find($request->unit_ids);
            $item->delete();
        }

        $item->delete();

        return response()->json(1);

    }
    public function itemChangeStatus(Request $request, $voucher_id ,$item_id,$status)
    {

        $voucher = Voucher::find($voucher_id);
        $item = $voucher->items()->where('item_id',$item_id)->first();
        $item->pivot->status = $status;
        $item->pivot->save();

            $preOdrPurchasing = 0;
            $preOdrArrived = 0;
            $preOdrInstock = 0;
            $preOdrPacked = 0;
            $preOdrDelivered = 0;

            foreach($voucher->items as $item){
                if($item->status == 'Purchasing'){
                    $preOdrPurchasing +=1;
                }else if($item->status == 'Arrived'){
                    $preOdrArrived +=1;
                }
                else if($item->status == 'Instock'){
                    $preOdrInstock +=1;
                }
                else if($item->status == 'Packed'){
                    $preOdrPacked +=1;
                }
                else if($item->status == 'Delivered'){
                    $preOdrDelivered +=1;
                }
            }
            if($preOdrPurchasing == count($voucher->items)){
                $voucher->status = 1 ;
            }
            else if($preOdrArrived==count($voucher->items)){
                $voucher->status = 2 ;
            }
            else if($preOdrInstock==count($voucher->items)){
                $voucher->status = 0 ;
            }
            else if($preOdrPacked==count($voucher->items)){
                $voucher->status = 4 ;
            }
            else if($preOdrDelivered==count($voucher->items)){
                $voucher->status = 4 ;
            }

            $voucher->save();
 
        alert()->success('Success');
        return back();
    }
     public function orderChangeStatus(Request $request, $voucher_id,$status)
    {
        $voucher = Voucher::find($voucher_id);
        $voucher -> status = $status;
        $voucher->save();

        if($voucher->order_type == 'Instock'){
            foreach($voucher->items as $item){
               $item->pivot->status = 4;
               $item->pivot->save();
            }
        };

        alert()->success('Success');
        return back();
    }
    public function notifyChangeStatus($vou_id)
    {
        // dd($vou_id);
        $change_notify = Voucher::find($vou_id);
        $change_notify->notify_flag = 0;
        $change_notify->save();
        alert()->success("Customer was notified!!!");
        return back();
    }
    protected function search_transactions_bydate(Request $request){


        if($request->type=="marketing"){
            if($request->value==0){
                $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
            }else{
                $fb_Ids= $request->value;
            }
            // ->whereBetween('order_date', [$request->from,$request->to])
            $voucher_lists =Voucher::where('page_id',$fb_Ids)->whereBetween('order_date', [$request->from,$request->to])->orderBy('id','desc')->with('items')->get();
        }
        else{
            $voucher_lists = [];
            if($request->value==0){
                $transactions = Transaction::whereBetween('tran_date', [$request->from,$request->to])->get();
            }else{
                $transactions = Transaction::where('bank_acc_id',$request->value)->whereBetween('tran_date', [$request->from,$request->to])->get();
            }

            foreach($transactions as $transaction){
                if($transaction->voucher){
                    $voucherWithItems = Voucher::with('items')->find($transaction->voucher->id);
                    array_push($voucher_lists,$voucherWithItems);
                }
            }
            
        }
        return response()->json($voucher_lists);
    }
    
    protected function search_transactions_bydatev2(Request $request){

        $voucher_lists = [];
        $transactions_lists=[];
    
        if($request->type=="marketing" ){
                if($request->value==0){
                    $fb_Id= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
                }else{
                    $fb_Id= array($request->value);
                }
                $transactions = Transaction::whereBetween('tran_date', [$request->from,$request->to])->
                whereHas('voucher', function($q)use($fb_Id){
                    $q->whereIn('page_id', $fb_Id);
                })->get();
    
                $transactions_lists= [];
                if($transactions != null){
                    foreach($transactions as $transaction){
                        $bank_acc = BankAccount::findOrFail($transaction->bank_acc_id);
                        $voucher= Voucher::findOrFail($transaction->voucher_id);
                        $fb_page = Fbpage::findOrFail($voucher->page_id);
                        $transaction_final = [
                            'voucher_id' => $transaction->voucher_id,
                            'voucher_code' => $voucher->voucher_code,
                            'order_date' => $voucher->order_date,
                            'customer_name' => $voucher->customer_name,
                            'page_name' => $fb_page->name,
                            'bank_acc_id' => $transaction->bank_acc_id,
                            'bank_name' => $bank_acc->bank_name,
                            'account_number' => $bank_acc->account_number,
                            'account_holder_name' => $bank_acc->account_holder_name,
                            'tran_date' => $transaction->tran_date,
                            'tran_time' => $transaction->tran_time,
                            'pay_amount'=> $transaction->pay_amount,
                            'remark' => $transaction->remark,
                        ];
                        array_push($transactions_lists,$transaction_final);
                    }
                }
                
        }else if($request->type =="owner"){
            if($request->value==0){
                $bank_Id= BankAccount::get()->pluck('id')->toArray();
            }else{
                $bank_Id= array($request->value);
            }
                $transactions = Transaction::whereIn('bank_acc_id',$bank_Id)->whereBetween('tran_date', [$request->from,$request->to])->get();
                 if($transactions != null){
                     
                         foreach($transactions as $transaction){
                             $bank_acc = BankAccount::findOrFail($transaction->bank_acc_id);
                             $voucher= Voucher::findOrFail($transaction->voucher_id);
                             $fb_page = Fbpage::findOrFail($voucher->page_id);
                             $transaction_final = [
                                 "voucher_id" => $transaction->voucher_id,
                                 "voucher_code" => $voucher->voucher_code,
                                 "order_date" => $voucher->order_date,
                                "customer_name" => $voucher->customer_name,
                                "page_name" => $fb_page->name,
                                "bank_acc_id" => $transaction->bank_acc_id,
                                 "bank_name" => $bank_acc->bank_name,
                                 "account_number" => $bank_acc->account_number,
                                 "account_holder_name" => $bank_acc->account_holder_name,
                                 "tran_date" => $transaction->tran_date,
                                
                                 "pay_amount"=> $transaction->pay_amount,
                                 "remark" => $transaction->remark
                             ];
                            array_push($transactions_lists,$transaction_final);
                        }
                    }
            }

        return response()->json($transactions_lists);
    }
    
    
    public function get_search_transactions_bydate()
    {
        return redirect()->route('transaction_lists');
    }
    public function transaction_detail(Request $request)
    {
        $current_Date = $request->from;
        $transaction_lists = Transaction::where('tran_date',$request->from)->where('bank_acc_id',$request->bank_id)->orderBy('id','desc')->get();


        $transactionArr =[];
        foreach($transaction_lists as $transaction){
                $transactionArr['bank_acc_id'] = $transaction->bank_account;
                $transactionArr['tran_date'] = $transaction->tran_date;
                if(isset($transactionArr['pay_amount'])){
                    $transactionArr['pay_amount'] += $transaction->pay_amount;
                }
                else{
                    $transactionArr['pay_amount'] = $transaction->pay_amount;
                }
        }
        return view('Purchase.transaction_detail', compact('transactionArr','transaction_lists'));
    }
    public function clearFlashOverride(Request $request,$voucher_id)
    {   
        $voucher = Voucher::findOrfail($voucher_id);
        $voucher->prepaid_clear_flash = 0;
        $voucher->save();
        alert()->success('Success');
        return back();
    }
    public function orderReturn(Request $request)
    {
        // "deliveredorder_id" => "16"
        // "voucher_id" => "24"
        // "return_date" => "2022-04-09"
        // "return_remark" => "sdf"
        $voucher = Voucher::findOrFail($request->voucher_id);
        $received_amount = $voucher->collect_amount - $voucher->delivery_expense;

        $deliverorder = Deliveredorder::find($request->deliveredorder_id);

        $deliverorder->total_collect_amount = $deliverorder->total_collect_amount - $voucher->collect_amount;
        $deliverorder->total_received_amount = $deliverorder->total_received_amount - $received_amount;
        $deliverorder->save();
        $deliverorder_voucher = DB::table('deliveredorder_voucher')->where('deliveredorder_id',$request->deliveredorder_id)->where('voucher_id',$request->voucher_id);
        $deliverorder_voucher_update = $deliverorder_voucher->update(['status' => 1,'date'=> $request->return_date,'return_remark'=>$request->return_remark]);
    
       return response()->json(1);
    }
    public function itemToStock(Request $request,$item_id,$quantity,$voucher_id,$item_code,$sku_code)
    {
        $check_sku_code = Item::find($item_id)->sku_code;
        $itemInstock = Item::where('sku_code',$check_sku_code)->where('item_type',0)->first();
        if($itemInstock){
            $balance_qty = $itemInstock->stock + $quantity;
            $itemInstock->stock = $balance_qty;
            $itemInstock->save();
        }
        else{
            $itemPreorder = Item::where('sku_code',$check_sku_code)->where('item_type',1)->first();
            $newItemInstock = $itemPreorder->replicate();
            $newItemInstock->stock = $quantity; 
            $newItemInstock->reserve_qty = $quantity; 
            $newItemInstock->item_type = 0; 
            $newItemInstock->sku_code = $sku_code;
            $newItemInstock->item_code = $item_code;
            $newItemInstock->save();
        }
        $itemStatusChange = DB::table('item_voucher')->where('item_id',$item_id)->where('voucher_id',$voucher_id)->update(array('status' => 7)); 
        $voucher = Voucher::find($voucher_id);
        $voucherItemCount = $voucher->items->count();  //2
        $ToInstockItemCount = DB::table('item_voucher')->where('voucher_id',$voucher_id)->where('status',7)->count(); 
        if($voucherItemCount == $ToInstockItemCount){
            $voucher->status = 7;
            $voucher->save();
        }
        return response()->json(1);
    }
    public function orderToStock(Request $request,$voucher_id)
    {  
        $voucher = Voucher::find($voucher_id);
        foreach($voucher->items as $item) 
        {
            $itemStatusChange = DB::table('item_voucher')->where('item_id',$item->id)->where('voucher_id',$voucher_id)->update(array('status' => 7)); 
            $sku_code =  Item::find($item->id)->sku_code;
            $itemInstock = Item::where('sku_code',$sku_code)->where('item_type',0)->first();
            if($itemInstock){
                $balance_qty = $itemInstock->stock + $item->pivot->quantity;
                $itemInstock->stock = $balance_qty;
                $itemInstock->save();
            }
            else{
                $itemPreorder = Item::where('sku_code',$sku_code)->where('item_type',1)->first();
                $newItemInstock = $itemPreorder->replicate();
                $newItemInstock->stock = $item->pivot->quantity; 
                $newItemInstock->reserve_qty = $item->pivot->quantity; 
                $newItemInstock->item_type = 0; 
                $newItemInstock->save();
            }
        }

        $voucher->status = 7 ;
        $voucher->save();

        return response()->json(1);
    }
    protected function getTransactionVouchers(Request $request){

        $date = new DateTime('Asia/Yangon');
        
        $current_Date = $date->format('Y-m-d');

        $role= $request->session()->get('user')->role;
        //$role = "Owner";
        if($role=="Marketing"){
            $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();

            $voucher_lists =Voucher::where('page_id',$fb_Ids)->where('order_date',$current_Date)->orderBy('id','desc')->with('items')->with('items.purchases')->get();

            $fb_pages = $request->session()->get('user')->employee->fbpages;
        }
        else{
            $voucher_lists = [];
            $transactions = Transaction::where('tran_date',$current_Date)->get();
            foreach($transactions as $transaction){
                array_push($voucher_lists,$transaction->voucher);
            }
            // $voucher_lists =Voucher::where('order_date',$current_Date)->orderBy('id','desc')->with('items')->with('items.purchases')->get();
            // dd($voucher_lists);
            $fb_pages =Fbpage::all();
        }
        $bank_accs = BankAccount::all();
        $purchase_lists = DB::table('item_purchase')->get();
        $search_sales = 0;
        return view('Sale.transaction_list',compact('voucher_lists','purchase_lists','current_Date','fb_pages','bank_accs'));
    }
    
    protected function getTransactionVouchersv2(Request $request){

        $date = new DateTime('Asia/Yangon');
        
        $current_Date = $date->format('Y-m-d');

        $role= $request->session()->get('user')->role;
        //$role = "Owner";
        $name = $request->session()->get('user')->name;
        //$name = "Ko Si Thu";
        if($role=="Marketing"){
            $fb_Ids= $request->session()->get('user')->employee->fbpages()->pluck('id')->toArray();
            $transactions = Transaction::where('tran_date',$current_Date)
            ->
            whereHas('voucher', function($q)use($fb_Ids){
                $q->whereIn('page_id', $fb_Ids);
            })->get();

            $transactions_lists= [];
            if($transactions != null){
                foreach($transactions as $transaction){
                    $bank_acc = BankAccount::findOrFail($transaction->bank_acc_id);
                    $voucher= Voucher::findOrFail($transaction->voucher_id);
                    $fb_page = Fbpage::findOrFail($voucher->page_id);
                    $transaction_final = [
                        'voucher_id' => $transaction->voucher_id,
                        'voucher_code' => $voucher->voucher_code,
                        'order_date' => $voucher->order_date,
                        'customer_name' => $voucher->customer_name,
                        'page_name' => $fb_page->name,
                        'bank_acc_id' => $transaction->bank_acc_id,
                        'bank_name' => $bank_acc->bank_name,
                        'account_number' => $bank_acc->account_number,
                        'account_holder_name' => $bank_acc->account_holder_name,
                        'tran_date' => $transaction->tran_date,
                        'tran_time' => $transaction->tran_time,
                        'pay_amount'=> $transaction->pay_amount,
                        'remark' => $transaction->remark,
                    ];
                    array_push($transactions_lists,$transaction_final);
                }
            }
       
            $fb_pages = $request->session()->get('user')->employee->fbpages;
        }
        else{
            $bank_Ids= BankAccount::get()->pluck('id')->toArray();

            $transactions_lists=[];
            $transactions = Transaction::whereIn('bank_acc_id',$bank_Ids)->whereBetween('tran_date', [$current_Date,$current_Date])->get();
            if($transactions != null){
                foreach($transactions as $transaction){
                    $bank_acc = BankAccount::findOrFail($transaction->bank_acc_id);
                    $voucher= Voucher::findOrFail($transaction->voucher_id);
                    $fb_page = Fbpage::findOrFail($voucher->page_id);
                    $transaction_final = [
                        'voucher_id' => $transaction->voucher_id,
                        'voucher_code' => $voucher->voucher_code,
                        'order_date' => $voucher->order_date,
                        'customer_name' => $voucher->customer_name,
                        'page_name' => $fb_page->name,
                        'bank_acc_id' => $transaction->bank_acc_id,
                        'bank_name' => $bank_acc->bank_name,
                        'account_number' => $bank_acc->account_number,
                        'account_holder_name' => $bank_acc->account_holder_name,
                        'tran_date' => $transaction->tran_date,
                        'tran_time' => $transaction->tran_time,
                        'pay_amount'=> $transaction->pay_amount,
                        'remark' => $transaction->remark,
                    ];
                    array_push($transactions_lists,$transaction_final);
                }
                
            }
            // $voucher_lists =Voucher::where('order_date',$current_Date)->orderBy('id','desc')->with('items')->with('items.purchases')->get();
            // dd($voucher_lists);
            $fb_pages =Fbpage::all();
        }
   

        $voucher_lists = [];
        $transactions = Transaction::where('tran_date',$current_Date)->get();
        foreach($transactions as $transaction){
            array_push($voucher_lists,$transaction->voucher);
        }

        //    dd($transactions_lists[0]['voucher_code']);
        $bank_accs = BankAccount::all();
        $purchase_lists = DB::table('item_purchase')->get();
        $search_sales = 0;
        return view('Sale.transaction_listv2',compact('voucher_lists','transactions_lists','purchase_lists','current_Date','fb_pages','bank_accs','name'));
    }
    public function saleCount(Request $reqeust,$from_date,$to_date,$order_type)
    {
        if($order_type == 'instock'){   //item_type -0
            $orderType = 0;
        }else if($order_type == 'preorder')
        {
            $orderType = 1;
        }
        
        $vouchers = Voucher::whereBetween('order_date', [$from_date,$to_date])->where('order_type',$orderType)->get();
        
        if($vouchers->isEmpty()){
            $items = [];
            $item_worst_sell_10 = [];
            $item_best_sell_10 = [];
        }else{
        
        $total_quantity = 0;
        

        $item_lists = array();
        
        
        foreach($vouchers as $voucher){
            
            $total_quantity += $voucher->total_quantity;
            
            foreach($voucher->items as $item){
                
                $item_id = $item->id;
                
                $item_name = $item->item_name;

                $item_code = $item->item_code;

                $sku_code = $item->sku_code;
                
                $quantity = $item->pivot->quantity;
                
                $price = $item->selling_price;
                
                $combined = array('item_id' => $item_id, 'item_name' => $item_name, 'item_code' => $item_code, 'sku_code' => $sku_code, 'quantity' => $quantity,'price' => $price );
                
                array_push($item_lists, $combined);
            }
            
        }
        
        $items = array();
        
        foreach ($item_lists as $item) {
            
            if (!isset($result[$item['item_id']]))
            
                $result[$item['item_id']] = $item;
                
            else
            
                $result[$item['item_id']]['quantity'] += $item['quantity'];
        }
        
        $items =  array_values($result);

        $price = array_column($result, 'quantity');
        $priceDESCSort = array_multisort($price, SORT_DESC, $result);
        $items = $result;

        $priceASCSort = array_multisort($price, SORT_ASC, $result);
        $itemsASC = $result;

        $item_best_sell_10 =[];
        foreach($items as $key=>$item){
            array_push($item_best_sell_10,$item);
            if($key==9){
               break;
            }
        }
        
        $item_worst_sell_10 =[];
       foreach($itemsASC as $key=>$item){
           array_push($item_worst_sell_10,$item);
           if($key==9){
              break;
           }
       }
    }

       
        return view('Inventory.total_item_sale',compact('items','item_best_sell_10','item_worst_sell_10','from_date','to_date','order_type'));
    }
}