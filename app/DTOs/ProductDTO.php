<?php

namespace App\DTOs;

use App\Models\Product;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Storage;

readonly class ProductDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $sku,
        public string $price,
        public ?string $imageUrl,
        public string $stateLabel,
        public string $stateKey,
    ) {}

    public static function fromModel(Product $product): self
    {
        /** @var FileSystemAdapter $storage */
        $storage = Storage::disk('public');

        return new self(
            id: $product->id,
            name: $product->name,
            sku: $product->sku,
            price: number_format($product->price, 2, ',', ' ').' €',
            imageUrl: $product->image ? $storage->url($product->image) : null,
            stateLabel: Product::$states[$product->state] ?? $product->state,
            stateKey: $product->state,
        );
    }
}
