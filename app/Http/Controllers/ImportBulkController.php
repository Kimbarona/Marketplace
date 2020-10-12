<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use App\Imports\PriceListImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Price;



class ImportBulkController extends Controller
{
    public function import(Request $request){

        $request->validate([
            'importBulk'  => 'required|file|mimes:xls,xlsx'
           ]);

        $file = $request->file('importBulk');
        (new PriceListImport)->import($file);

        return response()->json(['message' => 'Price Added Successfully!!'], 200);

    }
}
