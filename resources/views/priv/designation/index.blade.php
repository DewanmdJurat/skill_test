@extends('home')

@section('privContent')
<header class="page-header">
    <h2>Designation</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>Designation</li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="" data-toggle="modal"  data-target="#designation-form"><i class="fas fa-plus"></i> Create</a>
                </div>

                <h2 class="card-title">Designation</h2>
            </header>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-designation" class="form-control" placeholder="search">
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
                    <tbody id="DesignationTable">
                       
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
<div class="modal fade" id="designation-form" tabindex="-1" role="dialog" aria-labelledby="designationModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post"  id="designation-store">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Add New Designation </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="designation_name">Designation Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="designation_name" name="designation_name" placeholder="Designation Name" required>
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

<div class="modal fade" id="modal-form-designation-edit" tabindex="-1" role="dialog" aria-labelledby="variantModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="designation-update">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Edit Designation </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="designation_name_edit">Designation Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="designation_name_edit" name="designation_name" placeholder="Designation Name" required>
                        <input type="hidden" class="form-control" id="designation_id" name="designation_id" required>
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
            designationLoad();

            $('#designation-form').on('submit','#designation-store', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('designation.store')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#designation-form').modal('hide');
                       toastr.success(data.message);
                       designationLoad();
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

            $('#search-designation').on('input', function(event) {
                event.preventDefault();
                search = $(this).val();
                designationLoad(page = 0,offset = 10, search);
            });

            $(document).on('click', '.pagination a' ,function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('/')[5];
                offset = $(this).attr('href').split('/')[6];
                search = $('#search-designation').val();
                designationLoad();
            });

            $(document).on('click', '.designation-edit-row', function() {
                let designation_id = $(this).attr('data-edit-designation-id');
                $.ajax({
                    url: "{{url('designation/edit')}}/"+designation_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        $('#designation_id').val(data.designation.id);
                        $('#designation_name_edit').val(data.designation.designation_name);
                        $('#modal-form-designation-edit').modal('show');
                    }
                });
            });

            $('#modal-form-designation-edit').on('submit','#designation-update', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('designation.update')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#modal-form-designation-edit').modal('hide');
                       toastr.success(data.message);
                       designationLoad();
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

            $(document).on('click', '.delete-designation', function(event) {
                event.preventDefault();
                let designation_id = $(this).attr('data-delete-designation-id');
                
                $.ajax({
                    url: "{{url('designation/delete')}}/"+designation_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        toastr.success(data.message);
                        designationLoad();
                    }
                });
            });
        });

        function designationLoad() {
                designation_list = '';
                pagination_list = '';
                pagination_url = "{{url('/designation/lists')}}";
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
                        let numRows =  data.designation_count;
                        
                        $.each(data.designations, function(i, designation) {
                            designation_list +=`<tr data-designation-id="${designation.id}">
                                <td>${designation.id}</td>
                                <td>${designation.designation_name}</td>
                                <td class="actions">
                                    <a href="" data-toggle="modal" class="on-default btn btn-outline-info btn-sm edit-row designation-edit-row" data-edit-designation-id="${designation.id}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="" class="on-default btn btn-outline-danger btn-sm delete-designation" data-delete-designation-id="${designation.id}"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>`;
                        });

                        $('#DesignationTable').empty();
                        $('#DesignationTable').append(designation_list);
                      
                        if(numRows > offset)
                        {
                            let pageNumber = Math.ceil(numRows / offset);
                            for(let i = 1; i <= pageNumber;) {
                                paginate_url = "{{url('/designation/lists')}}/"+i+"/"+offset;
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