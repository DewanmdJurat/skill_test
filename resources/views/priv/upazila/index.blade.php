@extends('home')

@section('privContent')
<header class="page-header">
    <h2>Upazila</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>Upazila</li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="" data-toggle="modal"  data-target="#upazila-form"><i class="fas fa-plus"></i> Create</a>
                </div>

                <h2 class="card-title">Upazila</h2>
            </header>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-upazila" class="form-control" placeholder="search">
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
                    <tbody id="UpazilaTable">
                       
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
<div class="modal fade" id="upazila-form" tabindex="-1" role="dialog" aria-labelledby="upazilaModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post"  id="upazila-store">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Add New Upazila </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="upazila_name">Upazila Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="upazila_name" name="upazila_name" placeholder="Upazila Name" required>
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

<div class="modal fade" id="modal-form-upazila-edit" tabindex="-1" role="dialog" aria-labelledby="variantModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="upazila-update">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Edit Upazila </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="upazila_name_edit">Upazila Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="upazila_name_edit" name="upazila_name" placeholder="Upazila Name" required>
                        <input type="hidden" class="form-control" id="upazila_id" name="upazila_id" required>
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
            upazilaLoad();

            $('#upazila-form').on('submit','#upazila-store', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('upazila.store')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#upazila-form').modal('hide');
                       toastr.success(data.message);
                       upazilaLoad();
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

            $('#search-upazila').on('input', function(event) {
                event.preventDefault();
                search = $(this).val();
                upazilaLoad(page = 0,offset = 10, search);
            });

            $(document).on('click', '.pagination a' ,function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('/')[5];
                offset = $(this).attr('href').split('/')[6];
                search = $('#search-upazila').val();
                upazilaLoad();
            });

            $(document).on('click', '.upazila-edit-row', function() {
                let upazila_id = $(this).attr('data-edit-upazila-id');
                $.ajax({
                    url: "{{url('upazila/edit')}}/"+upazila_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        $('#upazila_id').val(data.upazila.id);
                        $('#upazila_name_edit').val(data.upazila.upazila_name);
                        $('#modal-form-upazila-edit').modal('show');
                    }
                });
            });

            $('#modal-form-upazila-edit').on('submit','#upazila-update', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('upazila.update')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#modal-form-upazila-edit').modal('hide');
                       toastr.success(data.message);
                       upazilaLoad();
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

            $(document).on('click', '.delete-upazila', function(event) {
                event.preventDefault();
                let upazila_id = $(this).attr('data-delete-upazila-id');
                console.log(upazila_id);
                $.ajax({
                    url: "{{url('upazila/delete')}}/"+upazila_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        toastr.success(data.message);
                        upazilaLoad();
                    }
                });
            });
        });

        function upazilaLoad() {
                upazila_list = '';
                pagination_list = '';
                pagination_url = "{{url('/upazila/lists')}}";
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
                        let numRows =  data.upazila_count;
                        
                        $.each(data.upazilas, function(i, upazila) {
                            upazila_list +=`<tr data-upazila-id="${upazila.id}">
                                <td>${upazila.id}</td>
                                <td>${upazila.upazila_name}</td>
                                <td class="actions">
                                    <a href="" data-toggle="modal" class="on-default btn btn-outline-info btn-sm edit-row upazila-edit-row" data-edit-upazila-id="${upazila.id}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="" class="on-default btn btn-outline-danger btn-sm delete-upazila" data-delete-upazila-id="${upazila.id}"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>`;
                        });

                        $('#UpazilaTable').empty();
                        $('#UpazilaTable').append(upazila_list);
                      
                        if(numRows > offset)
                        {
                            let pageNumber = Math.ceil(numRows / offset);
                            for(let i = 1; i <= pageNumber;) {
                                paginate_url = "{{url('/upazila/lists')}}/"+i+"/"+offset;
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