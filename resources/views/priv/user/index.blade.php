@extends('home')

@section('privContent')
<header class="page-header">
    <h2>User</h2>

    <div class="right-wrapper text-right mr-2">
        <ol class="breadcrumbs">
            <li>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i>
                </a>
            </li>
            <li>User</li>
        </ol>
    </div>
</header>
<div class="row">
    <div class="col">
        <section class="card">
            <header class="card-header">
                <div class="card-actions">
                    <a href="" data-toggle="modal"  data-target="#user-form"><i class="fas fa-plus"></i> Create</a>
                </div>

                <h2 class="card-title">User</h2>
            </header>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-md-8">
                        
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="search" id="search-user" class="form-control" placeholder="search">
                    </div>
                </div>
                <table class="table table-bordered table-striped mb-0" id="data-table">
                    <thead>
                        <tr>
                            <th width="2%">ID</th>
                            <th>Name</th>
                            <th>Designation</th>
                            <th>Division</th>
                            <th>District</th>
                            <th>Upazila</th>
                            <th>Union</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody id="UserTable">
                       
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
<div class="modal fade" id="user-form" tabindex="-1" role="dialog" aria-labelledby="userModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post"  id="user-store">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Add New User </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="user_name">User Name<span class="required">*</span></label>
                        <input type="text" class="form-control" id="user_name" name="name" placeholder="User Name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="user_email">User Email<span class="required">*</span></label>
                        <input type="text" class="form-control" id="user_email" name="email" placeholder="User Name" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="user_password">Password<span class="required">*</span></label>
                        <input type="password" class="form-control" id="user_password" name="password" placeholder="Password min 8 characters" min="8" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="designation">Designation<span class="required">*</span></label>
                        
                        <select class="form-control" id="designation" name="designation" required>
                            <option value="">Select Designation</option>
                            @foreach($designations as $value)
                                <option value="{{$value->id}}">{{ $value->designation_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="division">Division</label>
                        
                        <select class="form-control" id="division" name="division">
                            <option value="">Select Division</option>
                            @foreach($divisions as $value)
                                <option value="{{$value->id}}">{{ $value->division_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="district">District</label>
                        
                        <select class="form-control" id="district" name="district">
                            <option value="">Select District</option>
                            @foreach($districts as $value)
                                <option value="{{$value->id}}">{{ $value->district_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="upazila">Upazila</label>
                        
                        <select class="form-control" id="upazila" name="upazila">
                            <option value="">Select Upazila</option>
                            @foreach($upazilas as $value)
                                <option value="{{$value->id}}">{{ $value->upazila_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="union">Union</label>
                        
                        <select class="form-control" id="union" name="union">
                            <option value="">Select Union</option>
                            @foreach($unions as $value)
                                <option value="{{$value->id}}">{{ $value->union_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="user_lavel">User Level <span class="required">*</span></label>
                        
                        <select class="form-control" id="user_lavel" name="user_lavel" required>
                            <option value="division">Division</option>
                            <option value="district">District</option>
                            <option value="upazila">Upazila</option>
                        </select>
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

<div class="modal fade" id="modal-form-user-edit" tabindex="-1" role="dialog" aria-labelledby="variantModal" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form method="post" id="user-update">
       <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantModal">Edit User </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="user_name_edit">User Name<span class="required">*</span></label>
                            <input type="text" class="form-control" id="user_name_edit" name="name" placeholder="User Name" required>
                            <input type="hidden" class="form-control" id="user_id" name="user_id" >
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="user_email_edit">User Email<span class="required">*</span></label>
                            <input type="text" class="form-control" id="user_email_edit" name="email" placeholder="User Name" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="user_password_change">Password Change</label>
                            <input type="password" class="form-control" id="user_password_change" name="password" placeholder="Password min 8 characters" min="8">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="designation_edit">Designation<span class="required">*</span></label>
                            
                            <select class="form-control" id="designation_edit" name="designation" required>
                                <option value="">Select Designation</option>
                                @foreach($designations as $value)
                                    <option value="{{$value->id}}">{{ $value->designation_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="division_edit">Division</label>
                            <select class="form-control" id="division_edit" name="division">
                                <option value="">Select Division</option>
                                @foreach($divisions as $value)
                                    <option value="{{$value->id}}">{{ $value->division_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="district_edit">District</label>
                            <select class="form-control" id="district_edit" name="district">
                                <option value="">Select District</option>
                                @foreach($districts as $value)
                                    <option value="{{$value->id}}">{{ $value->district_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="upazila_edit">Upazila</label>
                            <select class="form-control" id="upazila_edit" name="upazila">
                                <option value="">Select Upazila</option>
                                @foreach($upazilas as $value)
                                    <option value="{{$value->id}}">{{ $value->upazila_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="union_edit">Union</label>
                            <select class="form-control" id="union_edit" name="union">
                                <option value="">Select Union</option>
                                @foreach($unions as $value)
                                    <option value="{{$value->id}}">{{ $value->union_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="user_lavel_edit">User Level <span class="required">*</span></label>
                            
                            <select class="form-control" id="user_lavel_edit" name="user_lavel" required>
                                <option value="division">Division</option>
                                <option value="district">District</option>
                                <option value="upazila">Upazila</option>
                            </select>
                        </div>
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
            userLoad();

            $('#user-form').on('submit','#user-store', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('user.store')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#user-form').modal('hide');
                       toastr.success(data.message);
                       userLoad();
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

            $('#search-user').on('input', function(event) {
                event.preventDefault();
                search = $(this).val();
                userLoad(page = 0,offset = 10, search);
            });

            $(document).on('click', '.pagination a' ,function(event) {
                event.preventDefault();
                page = $(this).attr('href').split('/')[5];
                offset = $(this).attr('href').split('/')[6];
                search = $('#search-user').val();
                userLoad();
            });

            $(document).on('click', '.user-edit-row', function() {
                let user_id = $(this).attr('data-edit-user-id');
                $.ajax({
                    url: "{{url('user/edit')}}/"+user_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {

                        let designation_id  = data.user.designation_id != null ? data.user.designation_id : '';
                        let division_id     = data.user.division_id != null ? data.user.division_id : '';
                        let district_id     = data.user.district_id != null ? data.user.district_id : '';
                        let upazila_id      = data.user.upazila_id != null ? data.user.upazila_id : '';
                        let union_id        = data.user.union_id != null ? data.user.union_id : '';

                        $('#user_id').val(data.user.id);
                        $('#user_name_edit').val(data.user.name);
                        $('#user_email_edit').val(data.user.email);
                        $('#designation_edit').val(designation_id);
                        $('#division_edit').val(division_id);
                        $('#district_edit').val(district_id);
                        $('#upazila_edit').val(upazila_id);
                        $('#union_edit').val(union_id);
                        $('#user_lavel_edit').val(data.user.user_lavel);
                        $('#modal-form-user-edit').modal('show');
                    }
                });
            });

            $('#modal-form-user-edit').on('submit','#user-update', function(event){
                event.preventDefault();

                $.ajax({
                    url: '{{route('user.update')}}',
                    type: 'POST',
                    headers:{ 
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') 
                    },
                    dataType: 'json',
                    data: $(this).serialize(),
                    success: function(data){
                       $('#modal-form-user-edit').modal('hide');
                       toastr.success(data.message);
                       userLoad();
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

            $(document).on('click', '.delete-user', function(event) {
                event.preventDefault();
                let user_id = $(this).attr('data-delete-user-id');
                
                $.ajax({
                    url: "{{url('user/delete')}}/"+user_id,
                    type: 'get',
                    dataType: 'json',
                    data: {},
                    success: function(data) {
                        toastr.success(data.message);
                        userLoad();
                    }
                });
            });
        });

        function userLoad() {
                user_list = '';
                pagination_list = '';
                pagination_url = "{{url('/user/lists')}}";
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
                        console.log(data);
                        let numRows =  data.user_count;
                        
                        $.each(data.users, function(i, user) {
                            let designation_name  = user.designation != null ? user.designation.designation_name : '';
                            let division_name     = user.division != null ? user.division.division_name : '';
                            let district_name     = user.district != null ? user.district.district_name : '';
                            let upazila_name      = user.upazila != null ? user.upazila.upazila_name : '';
                            let union_name        = user.union != null ? user.union.union_name : '';

                            user_list +=`<tr data-user-id="${user.id}">
                                <td>${user.id}</td>
                                <td>${user.name}</td>
                                <td>${designation_name}</td>
                                <td>${division_name}</td>
                                <td>${district_name}</td>
                                <td>${upazila_name}</td>
                                <td>${union_name}</td>
                                <td class="actions">
                                    <a href="" data-toggle="modal" class="on-default btn btn-outline-info btn-sm edit-row user-edit-row" data-edit-user-id="${user.id}"><i class="fas fa-pencil-alt"></i></a>
                                    <a href="" class="on-default btn btn-outline-danger btn-sm delete-user" data-delete-user-id="${user.id}"><i class="far fa-trash-alt"></i></a>
                                </td>
                            </tr>`;
                        });

                        $('#UserTable').empty();
                        $('#UserTable').append(user_list);
                      
                        if(numRows > offset)
                        {
                            let pageNumber = Math.ceil(numRows / offset);
                            for(let i = 1; i <= pageNumber;) {
                                paginate_url = "{{url('/user/lists')}}/"+i+"/"+offset;
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