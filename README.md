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

app/
├── Http/
│ ├── Exceptions/
│ │ ├── Handler.php
│ ├── Controllers/
│ │ ├── ProductController.php
│ ├── Requests/
│ │ ├── ProductRequest.php
│ ├── Interfaces/
│ │ ├── ProductRepositoryInterface.php
│ ├── Repositories/
│ │ ├── ProductRepository.php
├── Models/
│ ├── Product.php
database/
├── migrations/
│ ├── 2025_04_06_060328_create_products_table.php
routes/
├── api.php

## 🚀 Instalasi
```bash
git clone https://github.com/Vinsmoke-Tech/product-management.git
cd product-management
code .
composer install
rename .env.example jadi .env
```
## Atur DB di .env
```bash
php artisan migrate
php artisan serve
```

## 💻 .env
```php
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=product_crud
DB_USERNAME={Username anda}
DB_PASSWORD={Password anda}
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



