<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Models\Product;

use App\Models\ProductCategory;
use App\Models\Size;

class ProductsController extends Controller
{
    public function index()
    {
        //$systemMessage = session()->pull('system_message');
        
        /* $products = Product::query()
            ->with(['brand', 'productCategory', 'sizes'])
            ->orderBy('created_at','desc')
            ->get(); */
        
        return view('admin.products.index', [
            //'products' => $products,
            //'systemMessage' => $systemMessage,
        ]);
    }
    
    public function datatable(Request $request){
        $query = Product::query()
        ->with(['brand', 'productCategory', 'sizes']);
    
    //Inicijalizacija datatables-a
    $dataTable = \DataTables::of($query);
    
    //Podesavanje kolona
    $dataTable->addColumn('sizes', function ($product) {
        return optional($product->sizes->pluck('name'))->join(', ');
    })
    ->addColumn('brand_name', function ($product) {
        return optional($product->brand)->name;
    })
    ->addColumn('product_category_name', function ($product) {
        return optional($product->brand)->name;
    })
    ->addColumn('actions', function ($product) {
        return view('admin.products.partials.actions', ['product' => $product]);
    })
    ->editColumn('photo1', function ($product) {
        return view('admin.products.partials.product_photo', ['product' => $product]);
    })
    ->editColumn('id', function ($product) {
        return '#' . $product->id;
    })
    ->editColumn('name', function ($product) {
        return '<strong>' . e($product->name) . '</strong>';
    });
    
    
    $dataTable->rawColumns(['name', 'photo1', 'actions']);
    
    return $dataTable->make(true); //make - pravi json po specifikaciji DataTables.js plugin-a	
}


    public function add(Request $request)
    {
        
        $productCategories = ProductCategory::query()
            ->orderBy('priority')
            ->get();
        
        $sizes = Size::all();
        
        return view('admin.products.add', [
            'productCategories' => $productCategories,
            'sizes' => $sizes,
        ]);
    }
    
    public function insert(Request $request)
    {
        
        $formData = $request->validate([
            'brand_id' => ['required', 'numeric', 'exists:brands,id'],
            'product_category_id' => ['required', 'numeric', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255', 'unique:products,name'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'old_price' => [
                'nullable', 
                'numeric', 
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) { // use: upucavanje spoljnih varijabli u scope anonimne funkcije
                    if ($value <= $request->input('price')) {
                        $fail(__('Old price must be greater than price'));
                    }
                },
            ],
            'index_page' => ['required', 'numeric', 'in:0,1'],
            'size_id' => ['required', 'array', 'exists:sizes,id', 'min:2'],
            'photo1' => ['nullable', 'file', 'image'],
            'photo2' => ['nullable', 'file', 'image'],
        ]);
                
        //dd($formData);
        
        // novi model u memoriji, jos nije sacuvan u bazi
        $newProduct = new Product();
        
        //setovanje kolona u redu tabele
        //
        //jedna kolona po jedna kolona
        //$newProduct->name = $formData['name'];
        
        //MASS ASIGNMENT - vise kolona od jednom
        $newProduct->fill($formData);
        
        //dd($newProduct);
        
        //objekat pre snimanja u bazu
        //dump($newProduct);
        
        //save funkcija vrsi cuvanje promena nad redom u tabeli 
        $newProduct->save(); // radi se INSERT QUERY nad bazom zato sto smo rucno kreirali objekat sa "new"
        
        //$formData['size_id'] = [3,6,9];
        //$newProduct->id - 101
        
        /*
         product_sizes:
         product_id,    size_id
         101,           3
         101,           6
         101,           9
         */
        
        //sync funkcija nad relacijom sluzi za odrzavanje veze vise na vise
        $newProduct->sizes()->sync($formData['size_id']);
        
        
		$this->handlePhotoUpload('photo1', $request, $newProduct);
		$this->handlePhotoUpload('photo2', $request, $newProduct);
        
        
        //objekat nakon snimanja u bazu
        //dd($newProduct);
        
        session()->flash('system_message', __('New product has been saved!'));
        
