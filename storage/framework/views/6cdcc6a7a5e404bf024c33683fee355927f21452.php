<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Question Bank </h3>
            </div>
           <?php if(session('success')): ?>
    <div class="alert alert-danger">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">

                    </div>
                    <div class=" col-md-2 float-right">
                        <a href="<?php echo e(route('admin.qb.add')); ?>"> <button type="button" class="btn  btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add QB
                            </button></a>
                    </div>
                    <form method="POST" id="myForm" action="<?php echo e(route('excel.upload')); ?>" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <input type="file" name="file" accept="text/csv, application/csv" required>
    <input type="submit" value="Import">
</form>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped qb-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th width="50%">Question</th>
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
                <input type="hidden" name="qb" id="qb-id" required>
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


    $('.qb-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.qb.list')); ?>",
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
                        {"targets": 1, "name": "qb_question", 'searchable': false, 'orderable': false},
                        {"targets": 2, "name": "status", 'searchable': false, 'orderable': false},
                        {"targets": 3, "name": "action", 'searchable': false, 'orderable': false},
                    ],
                    'order': [[2, 'desc']],

            });

            $('body').on('click','.status-update', function(){

                let qb_id = $(this).attr('data-id')

                

                let status = this.checked ? 1 : 0;

                $.ajax({
                        url: "<?php echo e(route('admin.update.qb.status')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            qb_id: qb_id,
                            status: status,
                            _token: "<?php echo e(csrf_token()); ?>"
                        },
                        success: function (data) {

                            console.log(data)
                        


                        }
                    });

            })

             $('body').on('click', '.deleteQB', function(){

            $('#qb-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')
        })

        //SUbmit delete Modal 
        $('#deleteModuleSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.qb')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.qb-table').DataTable().ajax.reload(null, false);

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
   $(function()
{
  $('#myForm').submit(function(){
    $("input[type='submit']", this)
      .val("Please Wait...")
      .attr('disabled', 'disabled');
    return true;
  });
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/question_bank/qb_lists.blade.php ENDPATH**/ ?>