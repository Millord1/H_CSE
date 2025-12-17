<?php

namespace App\Http\Controllers;

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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:offers,slug'],
            'image' => ['required', 'image'],
            'description' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'in:draft,published,hidden'],
        ]);

        Offer::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'image' => $request->image->store('offers', ['disk' => 'public']),
            'description' => $request->description,
            'state' => $request->state,
        ]);

        return redirect()->route('dashboard');
    }

    public function edit(string $offerId): View
    {
        return view('offers.edit', [
            'offer' => Offer::find($offerId),
        ]);
    }

    public function update(Request $request, string $offerId): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'image' => ['required', 'file'],
            'description' => ['nullable', 'string', 'max:255'],
            'state' => ['required', 'string', 'in:draft,published,hidden'],
        ]);

        $offer = Offer::findOrFail($offerId);
        $offer->update($request->all('name', 'slug', 'description', 'state'));

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
