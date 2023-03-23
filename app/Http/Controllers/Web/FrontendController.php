<?php

namespace App\Http\Controllers\Web;

use App\Item;
use App\News;
use App\Contact;
use App\Package;
use App\PackageKg_Price;
use App\WayPlanSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;


class FrontendController extends Controller
{
    public function index(Request $request)
    {

        $items = Item::all();
        return view('frontend.home',compact('items'));
    }


}


// orWhere('customer_phone','LIKE','%'.$request->t.'%')->

