<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Offer;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected ProductRepositoryInterface $productRepository) {}

    public function index(Offer $offer): View
    {
        $products = $this->productRepository->paginateByOffer($offer);

        return view('products.index', compact('offer', 'products'));
    }

    public function create(Offer $offer): View
    {
        $product = new Product;

        return view('products.create', compact('offer', 'product'));
    }

    public function store(StoreProductRequest $request, Offer $offer): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $this->productRepository->create($offer, $data);

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit créé avec succès.');
    }

    public function edit(Offer $offer, Product $product): View
    {
        return view('products.edit', compact('offer', 'product'));
    }

    public function update(UpdateProductRequest $request, Offer $offer, Product $product): RedirectResponse
    {
        $data = $request->safe()->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $this->productRepository->update($product, $data);

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit mis à jour avec succès.');
    }

    public function destroy(Offer $offer, Product $product): RedirectResponse
    {
        $this->productRepository->delete($product);

        return redirect()
            ->route('offers.products.index', $offer->id)
            ->with('status', 'Produit supprimé avec succès.');
    }
}
