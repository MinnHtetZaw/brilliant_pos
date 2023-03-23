<?php

namespace App\Exports;

use App\Voucher;
use App\Deliveredorder;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DeliveredOrdersHistoryExport implements FromArray,ShouldAutoSize,WithHeadings
{
    use Exportable;
    
    protected $from_date;
    protected $to_date;
    protected $delivery_id;
    
    
    public function __construct($from,$to,$delivery_id){
        $this->from_date = $from;
        $this->to_date = $to;
        $this->delivery_id = $delivery_id;
        
    }
    
    public function array() :array
    {
        $delivery_orders = Deliveredorder::whereBetween('created_at',[$this->from_date,$this->to_date])->with('delivery')->where('delivery_id',$this->delivery_id)->withCount('vouchers')->get();

        $order_lists = array();
        foreach($delivery_orders as $de){
            $orders = $de->vouchers()->wherePivot('status',0)->with('fbpage')->get();
            foreach($orders as $order){
                 $delivery_name = $de->delivery->name;
                 $delivery_date = $de->date;
                 $create_date = $de->created_at;
                 $order_code = $order->voucher_code;
                 $order_date = $order->order_date;
                $fb_page = $order->fbpage->name;
                $customer_name = $order->customer_name;
                $customer_address = $order->customer_address;
                $total_charges = $order->total_charges;
                $delivery_expense = $order->delivery_expense;
                $collect_amount = $order->collect_amount;
                $receive_amount = $order->collect_amount - $order->delivery_expense;
              $combined = array('delivery_name' => $delivery_name,'delivery_date'=> $delivery_date,'create_date'=> $create_date,'order_code' => $order_code, 'order_date' => $order_date,'fb_page' => $fb_page, 'customer_name' => $customer_name,'customer_address' => $customer_address, 'total_charges' => $total_charges, 'delivery_expense' =>$delivery_expense, 'collect_amount' => $collect_amount, 'receive_amount' => $receive_amount);
                 array_push($order_lists, $combined);
            }
            
        }
        return $order_lists;
       
    }
    
    public function headings():array{
        
        return [
           'Delivery Name',
            'Delivery Date',
            'Create Date',
            'Order Code',
            'Order Date',
            'Page Name',
            'Customer Name',
            'Customer Address',
            'Total Charges',
            'Delivery Expense',
            'Collect Amount',
            'Receive Amount',
        ];
        
    }
    
    
}