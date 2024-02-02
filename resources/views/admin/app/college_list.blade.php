@extends('admin.layouts.app')

@section('css')
    
 <link href="{{ asset('admin') }}/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>College </h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                     <form method="POST" id="myForm" action="{{ route('admin.csv.college') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept="text/csv, application/csv" required>
                        <input type="submit" value="Import">
                    </form>
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addYearModal">
                            <i class="fa fa-plus"></i> Add College</button>
                    </div>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table year-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>College</th>
                                 <th >Action</th> 
                            </tr>
                        </thead>
                        <tbody id="table_data">
                            @foreach ($college as $key => $col)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $col->college_name }}</td>
                                     <td><a href="#" class="deleteYear" data-id="{{ $col->id }}"><i class="fa fa-trash text-danger"></i></a></td>  
                                    <!--<a href="#" class="editYear" data-id="{{ $col->id }}" data-year="{{ $col->year_name }}"><i class="fa fa-edit text-success"></i></a>-->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                
                <br><br>
            </div>
        </div>
    </div>
</div>
<!-- Add Year Modal -->
<div class="modal fade" id="addYearModal" tabindex="-1" role="dialog" aria-labelledby="addYearModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add College</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="add-year-form">
            <div class="modal-body">
                <div id="errosMsg"></div>
                <div class="form-group">
                    <label>College Name</label>
                    <input type="text" name="college_name" class="form-control">
                    @csrf
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
        
      </div>
    </div>
  </div>
  <!-- Add Year Modal End -->



  <!-- Delete Year Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="deleteYearSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="college_name" id="year-id" required>
                @csrf
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Delete</button>
              </div>
        </form>
        
      </div>
    </div>
  </div>
  <!-- Delete Year Modal End -->
  
@endsection

@section('js')

<script src="{{ asset('admin') }}/js/plugins/dataTables/datatables.min.js"></script>
<script src="{{ asset('admin') }}/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<script>
    $(document).ready(function(){

        $('.year-table').DataTable({
                pageLength: 10,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
               
            });

        $('#add-year-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "{{ route('admin.college.list.create') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                             window.location.reload()

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Delete Modal on Click
        $('body').on('click', '.deleteYear', function(){

            $('#year-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')

        })

        //SUbmit delete Modal 
        $('#deleteYearSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "{{ route('admin.delete.college') }}",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                             window.location.reload()

                            }, 1000);
                        }
                        else{

                            $('#errosDeleteMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

    })
</script>

@endsection