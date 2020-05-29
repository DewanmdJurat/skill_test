@extends('home')

@section('privContent')
<header class="page-header">
    <h2>Divisions</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>Divisions</li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="" data-toggle="modal"  data-target="#division-form"><i class="fas fa-plus"></i> Create</a>
                </div>

                <h2 class="card-title">Divisions</h2>
            </header>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-division" class="form-control" placeholder="search">
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
                    <tbody id="DivisionTable">
                       
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
<div class="modal fade" id="division-form" tabindex="-1" role="dialog" aria-labelledby="divisionModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post"  id="division-store">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Add New Division </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="division_name">Division Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="division_name" name="division_name" placeholder="Division Name" required>
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

<div class="modal fade" id="modal-form-division-edit" tabindex="-1" role="dialog" aria-labelledby="variantModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="division-update">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Edit Division </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="division_name_edit">Division Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="division_name_edit" name="division_name" placeholder="Division Name" required>
                        <input type="hidden" class="form-control" id="division_id" name="division_id" required>
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
            divisionLoad();

            $('#division-form').on('submit','#division-store', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('division.store')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#divisionForm').modal('hide');
                       toastr.success(data.message);
                       divisionLoad();
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

            $('#search-division').on('input', function(event) {
                event.preventDefault();
                search = $(this).val();
                divisionLoad(page = 0,offset = 10, search);
            });

            $(document).on('click', '.pagination a' ,function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('/')[5];
                offset = $(this).attr('href').split('/')[6];
                search = $('#search-division').val();
                divisionLoad();
            });

            $(document).on('click', '.division-edit-row', function() {
                let division_id = $(this).attr('data-edit-division-id');
                $.ajax({
                    url: "{{url('division/edit')}}/"+division_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        $('#division_id').val(data.division.id);
                        $('#division_name_edit').val(data.division.division_name);
                        $('#modal-form-division-edit').modal('show');
                    }
                });
            });

            $('#modal-form-division-edit').on('submit','#division-update', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('division.update')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#modal-form-division-edit').modal('hide');
                       toastr.success(data.message);
                       divisionLoad();
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

            $(document).on('click', '.delete-division', function(event) {
                event.preventDefault();
                let division_id = $(this).attr('data-delete-division-id');
                $.ajax({
                    url: "{{url('division/delete')}}/"+division_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        toastr.success(data.message);
                        divisionLoad();
                    }
                });
            });
        });

        function divisionLoad() {
                division_list = '';
                pagination_list = '';
                pagination_url = "{{url('/division/lists')}}";
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
                        
                        let numRows =  data.division_count;
                        
                        $.each(data.divisions, function(i, division) {
                            division_list +=`<tr data-division-id="${division.id}">
                                <td>${division.id}</td>
                                <td>${division.division_name}</td>
                                <td class="actions">
                                    <a href="" data-toggle="modal" class="on-default btn btn-outline-info btn-sm edit-row division-edit-row" data-edit-division-id="${division.id}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="" class="on-default btn btn-outline-danger btn-sm delete-division" data-delete-division-id="${division.id}"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>`;
                        });

                        $('#DivisionTable').empty();
                        $('#DivisionTable').append(division_list);
                      
                        if(numRows > offset)
                        {
                            let pageNumber = Math.ceil(numRows / offset);
                            for(let i = 1; i <= pageNumber;) {
                                paginate_url = "{{url('/division/lists')}}/"+i+"/"+offset;
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