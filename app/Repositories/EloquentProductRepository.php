<?php

namespace App\Repositories;

use App\DTOs\ProductDTO;
use App\Models\Offer;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * @return LengthAwarePaginator<int, ProductDTO>
     */
    public function paginateByOffer(Offer $offer, int $perPage = 10): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, ProductDTO> $paginator */
        $paginator = $offer->products()
            ->latest()
            ->paginate($perPage)
            ->through(fn (Product $product) => ProductDTO::fromModel($product));

        return $paginator;
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Offer $offer, array $data): Product
    {
        return $offer->products()->create($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }
}
