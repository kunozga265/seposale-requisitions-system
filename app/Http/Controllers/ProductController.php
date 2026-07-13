<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductVariantResource;
use App\Models\Delivery;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\ProductVariantPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy("name", "asc")->get();


        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(ProductResource::collection($products));
        else {
            //Web Response
            return Inertia::render('Products/Index', [
                'products' => ProductResource::collection($products),
            ]);
        }
    }

    public function show(Request $request, $id)
    {
        //find out if the request is valid
        $product = Product::find($id);

        if (is_object($product)) {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(new ProductResource($product));
            } else {
                //                $sales = $product->sales()->paginate(100);

                $unsorted = Delivery::whereHas('summary.product', function ($query) use ($product) {
                    $query->where('id', $product->id);
                })
                    ->orderBy("due_date", "asc")->get();

                $sorted = [];

                if (!$unsorted->isEmpty()) {
                    $currentMonth = date('F', $unsorted[0]->due_date);
                    $currentYear = date('Y', $unsorted[0]->due_date);

                    $item = 0;
                    $index = 0;
                    foreach ($unsorted as $object) {

                        if ($item == 0) {
                            $total = $object->summary->paid();
                            $count = $object->deliveryNotes->count() > 0 ? $object->deliveryNotes->count() : 1;
                            $quantity = $object->quantity_delivered;


                            $sorted[0] = [
                                'month' => $currentMonth,
                                'year' => $currentYear,
                                'total' => $total,
                                'count' => $count,
                                'quantity' => $quantity
                            ];
                        } else {
                            $month = date('F', $unsorted[$item]->due_date);
                            $year = date('Y', $unsorted[$item]->due_date);

                            if ($currentMonth === $month && $currentYear === $year) {

                                $sorted[$index]['total'] += $object->summary->paid();
                                $sorted[$index]['count'] += $object->deliveryNotes->count() > 0 ? $object->deliveryNotes->count() : 1;
                                $sorted[$index]['quantity'] += $object->quantity_delivered;;
                            } else {
                                $index += 1;
                                $currentMonth = date('F', $unsorted[$item]->due_date);
                                $currentYear = date('Y', $unsorted[$item]->due_date);
                                $total = $object->summary->paid();
                                $count = 1;
                                $quantity = $object->quantity_delivered;

                                $sorted[$index] = [
                                    'month' => $currentMonth,
                                    'year' => $currentYear,
                                    'total' => $total,
                                    'count' => $count,
                                    'quantity' => $quantity
                                ];
                            }
                        }
                        $item += 1;
                    }
                }



                $chartData = [];
                $currentYear = "";
                $index = -1;
                foreach ($sorted as $object) {
                    $year = $object["year"];
                    if ($currentYear != $year) {
                        $index++;
                        $currentYear = $year;
                        $chartData[$index] = [
                            "year" => $currentYear,
                            "data" => [$object]
                        ];
                    } else {
                        $chartData[$index]["data"][] = $object;
                    }
                }

                for ($i = 0; $i < count($chartData); $i++) {
                    $chartData[$i]["data"] = array_reverse($chartData[$i]["data"]);
                }





                //Web Response
                return Inertia::render('Products/Show', [
                    'product' => new ProductResource($product),
                    //                    'sales' => ProductResource::collection($sales),
                    'chartData' => $chartData,
                    'data' => $sorted,
                ]);
            }
        } else {
            if ((new AppController())->isApi($request)) {
                //API Response
                return response()->json(['message' => "Client not found"], 404);
            } else {
                //Web Response
                return Redirect::route('dashboard')->with('error', 'Client not found');
            }
        }
    }

    public function create(Request $request)
    {
        return Inertia::render('Products/Create', []);
    }

    public function store(Request $request)
    {

        $request->validate([
            'name' => ['required'],
            'variant_name' => ['required'],
            'description' => ['required'],
            'cost' => ['required'],
        ]);


        $product = Product::create([
            "name" => $request->name,
            "slug" => $this->uniqueSlug(Product::class, $request->name),
            "photo" => $request->photo,
        ]);

        $variant = ProductVariant::create([
            "description" => $request->description,
            "name" => $request->variant_name,
            "slug" => $this->uniqueSlug(ProductVariant::class, $request->description),
            "unit" => $request->unit,
            "quantity" => $request->quantity,
            "cost" => $request->cost,
            "cost_original" => $request->cost,
            "product_id" => $product->id,
            "photo" => $request->photo,
        ]);

        if ($request->photo) {
            ProductVariantPhoto::create([
                "product_variant_id" => $variant->id,
                "path" => $request->photo,
                "sort_order" => 0,
            ]);
        }

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ProductResource($product), 201);
        else {
            //Web Response
            return Redirect::route('products.index')->with('success', 'Product created!');
        }
    }

    public function edit(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        return Inertia::render('Products/Edit', [
            'product' => new ProductResource($product),
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'name' => ['required'],
        ]);

        $product->update([
            "name" => $request->name,
            "slug" => $request->name === $product->name ? $product->slug : $this->uniqueSlug(Product::class, $request->name, $product->id),
            "photo" => $request->photo ?? $product->photo,
            "description" => $request->description,
            "description_full" => $request->description_full,
        ]);

        $this->generatePricelist();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ProductResource($product));
        else {
            //Web Response
            return Redirect::route('products.index')->with('success', 'Product updated!');
        }
    }

    public function destroy(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        $this->generatePricelist();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(['message' => 'Product deleted']);
        else {
            //Web Response
            return Redirect::route('products.index')->with('success', 'Product deleted!');
        }
    }

    public function destroyVariant(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variant->delete();

        $this->generatePricelist();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(['message' => 'Product variant deleted']);
        else {
            //Web Response
            return Redirect::back()->with('success', 'Product variant deleted!');
        }
    }

    public function createVariant(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        return Inertia::render('Products/Variants/Create', [
            'product' => new ProductResource($product),
        ]);
    }

    public function storeVariant(Request $request)
    {
        $request->validate([
            'id' => ['required'],
            'variant_name' => ['required'],
            'description' => ['required'],
            'cost' => ['required'],
            'cost_original' => ['required'],
        ]);

        $variant = ProductVariant::create([
            "name" => $request->variant_name,
            "description" => $request->description,
            "slug" => $this->uniqueSlug(ProductVariant::class, $request->description),
            "unit" => $request->unit,
            "quantity" => $request->quantity,
            "cost" => $request->cost,
            "cost_original" => $request->cost_original,
            "product_id" => $request->id,
            "description_full" => $request->description_full,
            "about" => $request->about,
            "featured" => $request->boolean('featured'),
            "transport_inclusive" => $request->boolean('transport_inclusive'),
            "specifications" => json_encode($request->specifications ?? []),
            "product_information" => json_encode($request->product_information ?? []),
        ]);

        $this->syncVariantPhotos($variant, $request->photos ?? []);

        $this->generatePricelist();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ProductVariantResource($variant), 201);
        else {
            //Web Response
            return Redirect::route('products.show', ['id' => $request->id])->with('success', 'Product variant added!');
        }
    }

    public function editVariantPage(Request $request, $id)
    {
        $variant = ProductVariant::findOrFail($id);

        return Inertia::render('Products/Variants/Edit', [
            'variant' => new ProductVariantResource($variant),
        ]);
    }

    public function updateVariant(Request $request, $id)
    {
        $request->validate([
            'variant_name' => ['required'],
            'description' => ['required'],
            'cost' => ['required'],
            'cost_original' => ['required'],
        ]);

        $variant = ProductVariant::findOrFail($id);

        $variant->update([
            "name" => $request->variant_name,
            "description" => $request->description,
            "unit" => $request->unit,
            "quantity" => $request->quantity,
            "cost" => $request->cost,
            "cost_original" => $request->cost_original,
            "description_full" => $request->description_full,
            "about" => $request->about,
            "featured" => $request->boolean('featured'),
            "transport_inclusive" => $request->boolean('transport_inclusive'),
            "specifications" => json_encode($request->specifications ?? []),
            "product_information" => json_encode($request->product_information ?? []),
        ]);

        if ($request->filled('removed_photo_ids')) {
            ProductVariantPhoto::where('product_variant_id', $variant->id)
                ->whereIn('id', $request->removed_photo_ids)
                ->delete();
        }

        $this->syncVariantPhotos($variant, $request->new_photos ?? []);

        $this->generatePricelist();

        if ((new AppController())->isApi($request))
            //API Response
            return response()->json(new ProductVariantResource($variant));
        else {
            //Web Response
            return Redirect::route('products.show', ['id' => $variant->product_id])->with('success', 'Product variant updated!');
        }
    }

    /**
     * Append new photo paths to a variant's gallery, then re-point the
     * legacy single `photo` column at the first photo (by sort order) so
     * older read paths (invoices, pricelists, etc.) keep working.
     */
    private function syncVariantPhotos(ProductVariant $variant, array $newPhotoPaths): void
    {
        $nextOrder = (int) ($variant->photos()->max('sort_order') + 1);

        foreach ($newPhotoPaths as $path) {
            if (!$path) {
                continue;
            }

            ProductVariantPhoto::create([
                "product_variant_id" => $variant->id,
                "path" => $path,
                "sort_order" => $nextOrder,
            ]);
            $nextOrder++;
        }

        $coverPhoto = $variant->photos()->orderBy('sort_order')->first();
        $variant->update(['photo' => $coverPhoto?->path]);
    }

    private function uniqueSlug(string $class, string $source, $ignoreId = null)
    {
        $base = Str::slug($source);
        $slug = $base;
        $suffix = 2;

        while ($class::where('slug', $slug)->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))->exists()) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    private function generatePricelist()
    {

        $products = Product::where('name', "!=", "Other")
            ->where('name', "!=", "Services")
            ->get();
        $filename = "seposale_pricelist.pdf";

        $pdf = PDF::loadView('pricelist', [
            'products' => $products->chunk(2),
        ]);

        $filename = public_path('files') . "/seposale_pricelist_" . date('Y-m') . ".pdf";

        $pdf->save($filename);
    }
}
