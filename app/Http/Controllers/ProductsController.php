<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController
{
    public function index(Request $request)
    {
        $products = Product::orderBy('sort_order')
            ->where('visible', true)
            ->get();

        dump('I AM HERE');
        dump($products);

        return view(
            'front.pages.products.index',
            compact('products')
        );
    }

    public function show(Request $request, Product $product)
    {
        $purchases = $licenses = collect();

        if ($request->user()) {
            $purchases = $request->user()
                ->purchasesWithoutRenewals()
                ->forProduct($product)
                ->get();

            $licenses = $request->user()
                ->licensesWithoutRenewals()
                ->with(['purchasable'])
                ->forProduct($product)
                ->get();
        }

        return view('front.pages.products.show', compact('product', 'purchases', 'licenses'));
    }
}