        return redirect()->route('admin.products.index');
    }
    
    public function edit(Request $request, Product $product)
    {
        $productCategories = ProductCategory::query()
            ->orderBy('priority')
            ->get();
        
        $sizes = Size::all();
        
        return view('admin.products.edit', [
            'product' => $product,
            'productCategories' => $productCategories,
            'sizes' => $sizes,
        ]);
    }
    
    public function update(Request $request, Product $product)
    {
//        $formData = $request->validate([
//            'name' => ['required', 'string', 'max:10', Rule::unique('products')->ignore($product->id),]
//        ]);
        
        $formData = $request->validate([
            'brand_id' => ['required', 'numeric', 'exists:brands,id'],
            'product_category_id' => ['required', 'numeric', 'exists:product_categories,id'],
            'name' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0.01'],
            'old_price' => [
                'nullable', 
                'numeric', 
                'min:0.01',
                function ($attribute, $value, $fail) use ($request) { // use: upucavanje spoljnih varijabli u scope anonimne funkcije
                    if ($value <= $request->input('price')) {
                        $fail(__('Old price must be greater than price'));
                    }
                },
            ],
            'index_page' => ['required', 'numeric', 'in:0,1'],
            'size_id' => ['required', 'array', 'exists:sizes,id', 'min:2'],
            'photo1' => ['nullable', 'file', 'image'],
            'photo2' => ['nullable', 'file', 'image'],
        ]);
        
        $product->fill($formData);
        
        $product->save();//UPDATE QUERY nad bazom, zato sto je red vec nekako dobijen iz baze
        
        //odrzavanje veze vise na vise
        
        // $proucts sizes: 1,7,8
        //  $formData['size_id'] -> 4,5,6
        $product->sizes()->sync($formData['size_id']);
        
        
		$this->handlePhotoUpload('photo1', $request, $product);
		$this->handlePhotoUpload('photo2', $request, $product);
        
        
        session()->flash('system_message', __('Product has been saved'));
        
        return redirect()->route('admin.products.index');
    }
    
    public function delete(Request $request)
    {
        $formData = $request->validate([
            'id' => ['required', 'numeric', 'exists:products,id'],
        ]);
        
        $formData['id'];
        
        $product = Product::findOrFail($formData['id']);
        
        //brisanje reda iz baze preko Objekta
        $product->delete();
        
        //ODRZAVANJE RELACIJA
        //Imamo preneseni kljuc product_id u tabeli product_products
        \DB::table('product_sizes')
            ->where('product_id', '=', $product->id)
            ->delete();
        
        //$product->sizes()->delete();
        //$product->sizes()->sync([]);
        
        //brisanje redova pomocu QueryBuilder-a
        //Product::query()->where('id', $formData['id'])->delete();
        //Product::query()->where('created_at', '<', date('Y-m-d H:i:s', strtotime('-1 year')))->delete();
        
		//brisanje SVIH POVEZANIH FAJLOVA!!!
		$product->deletePhotos();
        
       /*  if($request->wantsJson()){
            return response()->json()
            'system_message'->__('Product has been deleted')->json([
                'system_message'->__('Product has been deleted');'system_message'->__('Product has been deleted');
                ]);                
        }
        
        session()->flash('system_message', __('Product has been deleted'));
        
        return redirect()->route('admin.products.index'); */

        return response->json([
            'system_message'=> __('Product has been deleted')
        ]);
    }
	
	public function deletePhoto(Request $request, Product $product)
	{
		$formData = $request->validate([
			'photo' => ['required', 'string', 'in:photo1,photo2'],
		]);
		
		$photoFieldName = $formData['photo']; //photo1 ili photo2
		
		$product->deletePhoto($photoFieldName);
		
        

		//reset kolone photo1 ili photo2 na null
		//da izbrisemo podatak u bazi o povezanoj fotografiji
		$product->$photoFieldName = null;
		$product->save();
		
		return response()->json([
			'system_message' => __('Photo has been deleted'),
			'photo_url' => $product->getPhotoUrl($photoFieldName),
		]);
	}
	
	protected function handlePhotoUpload(
		string $photoFieldName, Request $request, Product $product)
	{
		if ($request->hasFile($photoFieldName)) {		

			$product->deletePhoto($photoFieldName);			
			$photoFile = $request->file($photoFieldName);			
			$newPhotoFileName = $product->id . '_' . $photoFieldName . '_' . $photoFile->getClientOriginalName();		
			$photoFile->move(
				public_path('/storage/products/'),
				$newPhotoFileName
			);
			
			
			//$field = 'field_name';
			//echo $o1->$field; // $o1->field_name
			
			//$a = 5;
			//$b = 'a';
			
			//echo $$b; // echo $a
			//$photoFieldName = 'photo2'
			
			$product->$photoFieldName = $newPhotoFileName;
			
			$product->save();
			
			//originalna slika tj pravougaona verzija (kad udjemo u proizvod)
			\Image::make(public_path('/storage/products/' . $product->$photoFieldName))
				->fit(600, 800)
				->save(); //cuva tamo odakle je i ucitano
			
			//thumb slika tj kockasta verzija (u listi proizvoda)
			\Image::make(public_path('/storage/products/' . $product->$photoFieldName))
				->fit(300, 300)
				->save(public_path('/storage/products/thumbs/' . $product->$photoFieldName));
                    //druga lokacija (podfolder thumbs) za umanjene slike
		}
	}
}
