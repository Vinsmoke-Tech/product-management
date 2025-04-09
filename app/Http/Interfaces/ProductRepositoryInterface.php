<?php

// app/Http/Repositories/ProductRepository.php

// app/Http/Interfaces/ProductRepositoryInterface.php

namespace App\Http\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all(): LengthAwarePaginator;
    public function create(array $data): Product;
    public function update(Product $product, array $data): Product;
    public function delete(Product $product): void;
}

