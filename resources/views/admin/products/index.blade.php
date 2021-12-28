\@extends('admin._layout.layout')

@section('seo_title', __('Products'))

@section('content')
<!-- Content Header (Page header) -->
 <section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>@lang('Products')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.index.index')}}">
                            @lang('Home')
                        </a>
                    </li>
                    <li class="breadcrumb-item active">@lang('Products')</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">@lang('All Products')</h3>
                        <div class="card-tools">
                            <a href="{{route('admin.products.add')}}" class="btn btn-success">
                                <i class="fas fa-plus-square"></i>
                                @lang('Add new Product')
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table id="entities-list-table" class="table table-bordered">
                            <thead>                  
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th class="text-center">Photo</th>
                                    <th style="width: 20%;">Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th class="text-center">Sizes</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php /*
                                @foreach($products as $product)
                                <tr>
                                    <td>#{{$product->id}}</td>
                                    <td class="text-center">
                                        <img 
                                            src="{{$product->getPhoto1Url()}}" 
                                            style="max-width: 80px;"
                                        >
                                    </td>
                                    <td>
                                        <strong>{{$product->name}}</strong>
                                    </td>
                                    <td>
                                        {{optional($product->brand)->name}}
                                    </td>
                                    <td>
                                        {{optional($product->productCategory)->name}}
                                    </td>
                                    <td>
                                        {{optional($product->sizes)->pluck('name')->join(', ')}}
                                    </td>
                                    <td class="text-center">
                                        {{$product->created_at}}
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{$product->getFrontUrl()}}" class="btn btn-info" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{route('admin.products.edit', ['product' => $product->id])}}" class="btn btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button 
                                                type="button" 
                                                class="btn btn-info" 
                                                data-toggle="modal" 
                                                data-target="#delete-modal"
                                                data-action="delete"
                                                data-id="{{$product->id}}"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                                */ ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">

                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<form action="{{route('admin.products.delete')}}" method="post" class="modal fade" id="delete-modal">
    @csrf
    <input type="hidden" name="id" value="">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this product?</p>
                <strong data-container="name"></strong>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</form>
<!-- /.modal -->

@endsection

@push('footer_javascript')
<script type="text/javascript">
    //Datatables plugin formatiranje tabela
    let $('#entities-list-table').DataTable({
        "serverSide":true,
        "processing":true,
        "ajax": {
            "url":"{{route('admin.products.datatable')}}",
            "type":"post",
             "data":{
                "_token": "{{csrf_token()}}",
            } 
        },
        "pageLength":25, //pagination pages, mora biti iz liste
        "lengthMenu":[5,10,25,50,100,250,500,1000], //combo box za paginaciju
        "order":[[6,'desc']], //inicijalni soritra po koloni 6 desc
        "columns":[
        {"name":"id","data":"id"},
        {"name":"photo1","data":"photo1","orderable":false,"searchable":false,"className":"text-center"},
        {"name":"name","data":"name"},
        {"name":"brand_name","data":"brand_id"},
        {"name":"product_category_name","data":"product_category_id"},
        {"name":"sizes","data":"sizes","orderable":false},
        {"name":"created_at","data":"created_at","className":"text-center"},
        {"name":"actions","data":"actions","orderable":false,"searchable":false, "className":"text-center"},
        ]
    });

    $('#entities-list-table').on('click', '[data-action="delete"]', function (e) {
        //e.stopPropagation();
        //e.preventDefault();

        //let id = $(this).data('id');
        let id = $(this).attr('data-id');
        let name = $(this).attr('data-name');

        $('#delete-modal [name="id"]').val(id);
        $('#delete-modal [data-container="name"]').html(name);
    });
	
	$('#delete-modal').on('submit', function (e) {
		e.preventDefault();
		
		$(this).modal('hide');
		
		$.ajax({
			"url": $(this).attr('action'), //citanje action atributa sa forme
			"type": "post",
			"data": $(this).serialize() //citanje svih polja na formi  tj sve sto ima "name" atribut
		}).done(function (response) {
			
			toastr.success(response.system_message);

			// da refreshujemo datatables nakon izmene, preko ajaxa
			entitiesDataTable.ajax.reload(null, false); //false znaci da ostajemo na istoj strani paginacije
			
		}).fail(function () {
			toastr.error("@lang('Error occured while deleting product')");
		});
	});
</script>
@endpush