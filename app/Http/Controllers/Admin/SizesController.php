<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Validation\Rule;

use App\Models\Size;

class SizesController extends Controller
{
    public function index()
    {
        //$systemMessage = session()->pull('system_message');
        
        $sizes = Size::all();
        
        return view('admin.sizes.index', [
            'sizes' => $sizes,
            //'systemMessage' => $systemMessage,
        ]);
    }
    
    public function add(Request $request)
    {
        return view('admin.sizes.add', [
            
        ]);
    }
    
    public function insert(Request $request)
    {
        
        $formData = $request->validate([
            'name' => ['required', 'string', 'max:10', 'unique:sizes,name'],
        ]);
        
        // novi model u memoriji, jos nije sacuvan u bazi
        $newSize = new Size();
        
        //setovanje kolona u redu tabele
        //
        //jedna kolona po jedna kolona
        //$newSize->name = $formData['name'];
        
        //MASS ASIGNMENT - vise kolona od jednom
        $newSize->fill($formData);
        
        //objekat pre snimanja u bazu
        //dump($newSize);
        
        //save funkcija vrsi cuvanje promena nad redom u tabeli 
        $newSize->save(); // radi se INSERT QUERY nad bazom zato sto smo rucno kreirali objekat sa "new"
        
        //objekat nakon snimanja u bazu
        //dd($newSize);
        
        session()->flash('system_message', __('New size has been saved!'));
        
        return redirect()->route('admin.sizes.index');
    }
    
    public function edit(Request $request, Size $size)
    {
        return view('admin.sizes.edit', [
            'size' => $size
        ]);
    }
    
    public function update(Request $request, Size $size)
    {
        $formData = $request->validate([
            'name' => ['required', 'string', 'max:10', Rule::unique('sizes')->ignore($size->id),]
        ]);
        
        $size->fill($formData);
        
        $size->save();//UPDATE QUERY nad bazom, zato sto je red vec nekako dobijen iz baze
        
        session()->flash('system_message', __('Size has been saved'));
        
        return redirect()->route('admin.sizes.index');
    }
    
    public function delete(Request $request)
    {
        $formData = $request->validate([
            'id' => ['required', 'numeric', 'exists:sizes,id'],
        ]);
        
        $formData['id'];
        
        $size = Size::findOrFail($formData['id']);
        
        //brisanje reda iz baze preko Objekta
        $size->delete();
        
        //ODRZAVANJE RELACIJA
        //Imamo preneseni kljuc size_id u tabeli product_sizes
        \DB::table('product_sizes')
            ->where('size_id', '=', $size->id)
            ->delete();
        
        //brisanje redova pomocu QueryBuilder-a
        //Size::query()->where('id', $formData['id'])->delete();
        //Size::query()->where('created_at', '<', date('Y-m-d H:i:s', strtotime('-1 year')))->delete();
        
        
        session()->flash('system_message', __('Size has been deleted'));
        
        return redirect()->route('admin.sizes.index');
    }
}
