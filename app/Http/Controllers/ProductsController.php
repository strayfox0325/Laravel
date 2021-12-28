<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        
        //$request = request();
        
        //dd($request);
        
        
        $formData = $request->validate([
            'product_category_id' => ['nullable', 'array', 'exists:product_categories,id'],
            'brand_id' => ['nullable', 'array', 'exists:brands,id'],
            'sort_by' => ['nullable', 'string', 'in:G,W,X,Q'],
        ]);
        
        //dd($formData);
        
        $productCategories = ProductCategory::query()
            ->orderBy('priority')
            ->withCount(['products']) // ovo ce dati property products_count
            ->get();
        
        $brands = Brand::query()
            ->orderBy('name', 'ASC')
            ->withCount(['products'])
            ->get();
        
        
        $productsQuery = Product::query()
            ->with(['productCategory', 'brand']); //smestiti query builder u varijablu
        
        if (isset($formData['product_category_id'])) {
            
            $productsQuery->whereIn('product_category_id', $formData['product_category_id']);
        }
        
        if (isset($formData['brand_id'])) {
            
            $productsQuery->whereIn('brand_id', $formData['brand_id']);
        }
        
        if (isset($formData['sort_by'])) {
            
            switch ($formData['sort_by']) {
                case 'W':
                    $productsQuery->orderBy('created_at', 'ASC');
                    break;
                
                case 'X':
                    $productsQuery->orderBy('price', 'ASC');
                    break;
                
                case 'Q':
                    $productsQuery->orderBy('price', 'DESC');
                    break;
                
                default:
                    $productsQuery->orderBy('created_at', 'DESC');
                    break;
            }
            
        } else {
            //ako nije prosledjen sort_by po defaultu neka budu prvo najnoviji proizvodi
            $productsQuery->orderBy('created_at', 'DESC');
        }
        
        $products = $productsQuery->paginate(10);
        //prilikom formiranja linkova za paginaciju
        //smesti u url za svaku stranicu sve sto je bilo sa forme!!!
        $products->appends($formData);
        //poziv funkcije appends ce se odraziti kasnije u view skripti 
        //kada se pozove ->links() funkcija
        
        
        return view('front.products.index', [
            'products' => $products,
            'productCategories' => $productCategories,
            'brands' => $brands,
            'formData' => $formData,
        ]);
    }
    
    public function single(Request $request, Product $product)
    {
        //dd($request, $product);
        //dd($id);
        
        //$product = Product::find($id); // vraca se NULL ako se ne nadje red po ID-ju
        
        //$product = Product::findOrFail($id); // baca se ModelNotFoundException ako nema reda u bazi
        //dd($product);
        
        
        //relacija se definise kao funkcija
        //a koristi se kao PROPERTY koji je isti kao funkcija!!!
        //dd($product->productCategory->name, $product);
        
        //dd($product->sizes);
        
        
        //$response = response();
        
        //return $response->json(['test' => 'Cubes']);
        
        //return redirect()->away('http://cubes.edu.rs');
        
        $relatedProducts = Product::query()
            ->where('product_category_id', '=', $product->product_category_id)
            ->where('id', '!=', $product->id)
            ->take(10)
            ->latest()
            ->get();
        
        /*
        //dohvati povezanu kategoriju
        $productCategory = $product->productCategory;
        //iz povezane kategorije dohvati relaciju kao Query Builder
        //i nadovezi jos par uslova
        $relatedProducts = $productCategory->products() //vraca Query Builder
                ->where('id', '!=', $product->id)
                ->latest()
                ->take(10)
                ->get()
                ;
        */
        
        return view('front.products.single', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
