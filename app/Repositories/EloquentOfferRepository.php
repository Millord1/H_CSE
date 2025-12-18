<?php

namespace App\Repositories;

use App\DTOs\OfferDTO;
use App\Models\Offer;
use App\Repositories\Interfaces\OfferRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentOfferRepository implements OfferRepositoryInterface
{
    /**
     * @return Collection<int, OfferDTO>
     */
    public function getAllPublished(): Collection
    {
        return Offer::query()
            ->where('state', 'published')
            ->latest()
            ->get()
            ->map(fn (Offer $offer) => OfferDTO::fromModel($offer));
    }

    public function findById(int $id): ?Offer
    {
        return Offer::find($id);
    }

    /**
     * @param array<string, mixed> $data
     * @return Offer
     */
    public function create(array $data): Offer
    {
        return Offer::create($data);
    }

    /**
     * @param Offer $offer
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(Offer $offer, array $data): bool
    {
        return $offer->update($data);
    }

    public function delete(Offer $offer): bool
    {
        return (bool) $offer->delete();
    }

    /**
     * @param int $perPage
     * @return LengthAwarePaginator<int, OfferDTO>
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        /** @var LengthAwarePaginator<int, OfferDTO> $paginator */
        $paginator = Offer::query()
            ->latest()
            ->paginate($perPage)
            ->through(fn (Offer $offer) => OfferDTO::fromModel($offer));

        return $paginator;
    }
}