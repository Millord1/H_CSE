<?php

namespace App\Repositories\Interfaces;

use App\DTOs\ProductDTO;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, ProductDTO>
     */
    public function paginateByOffer(Offer $offer, int $perPage = 10): LengthAwarePaginator;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Offer $offer, array $data): Product;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): bool;

    public function delete(Product $product): ?bool;
}
