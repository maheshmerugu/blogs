<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Manage Video </h3>
            </div>
            <div class="ibox-content">
                 <div class="row">
                    <div class="col-md-10 float-left">

                    </div>
                    <div class=" col-md-2 float-right">
                        <a href="<?php echo e(route('admin.video.add')); ?>"> <button type="button" class="btn  btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Video
                            </button></a>
                    </div>

                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped video-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Year</th>
                                <th>Subject</th>
                                <th>Teacher</th>
                                <th>Module</th>
                                <th>Topic</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody id="table_data">

                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
                <input type="hidden" name="video" id="video-id" required>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>

<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<script>
   $(document).ready(function(){



            
 $('.video-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.video.list')); ?>",
                        dataFilter: function (data) {
                            var json = jQuery.parseJSON(data);
                           // alert(34)
                            json.recordsTotal = json.recordsTotal;
                            json.recordsFiltered = json.recordsFiltered;
                            json.data = json.data;
                            return JSON.stringify(json); // return JSON string
                        }
                    },
                    'columnDefs': [
                        {'targets': 0, 'searchable': false, 'orderable': false,},
                        {"targets": 1, "name": "year_name", 'searchable': true, 'orderable': true},
                        {"targets": 2, "name": "subject_name", 'searchable': false, 'orderable': false},
                        {"targets": 3, "name": "teacher_name", 'searchable': false, 'orderable': false},
                        {"targets": 4, "name": "module", 'searchable': false, 'orderable': false},
                        {"targets": 5, "name": "status", 'searchable': false, 'orderable': false},
                        {"targets": 6, "name": "status", 'searchable': false, 'orderable': false},
                        {"targets": 7, "name": "action", 'searchable': false, 'orderable': false},
                        {"targets": 8, "name": "action", 'searchable': false, 'orderable': false},
                    ],
                    'order': [[2, 'desc']],

            });
         
                  $('body').on('click','.status-update', function(){

                let video_id = $(this).attr('data-id')

                

                let status = this.checked ? 1 : 0;

                $.ajax({
                        url: "<?php echo e(route('admin.update.video.status')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            video_id: video_id,
                            status: status,
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function (data) {

                            console.log(data)
                        


                        }
                    });

            })
         $('body').on('click', '.deleteVideo', function(){

            $('#video-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')
        })

        //SUbmit delete Modal 
        $('#deleteModuleSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.video')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.video-table').DataTable().ajax.reload(null, false);

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

   })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/video/video_lists.blade.php ENDPATH**/ ?>