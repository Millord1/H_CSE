<?php

namespace App\Http\Controllers;

use App\Http\Requests\Offer\StoreOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Models\Offer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class OfferController extends Controller
{
    public function create(): View
    {
        return view('offers.create');
    }

    public function store(StoreOfferRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('offers', ['disk' => 'public']);

        Offer::create($data);

        return redirect()->route('dashboard');
    }

    public function edit(string $offerId): View
    {
        return view('offers.edit', [
            'offer' => Offer::find($offerId),
        ]);
    }

    public function update(UpdateOfferRequest $request, string $offerId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);
        $offer->update($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            $offer->update([
                'image' => $request->file('image')->store('offers', ['disk' => 'public'])
            ]);
        }

        return redirect()->route('dashboard');
    }

    public function destroy(string $offerId): RedirectResponse
    {
        Offer::where('id', $offerId)->delete();

        return redirect()->route('dashboard');
    }

    public function show(string $offerId): View
    {
        $offer = Offer::with('products')->findOrFail($offerId);

        return view('offers.show', compact('offer'));
    }
}
