<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Models\Brand;

class BrandsController extends Controller
{
    public function index()
    {
        //$systemMessage = session()->pull('system_message');
        
        $brands = Brand::all();
        
        return view('admin.brands.index', [
            'brands' => $brands,
            //'systemMessage' => $systemMessage,
        ]);
    }
    
    public function add(Request $request)
    {
        return view('admin.brands.add', [
            
        ]);
    }
    
    public function insert(Request $request)
    {
        
        $formData = $request->validate([
            'name' => ['required', 'string', 'max:10', 'unique:brands,name'],
            'website' => ['nullable', 'string', 'max:255', 'url'],
            'photo' => ['nullable', 'file', 'image', 'max:65000'],
        ]);
        
        // novi model u memoriji, jos nije sacuvan u bazi
        $newBrand = new Brand();
        
        //setovanje kolona u redu tabele
        //
        //jedna kolona po jedna kolona
        //$newBrand->name = $formData['name'];
        
        //MASS ASIGNMENT - vise kolona od jednom
        $newBrand->fill($formData);
        
        //objekat pre snimanja u bazu
        //dump($newBrand);
        
        //save funkcija vrsi cuvanje promena nad redom u tabeli 
        $newBrand->save(); // radi se INSERT QUERY nad bazom zato sto smo rucno kreirali objekat sa "new"
        
        //objekat nakon snimanja u bazu
        //dd($newBrand);
        
        if ($request->hasFile('photo')) {
            //fajl "photo" je poslat
            $photoFile = $request->file('photo');
            
            //novi naziv fajla, dodajem na pocetak ID brenda
            $photoFileName = $newBrand->id . '_' . $photoFile->getClientOriginalName();
            
            $photoFile->move(
                public_path('/storage/brands/'),
                $photoFileName
            );
            
            $newBrand->photo = $photoFileName;
            
            $newBrand->save();
            
            //kadriranje slike na 300x141
            \Image::make(public_path('/storage/brands/' . $newBrand->photo))
                ->fit(300, 141)
                ->save();
        }
        
        
        session()->flash('system_message', __('New brand has been saved!'));
        
        return redirect()->route('admin.brands.index');
    }
    
    public function edit(Request $request, Brand $brand)
    {
        return view('admin.brands.edit', [
            'brand' => $brand
        ]);
    }
    
    public function update(Request $request, Brand $brand)
    {
        $formData = $request->validate([
            'name' => ['required', 'string', 'max:10', Rule::unique('brands')->ignore($brand->id),],
            'website' => ['nullable', 'string', 'max:255', 'url'],
            'photo' => ['nullable', 'file', 'image', 'max:65000'],
        ]);
        
        $brand->fill($formData);
        
        $brand->save();//UPDATE QUERY nad bazom, zato sto je red vec nekako dobijen iz baze
        
        if ($request->hasFile('photo')) {
            
            //brisanje stare slike
            $brand->deletePhoto();
            
            $photoFile = $request->file('photo');
            
            $photoFileName = $brand->id . '_' . $photoFile->getClientOriginalName();
            
            $photoFile->move(
                public_path('/storage/brands/'),
                $photoFileName
            );
            
            $brand->photo = $photoFileName;
            $brand->save();
            
            //kadriranje slike na 300x141
            \Image::make(public_path('/storage/brands/' . $brand->photo))
                ->fit(300, 141)
                ->save();
        }
        
        session()->flash('system_message', __('Brand has been saved'));
        
        return redirect()->route('admin.brands.index');
    }
    
    public function delete(Request $request)
    {
        $formData = $request->validate([
            'id' => ['required', 'numeric', 'exists:brands,id'],
        ]);
        
        $formData['id'];
        
        $brand = Brand::findOrFail($formData['id']);
        
        //$brand->products; //relacija kao property -> kolekciju iz baze
        //$brand->products(); // relacija kao funkcija -> query builder
        //Product::query()->where('brand_id', $brand->id); // isto kao prethodni izraz
        //Product::query()->where('brand_id', $brand->id)->count(); // broj redova povezanog modela
        
        if ($brand->products()->count() > 0) {
            //throw new \Exception('There are products with brand: ' . $brand->name);
            
            session()->flash('system_error', __('There are products in brand :brand_name', ['brand_name' => $brand->name]));
            
            return redirect()->back();
        }
        
        
        //brisanje reda iz baze preko Objekta
        $brand->delete();
        
        //brisanje pratecih fajlova
        $brand->deletePhoto();
        
        session()->flash('system_message', __('Brand has been deleted'));
        
        return redirect()->route('admin.brands.index');
    }
    
    public function deletePhoto(Brand $brand)
    {
        $brand->deletePhoto(); //brisanje slike sa fajl sistema
        
        $brand->photo = null; //postavi kolonu photo da bude prazna
        $brand->save(); //sacuvaj izmene u bazi
        
        //session()->flash('system_message', __('Photo has been deleted'));
        
        //return redirect()->route('admin.brands.edit', ['brand' => $brand->id]);
        
        return response()->json([
            
            "system_message" => __('Photo has been deleted'),
            "photo_url" => $brand->getPhotoUrl(), //vratice se defaultni url 
        ]);
    }
}
