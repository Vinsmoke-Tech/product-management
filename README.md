# 🛒 Laravel Product CRUD API

Sebuah aplikasi Laravel sederhana untuk manajemen produk menggunakan prinsip Clean Architecture, SOLID Principles, Repository Pattern, dan dokumentasi API.

---

## 🔧 Tech Stack

- Laravel 11
- PHP 8.2+
- MySQL
- Postman (untuk testing API)

---

## 📂 Struktur Folder
```sql
app/
├── Exceptions/
│   └──  Handler.php
├── Http/
│   ├── Controllers/
│   │   └── ProductController.php
│   ├── Requests/
│   │   └── ProductRequest.php
│   ├── Interfaces/
│   │   └── ProductRepositoryInterface.php
│   ├── Repositories/
│   │   └── ProductRepository.php
├── Models/
│   └── Product.php
├── Providers/
│   └──  AppServiceProvider.php

database/
└── migrations/
    └── 2025_04_06_060328_create_products_table.php

routes/
└── api.php
```

## 🚀 Instalasi
```bash
git clone https://github.com/Vinsmoke-Tech/product-management.git
```
```bash
cd product-management
```
```bash
code .
```
```bash
composer install
```
```bash
rename .env.example jadi .env
```
## 🔧 Konfigurasi .env
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_crud
DB_USERNAME={Username anda}
DB_PASSWORD={Password anda}
```

## 💻Jalankan Server
```bash
php artisan migrate
php artisan serve
```

## 📄 Model: Product.php
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'product_name',
        'description',
        'product_price',
        'stock',
    ];
}
```

## 🧬 Migration: create_products_table.php
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->unique();
            $table->text('description')->nullable();
            $table->decimal('product_price', 8, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->timestampsTz();
            $table->softDeletesTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
```

## 🔐 Request Validation: ProductRequest.php
```php
<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {    
        $id = $this->route('product')?->id ?? null;
        
        return [
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $id,
            'description' => 'nullable|string',
            'product_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ];
    }


    public function messages(): array
    {
        return [
            'product_name.required' => 'Nama produk wajib diisi.',
            'product_name.string' => 'Nama produk harus berupa teks.',
            'product_name.max' => 'Nama produk maksimal 255 karakter.',
            'product_name.unique' => 'Nama produk sudah digunakan, silakan pilih nama lain.',

            'description.string' => 'Deskripsi harus berupa teks.',

            'product_price.required' => 'Harga produk wajib diisi.',
            'product_price.numeric' => 'Harga harus berupa angka.',
            'product_price.min' => 'Harga tidak boleh kurang dari 0.',

            'stock.required' => 'Stok wajib diisi.',
            'stock.integer' => 'Stok harus berupa bilangan bulat.',
            'stock.min' => 'Stok tidak boleh kurang dari 0.',
        ];
    }

}
```

## 🧩 Repository Interface: ProductRepositoryInterface.php
```php
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
```

## 🧠 Repository Implementation: ProductRepository.php
```php
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
```

## 🔁 Service Provider Binding (AppServiceProvider.php)
```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Interfaces\ProductRepositoryInterface;
use App\Http\Repositories\ProductRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
```

## 🧭 Controller: ProductController.php
```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\ProductRequest;
use App\Http\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{
    public function __construct(private ProductRepositoryInterface $productRepository) {}

    public function index(): JsonResponse
    {
        $products = $this->productRepository->all();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar produk berhasil diambil.',
            'data' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'last_page' => $products->lastPage(),
                'next_page_url' => $products->nextPageUrl(),
                'previous_page_url' => $products->previousPageUrl(),
            ]
        ], 201);
    }

    public function store(ProductRequest $request): JsonResponse
    {
        $product = $this->productRepository->create($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambahkan.',
            'data' => $product
        ], 201);
    }

    public function update(ProductRequest $request, Product $product): JsonResponse
    {
        $product = $this->productRepository->update($product, $request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil diperbarui.',
            'data' => $product
        ]);
    }

    public function destroy(Product $product): JsonResponse
    {
        try {
            $this->productRepository->delete($product);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Produk berhasil dihapus.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus produk.',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
```
## 🛣️ Routing: routes/api.php
```php
<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::prefix('products')->controller(ProductController::class)->group(function () {
    Route::get('/list', 'index');
    Route::get('{product}', 'show');
    Route::post('/', 'store');
    Route::put('{product}', 'update');
    Route::delete('{product}', 'destroy');
});
```

#📘 Dokumentasi Arsitektur
##✅ Arsitektur:
Controller: Hanya menangani permintaan & respon.

Request: Validasi input.

Repository: Mengelola logika bisnis & interaksi database.

Interface: Memastikan dependensi longgar & mudah diubah/diuji.

Model: Mewakili entitas produk.

Service Provider: Binding antar interface dan implementasi (IOC Container).

##🔍 Prinsip SOLID:
Single Responsibility: Tiap class punya satu tugas.

Open/Closed: Bisa diperluas via interface tanpa ubah kode utama.

Liskov Substitution: Interface dapat diganti implementasinya.

Interface Segregation: Interface hanya menyediakan metode yang dibutuhkan.

Dependency Inversion: Controller tergantung pada abstraksi, bukan implementasi.

