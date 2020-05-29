@extends('home')

@section('privContent')
<header class="page-header">
    <h2>District</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>District</li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="" data-toggle="modal"  data-target="#district-form"><i class="fas fa-plus"></i> Create</a>
                </div>

                <h2 class="card-title">District</h2>
            </header>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-district" class="form-control" placeholder="search">
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
                    <tbody id="DistrictTable">
                       
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
<div class="modal fade" id="district-form" tabindex="-1" role="dialog" aria-labelledby="districtModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post"  id="district-store">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Add New District </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="district_name">District Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="district_name" name="district_name" placeholder="District Name" required>
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

<div class="modal fade" id="modal-form-district-edit" tabindex="-1" role="dialog" aria-labelledby="variantModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="district-update">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Edit District </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="district_name_edit">District Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="district_name_edit" name="district_name" placeholder="District Name" required>
                        <input type="hidden" class="form-control" id="district_id" name="district_id" required>
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
            districtLoad();

            $('#district-form').on('submit','#district-store', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('district.store')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#districtForm').modal('hide');
                       toastr.success(data.message);
                       districtLoad();
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

            $('#search-district').on('input', function(event) {
                event.preventDefault();
                search = $(this).val();
                districtLoad(page = 0,offset = 10, search);
            });

            $(document).on('click', '.pagination a' ,function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('/')[5];
                offset = $(this).attr('href').split('/')[6];
                search = $('#search-district').val();
                districtLoad();
            });

            $(document).on('click', '.district-edit-row', function() {
                let district_id = $(this).attr('data-edit-district-id');
                $.ajax({
                    url: "{{url('district/edit')}}/"+district_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        $('#district_id').val(data.district.id);
                        $('#district_name_edit').val(data.district.district_name);
                        $('#modal-form-district-edit').modal('show');
                    }
                });
            });

            $('#modal-form-district-edit').on('submit','#district-update', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('district.update')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#modal-form-district-edit').modal('hide');
                       toastr.success(data.message);
                       districtLoad();
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

            $(document).on('click', '.delete-district', function(event) {
                event.preventDefault();
                let district_id = $(this).attr('data-delete-district-id');
                console.log(district_id);
                $.ajax({
                    url: "{{url('district/delete')}}/"+district_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        toastr.success(data.message);
                        districtLoad();
                    }
                });
            });
        });

        function districtLoad() {
                district_list = '';
                pagination_list = '';
                pagination_url = "{{url('/district/lists')}}";
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
                        let numRows =  data.district_count;
                        
                        $.each(data.districts, function(i, district) {
                            district_list +=`<tr data-district-id="${district.id}">
                                <td>${district.id}</td>
                                <td>${district.district_name}</td>
                                <td class="actions">
                                    <a href="" data-toggle="modal" class="on-default btn btn-outline-info btn-sm edit-row district-edit-row" data-edit-district-id="${district.id}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="" class="on-default btn btn-outline-danger btn-sm delete-district" data-delete-district-id="${district.id}"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>`;
                        });

                        $('#DistrictTable').empty();
                        $('#DistrictTable').append(district_list);
                      
                        if(numRows > offset)
                        {
                            let pageNumber = Math.ceil(numRows / offset);
                            for(let i = 1; i <= pageNumber;) {
                                paginate_url = "{{url('/district/lists')}}/"+i+"/"+offset;
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