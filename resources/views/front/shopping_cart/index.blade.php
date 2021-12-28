@extends('front._layout.layout')

@section('seo_title', 'Shopping Cart')

@section('content')
<!-- Breadcumb Area -->
<div class="breadcumb_area">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12">
                <h5>Cart</h5>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('front.index.index')}}">Home</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Breadcumb Area -->

<!-- Cart Area -->
<div class="cart_area section_padding_100_70 clearfix">
    <div class="container">
        <div class="row justify-content-between">
            <div class="col-12">
                <div class="cart-table">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-30">
                            <thead>
                                <tr>
                                    <th scope="col"><i class="icofont-ui-delete"></i></th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Product</th>
                                    <th scope="col">Unit Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($shoppingCart->getItems() as $item)
                                <tr>
                                    <th scope="row">
                                        <a href="#"><i class="icofont-close"></i></a>
                                    </th>
                                    <td>
                                        <img src="{{$item->getProduct()->getPhoto1Url()}}" alt="Product">
                                    </td>
                                    <td>
                                        <a href="{{$item->getProduct()->getFrontUrl()}}">
                                            {{$item->getProduct()->name}}
                                        </a>
                                    </td>
                                    <td>${{$item->getProduct()->price}}</td>
                                    <td>
                                        <div class="quantity">
                                            <strong>{{$item->getQuantity()}}</strong>
                                        </div>
                                    </td>
                                    <td>${{$item->getSubtotal()}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">

            </div>

            <div class="col-12 col-lg-5">
                <div class="cart-total-area mb-30">
                    <h5 class="mb-3">Cart Totals</h5>
                    <div class="table-responsive">
                        <table class="table mb-3">
                            <tbody>
                                <tr>
                                    <td>Total</td>
                                    <td>${{$shoppingCart->getTotal()}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="#" class="btn btn-primary d-block">Proceed To Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cart Area End -->

@endsection