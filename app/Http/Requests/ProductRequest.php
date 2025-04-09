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



