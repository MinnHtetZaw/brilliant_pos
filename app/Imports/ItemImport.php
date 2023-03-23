<?php

namespace App\Imports;

use App\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class ItemImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $userId = session()->get('user')->id;
        $item_store = new Item([
            'item_type' => $row['item_type'],
            'category_id' => $row['category_id'],
            // 'sub_category_id' => $row['subcategory_id'],
            'item_code' => $row['product_code'],
            'item_name' => $row['product_name'],
            'sku_code' => $row['sku_code'],
            // 'original_code' => $row['original_code'],
            'stock' => 0,
            'brand_name' => $row['brand_name'],
            'selling_price' => $row['selling_price'],
            'purchase_price' => $row['purchase_price'],
            'created_by' => $userId,
            // 'model_size' => $row['model_size'],
            // 'customer_console'=>0,
            // 'photo_path'=> 'default.png',

        ]);
        $item_store->save();
        return $item_store;
    }
}