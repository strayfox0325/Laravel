<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Shop\ShoppingCart;
use App\Models\Product;

class ShoppingCartController extends Controller
{
    public function index(Request $request)
    {
        $shoppingCart = ShoppingCart::getShoppingCartFromSession();
        
        return view('front.shopping_cart.index', [
            'shoppingCart' => $shoppingCart,
        ]);
    }
    
    public function content(Request $request)
    {
        //sleep(5);
        
        //throw new \Exception('Nesto uzasno se desilo!');
        
        $shoppingCart = ShoppingCart::getShoppingCartFromSession();
        
        return view('front._layout.partials.cart_content', [
            'shoppingCart' => $shoppingCart,
        ]);
    }
    
    public function addProduct(Request $request)
    {
        
        $formData = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'numeric', 'min:1', 'max:10'],
        ]);
        
        $shoppingCart = ShoppingCart::getShoppingCartFromSession();
        
        $product = Product::findOrFail($formData['product_id']);
        
        $shoppingCart->addProduct($product, $formData['quantity']);
        
        //dump($product->toArray(), $shoppingCart);
        
        return response()->json([
            'system_message' => 'Product has been added to cart',
        ]);
    }
    
    public function removeProduct(Request $request)
    {
        $formData = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
        ]);
        
        $shoppingCart = ShoppingCart::getShoppingCartFromSession();
        
        $shoppingCart->removeProduct($formData['product_id']);
        
        return response()->json([
            'system_message' => 'Product has been removed from cart',
        ]);
    }
}
