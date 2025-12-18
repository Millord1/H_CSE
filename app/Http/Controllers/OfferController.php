<?php

namespace App\Http\Controllers;

use App\DTOs\OfferDTO;
use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Models\Offer;
use App\Repositories\Interfaces\OfferRepositoryInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class OfferController extends Controller
{
    public function __construct(
        protected OfferRepositoryInterface $offerRepository
    ) {}

/*     public function index(): View
    {
        return view('offers.index', [
            'offers' => $this->repository->paginate(10),
        ]);
    } */

    public function create(): View
    {
        return view('offers.create');
    }

    public function store(StoreOfferRequest $request): RedirectResponse
    {
        $data = $request->validated();
        // Safe storage with random name (store)
        $data['image'] = $request->file('image')->store('offers', ['disk' => 'public']);

        $this->offerRepository->create($data);

        return redirect()->route('dashboard');
    }

    public function edit(Offer $offer): View
    {
        return view('offers.edit', compact('offer'));
    }

    public function update(UpdateOfferRequest $request, Offer $offer): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('offers', 'public');
        }

        $this->offerRepository->update($offer, $data);

        return redirect()->route('dashboard');
    }

    public function destroy(Offer $offer): RedirectResponse
    {
        $this->offerRepository->delete($offer);

        return redirect()->route('dashboard');
    }

    public function show(Offer $offer): View
    {
        $offer->load('products');

        return view('offers.show', [
            'offer' => OfferDTO::fromModel($offer),
        ]);
    }
}
