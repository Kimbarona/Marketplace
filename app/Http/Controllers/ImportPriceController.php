<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;
use App\Imports\PriceChangeImport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use App\Price;
use Illuminate\Contracts\Validation\Rule;

class ImportPriceController extends Controller
{

    public function update(Request $request){

        $request->validate([
            'excelfile'  => 'required|file|mimes:xls,xlsx'
           ]);

           $file = $request->file('excelfile');
           (new PriceChangeImport)->import($file);
            // $data = Excel::toArray(new PriceChangeImport, $request->file('excelfile'));
            // collect(head($data))
            // ->each(function ($row, $key){
            //     DB::table('prices')
            //     ->where('store_id', $row['store_id'])
            //     ->where('product_id', $row['product_id'])
            //     ->update(Arr::except($row, ['store_id'], $row, ['product_id']));
            // });


    }

}
