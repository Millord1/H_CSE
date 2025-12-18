<?php

namespace App\DTOs;

use App\Models\Offer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

readonly class OfferDTO
{
    /**
     * @param int $id
     * @param string $name
     * @param string $slug
     * @param string|null $description
     * @param string $imageUrl
     * @param string $stateLabel
     * @param Collection<int, ProductDTO>
     */
    public function __construct(
        public int $id,
        public string $name,
        public string $slug,
        public ?string $description,
        public string $imageUrl,
        public string $stateLabel,
        public Collection $products,
    ) {}

    public static function fromModel(Offer $offer): self
    {
        /** @var FileSystemAdapter $storage */
        $storage = Storage::disk('public');

        return new self(
            id: $offer->id,
            name: $offer->name,
            slug: $offer->slug,
            description: $offer->description,
            imageUrl: $storage->url($offer->image),
            stateLabel: Offer::$states[$offer->state] ?? $offer->state,
            products: $offer->relationLoaded('products') 
                ? $offer->products->map(fn ($product) => ProductDTO::fromModel($product))
                : collect(),
        );
    }
}