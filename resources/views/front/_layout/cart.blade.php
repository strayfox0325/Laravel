<!-- Cart -->
<div class="cart-area" id="shopping_cart_top">
    
</div>
@push('footer_javascript')
<script type="text/javascript">
//naknadno popunjavanje div-a #shopping_cart_top 

function shoppingCartFrontRefreshTop() {
    
    // ajax funkcija vraca PROMISE 
    $.ajax({
        "url": "{{route('front.shopping_cart.content')}}",
        "type": "get", //http method GET ili POST
        "data": {}
    }).done(function (response) {

        $('#shopping_cart_top').html(response);
        console.log('Zavrseno ucitavanje sadrzaja korpe');
        //console.log(response);
    }).fail(function (jqXHR, textStatus, error) {

        console.log('Greska prilikom ucitavanja sadrzaja korpe');
    //    console.log(textStatus);
    //    console.log(error);
    });
}


function shoppingCartFrontAddToCart(productId, quantity) {
    $.ajax({
        
        "url": "{{route('front.shopping_cart.add_product')}}",
        "type": "POST",
        "data": {
            //"_token": "{{csrf_token()}}", //obavezno u Laravelu
            "product_id": productId,
            "quantity": quantity
        }
        
    }).done(function (response) {
        
        console.log(response);
        
        //alert(response.system_message);
        
        shoppingCartFrontRefreshTop();
        
        
    }).fail(function() {
        console.log('Neuspesno dodavanje u korpu');
    });
}

function shoppingCartFrontRemoveProduct(productId)
{
    $.ajax({
        "url": "{{route('front.shopping_cart.remove_product')}}",
        "type": "POST",
        "data": {
            "_token": "{{csrf_token()}}",
            "product_id": productId
        }
        
    }).done(function (response) {
        
        alert(response.system_message);
        
        shoppingCartFrontRefreshTop();
        
    }).fail(function () {
        console.log('Neuspesno brisanje iz korpe');
    });
}




//selektujem sve elemente koji imaju data-action="add_to_cart"
$('[data-action="add_to_cart"]').on('click', function (e) {
    
    e.preventDefault();
    e.stopPropagation();
    
    let productId = $(this).attr('data-product-id');
    //let productId = $(this).data('product_id');
    
    let quantity = $(this).attr('data-quantity');
    
    
    shoppingCartFrontAddToCart(productId, quantity);
    
});

// za sadrzaj koji se ucitava preko ajax-a 
// obavezno event bubbling !!! 
// handler je na nekom nad container-u
$('#shopping_cart_top').on('click', '[data-action="remove_product"]', function (e) {
    
    e.preventDefault();
    e.stopPropagation();
    
    let productId = $(this).attr('data-product-id');
    
    shoppingCartFrontRemoveProduct(productId);
});


shoppingCartFrontRefreshTop(); //kada se ucita stranica prvi put ucitaj i korpu

</script>
@endpush