<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Brand;
use App\Models\Product;

class IndexController extends Controller
{
    public function index()
    {
        //query builder
        //$query = \DB::table('brands');
        
        // 1 red iz tabele
        //dd($query->first());
        
        //kolekciju redova iz tabele
        
//        $query->orderBy('name', 'DESC')
//                ->orderBy('created_at')
//                ->where('name', 'LIKE', '%7')
//                ->where('id', '>', 6)
//                ->whereIn('id', [4, 6, 9])
//        ;
//        
//        dd($query->toSql());
//        
//        $brands = $query->get()->toArray();
//        
//        dd($brands);
        
        
//        $brands = \DB::table('brands')
//                ->orderBy('name', 'ASC')
//                ->get()->toArray();
        
        
        //$brands = Brand::all();  // dobijanje kolekcije svih redova iz tabele
        $brands = Brand::query() // dobijanje query builder-a koji je automatski vezan za tabelu
                ->orderBy('name')
                ->get();
        
        //dd($brands);
        
        //JEDAN RED IZ BAZE JE OBJEKAT KLASE MODELA
        //$brand = Brand::first();// prvi red iz tabele
        //
        //PRISTUPANJE KOLONAMA
        //dd($brand->id);//pristup jednoj koloni
        //dd($brand['id']);
        
        //DOBIJANJE REDA PO ID-u
        //$brand = Brand::query()->where('id', 3)->first();
        //$brand = Brand::find(3); // pretraga po primarnom kljucu
        //dd($brand->name);
        
        $newArrivals = Product::query()
                ->newArrivals() //upotreba local scope-a
                ->limit(10)
                ->with(['brand'])
                ->get();
        
        //dd($newArrivals);
        
        return view('front.index.index', [
            'newArrivalProducts' => $newArrivals,
            'brands' => $brands,
        ]);
    }
}
