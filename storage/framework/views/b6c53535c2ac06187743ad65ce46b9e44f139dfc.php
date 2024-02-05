<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>User Management </h3>
            </div>
            <div class="ibox-content">
                
                <!-- <div class="row mt-3 ">
                    <div class="col-sm-3"></div>
                   
                    <div class="col-sm-3"></div>

                </div> -->
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table">
                        <thead>
                            <tr>
                                <th style="width:50px">#</th>
                                <th>User Name</th>
                                <th>Phone Number</th>
                                <th>College Name</th>
                                <th>Year</th>
                                <th>Subscribed</th>
                                <th>Plan</th>
                                <th>Expiry of Plan</th>
                                <th>Status</th>
                                <th>Action</th>
                                <th>Date of registration</th>
                            </tr>
                        </thead>
                        <tbody id="table_data">

                            
                        </tbody>
                    </table>
                </div>
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
        <form id="deleteModuleSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="user" id="user-id" required>
                <?php echo csrf_field(); ?>
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
                <div class="pull-left" id="meta_data"></div>
                <div class="btn-group pull-right" id="pagination">
                    
                </div>
                <br><br>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        get_data()
    });

    function get_data(link = null) {

        return false

        let search = $('#search').val();
        let status = $('#status').val();
        let from_date = $('#from_date').val();
        let to_date = $('#to_date').val();


        let row = `
        <tr>
            <td colspan="7"> Loading... </td>
        </tr>
    `;
        $('#table_data').html(row);
        $('#pagination').html('');

        $.ajax({
            url: link ? link : "",
            type: 'get',
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                from_date: from_date,
                to_date: to_date,
                search: search,
                status: status,

            },
            success: function(res) {
                console.log(res);
                $('#data_count').text(res.count);
                $('#meta_data').text('');
                if (res.data.data.length) {
                    table_data = res.data.data;
                    $('#meta_data').text(
                        `Showing results ${res.data.from} to ${res.data.to} of ${res.data.total}`
                    );
                    $('#table_data').html('');
                    $.each(res.data.data, function(key, value) {
                        // console.log(value.answer_key_details)
                        let row = `
                        <tr>
                            <td> ${res.data.from++} </td> 
                            <td> ${value.name ? value.name : '-'} </td>
                            <td> ${value.mobile ? value.mobile : '-'} </td>
                            <td> `;

                        row += `<a href="#" class="">View Details</a>`;
                        row += `
                             </td>                        
                            <td>
                                <span class="badge ${value.status==1?'bg-success':'bg-danger'} ">${value.status==1?'Active':'Inactive'}</span>
                            </td> 
                            <td class="text-capitalize"> 
                                <div class="pull-right">                                    
                               
                                <label class="switch">
                                <input  onclick="chnageStatus(${value.id})" type="checkbox" ${value.status ? 'checked' : ''}>
                                <span class="slider round"></span>
                                </label>
                                    
                                    </div>
                                </td>
                                <td>${value.created_at ? value.created_at : '_'}</td>
                            </tr>
                    `;
                        $('#table_data').append(row);
                    })
                    let pagination = `
                    <button type="button" onclick="get_data('${res.data.first_page_url}')" class="btn btn-white text-muted"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></button>
                `;
                    if (res.data.prev_page_url) {
                        pagination += `
                        <button onclick="get_data('${res.data.prev_page_url}')" class="btn btn-white">${res.data.current_page - 1}</button>
                    `;
                    }
                    pagination += `
                      <button class="btn btn-white active">${res.data.current_page}</button>
                `;
                    if (res.data.next_page_url) {
                        pagination += `
                        <button onclick="get_data('${res.data.next_page_url}')" class="btn btn-white">${res.data.current_page + 1}</button>
                    `;
                    }
                    pagination += `
                      <button type="button" onclick="get_data('${res.data.last_page_url}')" class="btn btn-white text-muted"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i> </button>
                `;
                    $('#pagination').html(pagination);
                } else {
                    let row = `
                    <tr>
                        <td colspan="12"> Record not found! </td>
                    </tr>
                `;
                    $('#table_data').html(row);
                    var elem = document.querySelector('.js-switch');
                    var switchery = new Switchery(elem, {
                        color: '#1AB394'
                    });
                }
            },
            error: function(err) {
                // console.log(err);
            }
        });
    }

     $('body').on('click', '.deleteVideo', function(){

            $('#user-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')
        })

 //SUbmit delete Modal 
        $('#deleteModuleSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.user')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.users-table').DataTable().ajax.reload(null, false);

                            setTimeout(() => {
                                $('#errosDeleteMsg').html(``)
                                $('#deleteModal').modal('hide')

                            }, 1000);
                        }
                        else{

                            $('#errosDeleteMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

    // Change Status
    function chnageStatus(id) {

        $.ajax({
            url: "",
            type: 'post',
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                id: id

            },
            success: function(response) {
                console.log(response);
                // swal({
                //     position: 'center',
                //     type: 'success',
                //     title: response.message,
                //     showConfirmButton: false,
                //     timer: 1000
                // });
                location.reload();
            },
            error: function(error) {

            }

        });

    }
</script>
<script>
    $(document).ready(function(){
            $('.users-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.users.list')); ?>",
                        dataFilter: function (data) {
                            var json = jQuery.parseJSON(data);
                            json.recordsTotal = json.recordsTotal;
                            json.recordsFiltered = json.recordsFiltered;
                            json.data = json.data;
                            return JSON.stringify(json); // return JSON string
                        }
                    },
                    'columnDefs': [
                        {'targets': 0, 'searchable': false, 'orderable': false,},
                        {"targets": 1, "name": "name", 'searchable': true, 'orderable': true},
                        {"targets": 2, "name": "mobile_number", 'searchable': false, 'orderable': false},
                        {"targets": 3, "name": "college_name", 'searchable': false, 'orderable': true},
                        {"targets": 4, "name": "year_id", 'searchable': false, 'orderable': true},
                        {"targets": 5, "name": "subsribed", 'searchable': false, 'orderable': false},
                        {"targets": 6, "name": "plan", 'searchable': false, 'orderable': false},
                        {"targets": 7, "name": "date", 'searchable': false, 'orderable': false},
                        {"targets": 8, "name": "status", 'searchable': false, 'orderable': false},
                        {"targets": 9, "name": "action", 'searchable': false, 'orderable': false},
                        {"targets": 10, "name": "Date of Registration", 'searchable': false, 'orderable': false},
                    ],
                    'order': [[1, 'desc']],

            });

            $('body').on('click','.status-update', function(){

                let user_id = $(this).attr('data-id')

                let status = this.checked ? 1 : 0;

                $.ajax({
                        url: "<?php echo e(route('admin.update.users.status')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            user_id: user_id,
                            status: status,
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function (data) {


                        


                        }
                    });

            })

        });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\blogs\resources\views/admin/user-management/index.blade.php ENDPATH**/ ?>