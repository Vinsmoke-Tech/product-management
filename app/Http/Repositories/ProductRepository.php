<?php

namespace App\Http\Repositories;

use Log;
use App\Models\Product;
use App\Http\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Product::paginate(5);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);
        return $product;
    }

    public function delete(Product $product): void
    {
        if ($product->stock > 0) {
            throw new \Exception("Produk tidak bisa dihapus karena stok masih tersedia.");
        }
    
        $product->delete();
    }
    
}


