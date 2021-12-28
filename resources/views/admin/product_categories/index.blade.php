@extends('admin._layout.layout')

@section('seo_title', __('Product Categories'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>@lang('Product Categories')</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{route('admin.index.index')}}">@lang('Home')</a></li>
                    <li class="breadcrumb-item active">@lang('Product Categories')</li>
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
                        <h3 class="card-title">@lang('All Product Categories')</h3>
                        <div class="card-tools">
                            <form style="display:none;" id="change-priority-form" class="btn-group" action="{{route('admin.product_categories.change_priorities')}}" method="post">
                                @csrf
                                <input type="hidden" name="priorities" value="">
                                
                                <button type="submit" class="btn btn-outline-success">
                                    <i class="fas fa-check"></i>
                                    @lang('Save Order')
                                </button>
                                <button type="button" data-action="hide-order" class="btn btn-outline-danger">
                                    <i class="fas fa-remove"></i>
                                    @lang('Cancel')
                                </button>
                            </form>
                            <button data-action="show-order" class="btn btn-outline-secondary">
                                <i class="fas fa-sort"></i>
                                @lang('Change Order')
                            </button>
                            <a href="{{route('admin.product_categories.add')}}" class="btn btn-success">
                                <i class="fas fa-plus-square"></i>
                                @lang('Add new Category')
                            </a>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="entities-list-table" class="table table-bordered">
                            <thead>                  
                                <tr>
                                    <th style="width: 10%">#</th>
                                    <th style="width: 30%;">Name</th>
                                    <th style="width: 30%;">Description</th>
                                    <th class="text-center">Created At</th>
                                    <th class="text-center">Last Change</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sortable-list">
                                @foreach($productCategories as $productCategory)
                                <tr data-id="{{$productCategory->id}}">
                                    <td>
                                        <span style="display:none;" class="btn btn-outline-secondary handle">
                                            <i class="fas fa-sort"></i>
                                        </span>
                                        #{{$productCategory->id}}
                                    </td>
                                    <td>
                                        <strong>{{$productCategory->name}}</strong>
                                    </td>
                                    <td>
                                        {{\Str::limit($productCategory->description, 50)}}
                                    </td>
                                    <td class="text-center">{{$productCategory->created_at}}</td>
                                    <td class="text-center">{{$productCategory->updated_at}}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{route('admin.product_categories.edit', ['productCategory' => $productCategory->id])}}" class="btn btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button 
                                                type="button" 
                                                class="btn btn-info" 
                                                data-toggle="modal" 
                                                data-target="#delete-modal"
                                                
                                                data-action="delete"
                                                data-id="{{$productCategory->id}}"
                                                data-name="{{$productCategory->name}}"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
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

<form class="modal fade" id="delete-modal" action="{{route('admin.product_categories.delete')}}" method="post">
    @csrf
    <input type="hidden" name="id" value="">
    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete category?</p>
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

@push('head_links')
<link href="/themes/admin/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css"/>
<link href="/themes/admin/plugins/jquery-ui/jquery-ui.theme.min.css" rel="stylesheet" type="text/css"/>
@endpush

@push('footer_javascript')

<script src="{{url('/themes/admin/plugins/jquery-ui/jquery-ui.min.js')}}" type="text/javascript"></script>
<script type="text/javascript">

$('#entities-list-table').on('click', '[data-action="delete"]', function (e) {
    //e.stopPropagation();
    //e.preventDefault();
    
    //let id = $(this).data('id');
    let id = $(this).attr('data-id');
    let name = $(this).attr('data-name');
    
    $('#delete-modal [name="id"]').val(id);
    $('#delete-modal [data-container="name"]').html(name);
});

$('#sortable-list').sortable({
    "handle": ".handle",
    "update": function(event, ui) {
        
        let priorities = $('#sortable-list').sortable('toArray', {
            "attribute": "data-id"
        });
        
        console.log(priorities);
        
        $('#change-priority-form [name="priorities"]').val(priorities.join(','));
    }
});

$('[data-action="show-order"]').on('click', function (e) {
    
    $('[data-action="show-order"]').hide();
    
    $('#change-priority-form').show();
    
    $('#sortable-list .handle').show();
});

$('[data-action="hide-order"]').on('click', function (e) {
    
    $('[data-action="show-order"]').show();
    
    $('#change-priority-form').hide();
    
    $('#sortable-list .handle').hide();
    
    $('#sortable-list').sortable('cancel');
});

</script>
@endpush