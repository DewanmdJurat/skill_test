@extends('home')

@section('privContent')
<header class="page-header">
    <h2>Union</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>Union</li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="" data-toggle="modal"  data-target="#union-form"><i class="fas fa-plus"></i> Create</a>
                </div>

                <h2 class="card-title">Union</h2>
            </header>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-union" class="form-control" placeholder="search">
                    </div>
                </div>
                <table class="table table-bordered table-striped mb-0" id="data-table">
                    <thead>
                        <tr>
                            <th width="2%">ID</th>
                            <th>Name</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody id="UnionTable">
                       
                    </tbody>
                </table>
                <nav aria-label="Page navigation" class="mt-3">
                    <ul class="pagination">
                    </ul>
                </nav>
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="union-form" tabindex="-1" role="dialog" aria-labelledby="unionModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post"  id="union-store">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Add New Union </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="union_name">Union Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="union_name" name="union_name" placeholder="Union Name" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modal-form-union-edit" tabindex="-1" role="dialog" aria-labelledby="variantModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="union-update">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Edit Union </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="union_name_edit">Union Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="union_name_edit" name="union_name" placeholder="Union Name" required>
                        <input type="hidden" class="form-control" id="union_id" name="union_id" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </form>
  </div>
</div>

@endsection

@section('scripts')
    <script type="text/javascript">
        let page    = 0;
        let offset  = 10;
        let search  = '';
        $(document).ready(function(){
            unionLoad();

            $('#union-form').on('submit','#union-store', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('union.store')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#union-form').modal('hide');
                       toastr.success(data.message);
                       unionLoad();
                    },
                    error: function(error){
                        if (error.status == 422) {
                            $.each(error.responseJSON.errors, function (i, message) {
                                toastr.error(message);
                            });
                        }
                    },
                });
            });

            $('#search-union').on('input', function(event) {
                event.preventDefault();
                search = $(this).val();
                unionLoad(page = 0,offset = 10, search);
            });

            $(document).on('click', '.pagination a' ,function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('/')[5];
                offset = $(this).attr('href').split('/')[6];
                search = $('#search-union').val();
                unionLoad();
            });

            $(document).on('click', '.union-edit-row', function() {
                let union_id = $(this).attr('data-edit-union-id');
                $.ajax({
                    url: "{{url('union/edit')}}/"+union_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        $('#union_id').val(data.union.id);
                        $('#union_name_edit').val(data.union.union_name);
                        $('#modal-form-union-edit').modal('show');
                    }
                });
            });

            $('#modal-form-union-edit').on('submit','#union-update', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('union.update')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#modal-form-union-edit').modal('hide');
                       toastr.success(data.message);
                       unionLoad();
                    },
                    error: function(error){
                        if (error.status == 422) {
                            $.each(error.responseJSON.errors, function (i, message) {
                                toastr.error(message);
                            });
                        }
                    },
                });
            });

            $(document).on('click', '.delete-union', function(event) {
                event.preventDefault();
                let union_id = $(this).attr('data-delete-union-id');
                
                $.ajax({
                    url: "{{url('union/delete')}}/"+union_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        toastr.success(data.message);
                        unionLoad();
                    }
                });
            });
        });

        function unionLoad() {
                union_list = '';
                pagination_list = '';
                pagination_url = "{{url('/union/lists')}}";
                $.ajax({
                    url: pagination_url,
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    type: 'post',
                    dataType: 'json',
                    data: {
                        page: page,
                        offset: offset,
                        search: search
                    },
                    success: function(data){
                        let numRows =  data.union_count;
                        
                        $.each(data.unions, function(i, union) {
                            union_list +=`<tr data-union-id="${union.id}">
                                <td>${union.id}</td>
                                <td>${union.union_name}</td>
                                <td class="actions">
                                    <a href="" data-toggle="modal" class="on-default btn btn-outline-info btn-sm edit-row union-edit-row" data-edit-union-id="${union.id}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="" class="on-default btn btn-outline-danger btn-sm delete-union" data-delete-union-id="${union.id}"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>`;
                        });

                        $('#UnionTable').empty();
                        $('#UnionTable').append(union_list);
                      
                        if(numRows > offset)
                        {
                            let pageNumber = Math.ceil(numRows / offset);
                            for(let i = 1; i <= pageNumber;) {
                                paginate_url = "{{url('/union/lists')}}/"+i+"/"+offset;
                                pagination_list +=`<li class="page-item"><a class="page-link" href="${paginate_url}">${i++}</a></li>`;
                            }
                            $('.pagination').empty();
                            $('.pagination').append(pagination_list);
                        }
                        else
                        {
                            $('.pagination').empty();
                        }
                    }
                });
        }
    </script>
@endsection