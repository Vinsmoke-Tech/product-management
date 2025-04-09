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




