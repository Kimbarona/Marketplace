<?php

namespace App\Imports;

use App\Price;
use App\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class PriceListImport implements ToModel, WithHeadingRow, WithValidation
{

    use Importable;

    public function model(array $row)
    {
        Price::create([
            'store_id'  => $row['store_id'],
            'product_id' => $row['product_id'],
            'price' => $row['price'],
        ]);

    }

    public function rules(): array
    {
        return [
            '*.store_id' => 'required',
            '*.price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            // '*.product_id' => 'required|unique:prices,product_id,NULL,store_id,store_id,' . $this->store_id
            ];
    }
}
