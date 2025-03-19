<?php

namespace App\Http\Controllers\Api;

use App\Models\Brands;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\Condation;
use App\Models\WhishList;
use App\Models\NumberOfUse;
use App\Models\SilderImages;
use Illuminate\Http\Request;
use App\Models\Certification;
use App\Models\ProductVaraint;
use App\Traits\ProductHelperTrait;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    use ProductHelperTrait;
    protected function getDiscountMessage($discounts)
    {
        foreach ($discounts as $discount) {
            if ($discount->discount_expiration_status === 'active') {
                $now = now();
                $remainingTime = $discount->end_date->diff($now);
                return [
                    'percentage' => $discount->discount_percentage,
                    'message' => "{$discount->discount_percentage}% discount for {$remainingTime->d} days {$remainingTime->h} hours {$remainingTime->i} minutes remaining!"
                ];
            }
        }
        return null;
    }

        public function getClasses()
    {
        return collect([
            ['id' => 1, 'name' => 'Class A-1'],
            ['id' => 2, 'name' => 'Class B-2'],
            ['id' => 3, 'name' => 'Class C-3'],
            ['id' => 4, 'name' => 'Class D-4'],
            ['id' => 5, 'name' => 'Class E-5'],
        ]);
    }



    public function getDropDownData(Request $request)
    {
        try {
            // return $request;
            // Get the distinct dropdown data
            $countryOfManufacture = Product::where('status', '1')->where('is_draft', 1)->select('country')->distinct()->get();
            $categories = Category::with('discounts')->where('status', '1')->where('is_draft', 1)->select('id', 'name')->get();
            $brands = Brands::with('discounts')->where('status', '1')->where('is_draft', 1)->select('id', 'name')->get();
            $certifications = Certification::where('status', '1')->where('is_draft', 1)->select('id', 'name')->get();
            $company = Company::where('status', '1')->where('is_draft', 1)->select('id', 'name')->get();

            $warrantyPeriods = Product::where('status', '1')->where('is_draft', 1)->select('warranty_period')->distinct()->get();
            $numberOfUses = NumberOfUse::where('status', '1')->where('is_draft', 1)->get();
            $conditions = Condation::all();
            $class = $this->getClasses();
            // return $class;
            $featureProducts = Product::with([
                'productBrands.brands:id,name',
                'productCategory.categories:id,name',
            ])->select('id', 'thumbnail_image', 'short_name', 'short_description')
                ->where('product_status', 'Featured Product')
                ->where('status', '1')->where('is_draft', 1)
                ->latest()

                ->get();
            $featureProducts =  $featureProducts->map(function ($featureProduct) {
                $discountMessage = $this->getDiscountMessage($featureProduct->discounts);
                $featureProduct->discount_percentage = $discountMessage ? $discountMessage['percentage'] : null;
                $featureProduct->discount_message = $discountMessage ? $discountMessage['message'] : null;
                return $featureProduct;
            });
            $silders = SilderImages::where('status', '0')->select('id', 'images')->latest()->get();

            // If no featured products found, return a failure response
            if ($featureProducts->isEmpty()) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Feature Product Not Found!'
                ], 404);
            }
            // $filterCounts = $this->getFilterCounts();
            $filters = [
                'min_price', 'max_price', 'company', 'brand_id', 'category_id',
                'certification_id', 'country', 'user_id', 'product_id',
                'key_words', 'available_product', 'suggested_word', 'price_order',
                'product_class','product_condition','warranty_period','product_use_status',
                'search'
            ];

            $hasFilters = false;

            // Check if at least one filter is present in request
            foreach ($filters as $filter) {
                if ($request->has($filter) && !empty($request->input($filter))) {
                    $hasFilters = true;
                    break;
                }
            }

            // Call function only if request is not empty
            if ($hasFilters) {
                $filterCounts = $this->getFilterCountsReq($request);
            } else {
                $filterCounts = $this->getFilterCounts();
            }
            // return $filterCounts;

            // Append counts to dropdown data in desired structure
            $countryOfManufacture = $countryOfManufacture->map(function ($country) use ($filterCounts) {
                $count = $filterCounts['countries'][$country->country] ?? 0;
                return ['country' => $country->country, 'count' => $count];
            });

            $conditions = $conditions->map(function ($condition) use ($filterCounts) {
                $count = $filterCounts['productCondition'][$condition->name] ?? 0;
                return ['productCondition' => $condition->name, 'count' => $count];
            });

            $numberOfUses = collect($numberOfUses)->map(function ($uses) use ($filterCounts) {
                $name = is_array($uses) ? ($uses['name'] ?? null) : ($uses->name ?? null);
                $count = $filterCounts['numberOfUse'][$name] ?? 0; // Fix key and access properly
                return ['numberOfUses' => $name, 'count' => $count];
            });
            $warrantyPeriods = $warrantyPeriods->map(function ($period) use ($filterCounts) {
                $count = $filterCounts['warrantyPeriod'][$period->warranty_period] ?? 0;
                return ['warrantyPeriod' => $period->warranty_period, 'count' => $count];
            });
            $class = collect($class)->map(function ($data) use ($filterCounts) {
                $count = $filterCounts['productClass'][$data['name']] ?? 0; // Change ->name to ['name']
                return ['productClass' => $data['name'], 'count' => $count];
            });
            $categories = $categories->map(function ($category) use ($filterCounts) {
                $count = $filterCounts['categories'][$category->id] ?? 0;
                $discountMessage = $this->getDiscountMessage($category->discounts);
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'count' => $count,
                    'discount_percentage' => $discountMessage ? $discountMessage['percentage'] : null,
                    'discount_message' => $discountMessage ? $discountMessage['message'] : null,
                ];
            });

            // Process brands with discounts
            $brands = $brands->map(function ($brand) use ($filterCounts) {
                $count = $filterCounts['brands'][$brand->id] ?? 0;
                $discountMessage = $this->getDiscountMessage($brand->discounts);

                return [
                    'id' => $brand->id,
                    'name' => $brand->name,
                    'count' => $count,
                    'discount_percentage' => $discountMessage ? $discountMessage['percentage'] : null,
                    'discount_message' => $discountMessage ? $discountMessage['message'] : null,
                ];
            });

            $certifications = $certifications->map(function ($certification) use ($filterCounts) {
                $count = $filterCounts['certifications'][$certification->id] ?? 0;
                return ['id' => $certification->id, 'name' => $certification->name, 'count' => $count];
            });

            $companies = $company->map(function ($company) use ($filterCounts) {
                $count = $filterCounts['companies'][$company->name] ?? 0;
                return ['id' => $company->id, 'name' => $company->name, 'count' => $count];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'silders' => $silders,
                    'countries_of_manufacture' => $countryOfManufacture,
                    'categories' => $categories,
                    'brands' => $brands,
                    'productCondition' => $conditions,
                    'productUseStatus' => $numberOfUses,
                    'warrantyPeriod' => $warrantyPeriods,
                    'certifications' => $certifications,
                    'productClass' => $class,
                    'companies' => $companies,
                    'featureProducts' => $featureProducts,
                ]
            ], 200);
        } catch (\Exception $e) {
            // Handle errors
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching data: ' . $e->getMessage()
            ], 500);
        }
    }
    private function getFilterCounts()
    {
        return [
                'countries' => Product::where('status', '1')
                ->where('is_draft', 1)
                ->selectRaw('country, COUNT(*) as count')
                ->groupBy('country')
                ->pluck('count', 'country')
                ->toArray(),

                'productClass' => Product::where('status', '1')
                ->where('is_draft', 1)
                ->selectRaw('product_class, COUNT(*) as count')
                ->groupBy('product_class')
                ->pluck('count', 'product_class')
                ->toArray(),

                'productCondition' => Product::where('status', '1')
                ->where('is_draft', 1)
                ->selectRaw('product_condition, COUNT(*) as count')
                ->groupBy('product_condition')
                ->pluck('count', 'product_condition')
                ->toArray(),

                'warrantyPeriod' => Product::where('status', '1')
                ->where('is_draft', 1)
                ->whereNotNull('warranty_period') // Ensures column is not null
                ->selectRaw('warranty_period, COUNT(*) as count')
                ->groupBy('warranty_period')
                ->pluck('count', 'warranty_period')
                ->toArray(),


                'numberOfUse' => Product::where('status', '1')
                ->where('is_draft', 1)
                ->selectRaw('product_use_status, COUNT(*) as count')
                ->groupBy('product_use_status')
                ->pluck('count', 'product_use_status')
                ->toArray(),

            'brands' => Product::with('productBrands')
                ->selectRaw('product_brands.brand_id, COUNT(*) as count')
                ->join('product_brands', 'products.id', '=', 'product_brands.product_id')
                ->where('products.status', '1')
                ->where('products.is_draft', 1)
                ->groupBy('product_brands.brand_id')
                ->pluck('count', 'product_brands.brand_id')
                ->toArray(),

            'categories' => Product::with('productCategory')
                ->selectRaw('product_catgeories.category_id, COUNT(*) as count')
                ->join('product_catgeories', 'products.id', '=', 'product_catgeories.product_id')
                ->where('products.status', '1')
                ->where('products.is_draft', 1)
                ->groupBy('product_catgeories.category_id')
                ->pluck('count', 'product_catgeories.category_id')
                ->toArray(),

            'certifications' => Product::with('productCertifications')
                ->selectRaw('product_certifcations.certification_id, COUNT(*) as count')
                ->join('product_certifcations', 'products.id', '=', 'product_certifcations.product_id')
                ->where('products.status', '1')
                ->where('products.is_draft', 1)
                ->groupBy('product_certifcations.certification_id')
                ->pluck('count', 'product_certifcations.certification_id')
                ->toArray(),


            'companies' => Product::where('status', '1')
                ->where('is_draft', 1)
                ->selectRaw('company, COUNT(*) as count')
                ->groupBy('company')
                ->pluck('count', 'company')
                ->toArray(),
        ];
    }
    private function getFilterCountsReq($request)
{
    $query = Product::where('status', '1')->where('is_draft', 1);

    // Apply filters dynamically
    if ($request->has('key_words')) {
        $search = $request->input('key_words');
        $query->where('short_name', 'like', '%' . $search . '%');
    }

    if ($request->has('min_price') && $request->has('max_price')) {
        $query->whereBetween('price', [$request->input('min_price'), $request->input('max_price')]);
    }
    if ($request->has('company')) {
        $companies = explode(',', $request->input('company'));
        $query->whereIn('company', $companies);
    }
    if ($request->has('brand_id')) {
        $brandIds = explode(',', $request->input('brand_id'));
        $query->whereHas('productBrands', function ($q) use ($brandIds) {
            $q->whereIn('brand_id', $brandIds);
        });
    }
    if ($request->has('category_id')) {
        $categoryIds = explode(',', $request->input('category_id'));
        $query->whereHas('productCategory', function ($q) use ($categoryIds) {
            $q->whereIn('category_id', $categoryIds);
        });
    }
    if ($request->has('certification_id')) {
        $certificationIds = explode(',', $request->input('certification_id'));
        $query->whereHas('productCertifications', function ($q) use ($certificationIds) {
            $q->whereIn('certification_id', $certificationIds);
        });
    }
    if ($request->has('country')) {
        $countries = explode(',', $request->input('country'));
        $query->whereIn('country', $countries);
    }
    if ($request->has('product_class')) {
        $countries = explode(',', $request->input('product_class'));
        $query->whereIn('product_class', $countries);
    }
    if ($request->has('product_condition')) {
        $countries = explode(',', $request->input('product_condition'));
        $query->whereIn('product_condition', $countries);
    }
    if ($request->has('product_use_status')) {
        $countries = explode(',', $request->input('product_use_status'));
        $query->whereIn('product_use_status', $countries);
    }
    if ($request->has('warranty_period')) {
        $countries = explode(',', $request->input('warranty_period'));
        $query->whereIn('warranty_period', $countries);
    }

    return [
            'countries' => (clone $query)->selectRaw('country, COUNT(*) as count')
            ->groupBy('country')
            ->pluck('count', 'country')
            ->toArray(),

            'productClass' => (clone $query)->selectRaw('product_class, COUNT(*) as count')
            ->groupBy('product_class')
            ->pluck('count', 'product_class')
            ->toArray(),

            'productCondition' => (clone $query)->selectRaw('product_condition, COUNT(*) as count')
            ->groupBy('product_condition')
            ->pluck('count', 'product_condition')
            ->toArray(),

            'warrantyPeriod' => (clone $query)->selectRaw('warranty_period, COUNT(*) as count')
            ->whereNotNull('warranty_period')
            ->groupBy('warranty_period')
            ->pluck('count', 'warranty_period')
            ->toArray(),

            'numberOfUse' => (clone $query)->selectRaw('product_use_status, COUNT(*) as count')
            ->groupBy('product_use_status')
            ->pluck('count', 'product_use_status')
            ->toArray(),

        'brands' => (clone $query)->join('product_brands', 'products.id', '=', 'product_brands.product_id')
            ->selectRaw('product_brands.brand_id, COUNT(*) as count')
            ->groupBy('product_brands.brand_id')
            ->pluck('count', 'product_brands.brand_id')
            ->toArray(),

        'categories' => (clone $query)->join('product_catgeories', 'products.id', '=', 'product_catgeories.product_id')
            ->selectRaw('product_catgeories.category_id, COUNT(*) as count')
            ->groupBy('product_catgeories.category_id')
            ->pluck('count', 'product_catgeories.category_id')
            ->toArray(),

        'certifications' => (clone $query)->join('product_certifcations', 'products.id', '=', 'product_certifcations.product_id')
            ->selectRaw('product_certifcations.certification_id, COUNT(*) as count')
            ->groupBy('product_certifcations.certification_id')
            ->pluck('count', 'product_certifcations.certification_id')
            ->toArray(),

        'companies' => (clone $query)->selectRaw('company, COUNT(*) as count')
            ->groupBy('company')
            ->pluck('count', 'company')
            ->toArray(),
    ];
}



    public function getFilteredProducts(Request $request)
    {
        try {
            $minPrice = $request->input('min_price');
            $maxPrice = $request->input('max_price');
            $companies = $request->input('company') ? explode(',', $request->input('company')) : [];
            $brandIds = $request->input('brand_id') ? explode(',', $request->input('brand_id')) : [];
            $categoryIds = $request->input('category_id') ? explode(',', $request->input('category_id')) : [];
            $certificationIds = $request->input('certification_id') ? explode(',', $request->input('certification_id')) : [];
            $countries = $request->input('country') ? explode(',', $request->input('country')) : [];
            // $numberOfUses = $request->input('product_use_status') ? explode(',', $request->input('product_use_status')) : [];
            $numberOfUses = is_string($request->input('product_use_status'))
                                        ? explode(',', $request->input('product_use_status'))
                                            : (array) $request->input('product_use_status');
            $productClass = $request->input('product_class') ? explode(',', $request->input('product_class')) : [];
            $warrantyPeriod = $request->input('warranty_period') ? explode(',', $request->input('warranty_period')) : [];
            $productCondition = $request->input('product_condition') ? explode(',', $request->input('product_condition')) : [];
            $userId = $request->input('user_id');
            $productId = $request->input('product_id');
            $searchByWords = $request->input('key_words');
            $availability = $request->input('available_product');
            $suggestedWords = $request->input('suggested_word');
            $priceRange = $request->input('price_order');
            $page = $request->input('page', 1);
            $perPage = 6;
            $offset = ($page - 1) * $perPage;

            // Get currency and handle errors
            $currency = $this->getCurrency();
            if (!$currency) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No Currency found.',
                ]);
            }
            $pkrAmount = $currency->pkr_amount;

            // Suggest words based on input
            if ($suggestedWords) {
                $suggestedWordsList = Product::selectRaw('DISTINCT(short_name)')
                    ->where('short_name', 'like', '%' . $suggestedWords . '%')
                    ->pluck('short_name')
                    ->toArray();

                return count($suggestedWordsList) > 0
                    ? response()->json(['suggested_words' => $suggestedWordsList], 200)
                    : response()->json(['message' => 'No words found!'], 404);
            }

            // Build the query with eager loading and filters
            $query = Product::with([
                'productBrands.brands:id,name',
                'productCategory.categories:id,name',
                'productCertifications.certification:id,name',
                'productVaraint' => function ($query) {
                    $query->select('product_id', 'selling_price_per_unit', 'status');
                }
            ])->select(
                'products.id',
                'products.product_code',
                'products.thumbnail_image',
                'products.short_name',
                'products.country',
                'products.company',
                'products.models',
                'products.product_use_status',
                'products.short_description',
                'products.sterilizations'
            )
                ->where('products.status', '1')->where('is_draft', 1);

            // Apply filters
            if ($minPrice && $maxPrice) {
                $query->whereHas('productVaraint', function ($variantPrice) use ($minPrice, $maxPrice) {
                    $variantPrice->whereBetween('selling_price_per_unit', [$minPrice, $maxPrice]);
                });
            }
            if (!empty($companies)) {
                $query->whereIn('company', $companies);
            }
            if (!empty($brandIds)) {
                $query->whereHas('productBrands', function ($q) use ($brandIds) {
                    $q->whereIn('brand_id', $brandIds);
                });
            }
            if (!empty($categoryIds)) {
                $query->whereHas('productCategory', function ($q) use ($categoryIds) {
                    $q->whereIn('category_id', $categoryIds);
                });
            }
            if (!empty($certificationIds)) {
                $query->whereHas('productCertifications', function ($q) use ($certificationIds) {
                    $q->whereIn('certification_id', $certificationIds);
                });
            }
            if (!empty($countries)) {
                $query->whereIn('country', $countries);
            }
            if (!empty($numberOfUses)) {
                $query->whereIn('product_use_status', $numberOfUses);
            }
            if (!empty($productClass)) {
                $query->whereIn('product_class', $productClass);
            }
            if (!empty($warrantyPeriod)) {
                $query->whereIn('warranty_period', $warrantyPeriod);
            }
            if (!empty($productCondition)) {
                $query->whereIn('product_condition', $productCondition);
            }

            if ($searchByWords) {
                $query->where('short_name', 'like', '%' . $searchByWords . '%');
            }
            if ($availability) {
                $query->whereHas('productVaraint');
            }

            // Clone query to get total count
            $totalProductsQuery = clone $query;
            $totalProducts = $totalProductsQuery->count();

            // Apply sorting
            if ($priceRange === 'low_to_high') {
                $query->addSelect([
                    'min_variant_price' => ProductVaraint::select('selling_price_per_unit')
                        ->whereColumn('product_id', 'products.id')
                        ->orderBy('selling_price_per_unit', 'asc')
                        ->limit(1)
                ])->orderBy('min_variant_price', 'asc');
            } elseif ($priceRange === 'high_to_low') {
                $query->addSelect([
                    'max_variant_price' => ProductVaraint::select('selling_price_per_unit')
                        ->whereColumn('product_id', 'products.id')
                        ->orderBy('selling_price_per_unit', 'desc')
                        ->limit(1)
                ])->orderBy('max_variant_price', 'desc');
            }


            // Paginate the results
            $products = $query->orderBy('products.created_at', 'desc')
                ->skip($offset)
                ->take($perPage)
                ->get();
            $products = $products->map(function ($product) {
                $discountMessage = $this->getDiscountMessage($product->discounts);
                $product->discount_percentage = $discountMessage ? $discountMessage['percentage'] : null;
                $product->discount_message = $discountMessage ? $discountMessage['message'] : null;
                return $product;
            });
            if ($products->isEmpty()) {
                return response()->json(['products' => []]);
            }

            // Calculate price ranges and other product details
            $products->each(function ($product) use ($pkrAmount) {
                $variantPrices = $product->productVaraint->pluck('selling_price_per_unit')->map(function ($price) use ($pkrAmount) {
                    return $price * $pkrAmount;
                });
                if ($variantPrices->count() == 1) {
                    $product->min_price_range_pkr = $variantPrices->first();
                } else {
                    $product->min_price_range_pkr = $variantPrices->min();
                    $product->max_price_range_pkr = $variantPrices->max();
                }
                $product->variant_count = $product->productVaraint->count();
                unset($product->productVaraint);
            });

            // Get wishlist products
            $favoritesProducts = WhishList::whereIn('product_id', $products->pluck('id'))->get();
            foreach ($products as $product) {
                $productLikes = $favoritesProducts->where('product_id', $product->id)->pluck('user_id');
                $product->likes = $productLikes->isEmpty() ? [] : $productLikes->toArray();
                if ($userId && $productId && $productId == $product->id) {
                    $wishlist = WhishList::where([
                        'user_id' => $userId,
                        'product_id' => $productId,
                    ])->first();
                    if ($wishlist) {
                        $wishlist->delete();
                        return response()->json([
                            'success' => 'Wishlist item removed successfully!',
                        ], 200);
                    } else {
                        $wishlist = WhishList::create([
                            'user_id' => $userId,
                            'product_id' => $productId,
                        ]);
                        return response()->json([
                            'success' => 'Wishlist added successfully!',
                        ], 200);
                    }
                }
            }
            return response()->json([
                'status' => 'success',
                'products' => $products,
                'pkrAmount' => $pkrAmount,
                'totalProducts' => $totalProducts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while fetching products: ' . $e->getMessage()
            ], 500);
        }
    }
}
