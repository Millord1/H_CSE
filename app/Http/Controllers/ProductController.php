<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(string $offerId): View
    {
        $offer = Offer::findOrFail($offerId);
        $products = $offer->products()->latest()->get();

        return view('products.index', compact('offer', 'products'));
    }

    public function create(string $offerId): View
    {
        $offer = Offer::findOrFail($offerId);
        $product = new Product;

        return view('products.create', compact('offer', 'product'));
    }

    public function store(StoreProductRequest $request, string $offerId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);

        /** @var Product $product */
        $product = $offer->products()->create($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            // Safe storage with random name (store)
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);
        }

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit créé avec succès.');
    }

    public function edit(string $offerId, string $productId): View
    {
        $offer = Offer::findOrFail($offerId);
        $product = $offer->products()->findOrFail($productId);

        return view('products.edit', compact('offer', 'product'));
    }

    public function update(UpdateProductRequest $request, string $offerId, string $productId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);
        /** @var Product $product */
        $product = $offer->products()->findOrFail($productId);

        $product->update($request->safe()->except('image'));

        if ($request->hasFile('image')) {
            // Safe storage with random name (store)
            $path = $request->file('image')->store('products', 'public');
            $product->update(['image' => $path]);
        }

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit mis à jour avec succès.');
    }

    public function destroy(string $offerId, string $productId): RedirectResponse
    {
        $offer = Offer::findOrFail($offerId);
        $product = $offer->products()->findOrFail($productId);
        $product->delete();

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit supprimé avec succès.');
    }
}
