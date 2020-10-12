<?php

namespace App\Imports;

use App\Price;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PriceChangeImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    public function model(array $row)
    {

        DB::table('prices')
        ->where('store_id', $row['store_id'])
        ->where('product_id', $row['product_id'])
        ->update(Arr::except($row, ['store_id'], $row, ['product_id']));

    }

    public function rules(): array
    {
        return [
            '*.store_id' => 'required',
            '*.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            '*.product_id' => 'required'
        ];
    }
}
