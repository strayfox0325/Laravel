<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactFormMail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        
        //if ($request->isMethod('POST')) {
        //    
        //}
        
        $systemMessage = session()->pull('system_message');
        
        return view('front.contact.index', [
            'systemMessage' => $systemMessage,
        ]);
    }
    
    public function sendMessage(Request $request)
    {
        //input funkcija - dobijanje POST parametara
        //query funkcija - dobijanje GET parametara is URL-a -> ?page=3
        //validate - validira podatke sa forme 
        //  i vraca niz podatak sa forme AKO JE SVE U REDU
        //  AKO NIJE U REDU BACA SE ValidationException
        
        // U SLUCAJU ValidationException-a Laravel automatski vraca na prethodnnu stranicu
        //  tj na prikaz forme
        
        $formData = $request->validate([
            //validation rules
            
            
            //1. da li je nesto obavezno ili nije: required, nullable
            //2. validacija tipa polja - string, numeric, date, file, image, array
            //3. dodatna validacija
            'your_name' => 'required|string|min:2',
            'your_email' => ['required', 'email'],
            'message' => ['required', 'string', 'min:50', 'max:255'],
            
        ]);
        
        //SLANJE EMAIL-a
        
        \Mail::to('dimi7even@gmail.com')->send(new ContactFormMail(
            $formData['your_email'],
            $formData['your_name'],
            $formData['message']
        ));
        
        
        //session()->put( // upis u sesiju
        session()->flash( // kratotrajan upis u sesiju, gubi se u narednom requestu
            'system_message',
            __('We have recieved your message, we will contact you soon!') // dali smo mogucnost prevodjenja poruke
        );
        
        //$request->session(); // isto kao da smo samo uradili session()
        //dd($formData);
        
        //vracanje na prethodnu stranicu
        
        //return redirect()->route('front.contact.index'); // redirectovanje na rutu
        return redirect()->back(); //redirectovanje na prethodnu stranicu
    }
}
