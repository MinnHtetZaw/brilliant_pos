<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ItemImport;
class ItemExcelController extends Controller
{
    //
    public function import_items()
    {
        Excel::import(new ItemImport,request()->file('file'));
        alert()->success('Successfully Stored Items With Excel!');
        return redirect()->back();
    }
}
