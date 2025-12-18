<?php

namespace App\Repositories\Interfaces;

use App\DTOs\OfferDTO;
use App\Models\Offer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OfferRepositoryInterface
{
    /** @return Collection<int, OfferDTO> */
    public function getAllPublished(): Collection;

    public function findById(int $id): ?Offer;

    /** @param array<string, mixed> $data */
    public function create(array $data): Offer;

    /** @param array<string, mixed> $data */
    public function update(Offer $offer, array $data): bool;

    public function delete(Offer $offer): bool;

    /**
     * @return LengthAwarePaginator<int, OfferDTO>
     */
    public function paginate(int $perPage = 10): LengthAwarePaginator;
}
