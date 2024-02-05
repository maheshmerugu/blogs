<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Subject </h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addSubjectModal">
                            <i class="fa fa-plus"></i> Add Subject</button>
                    </div>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table subject-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>Year</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody id="table_data">
                           
                        </tbody>
                    </table>
                </div>

                
                <br><br>
            </div>
        </div>
    </div>
</div>
<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" role="dialog" aria-labelledby="addSubjectModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add Subject</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="add-subject-form">
            <div class="modal-body">
                <div id="errosMsg"></div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" class="form-control" id="" required>
                        <option value="">--Select Year--</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr->id); ?>"><?php echo e($yr->year_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                 <div class="form-group">
                    <label>Type</label>
                    <select name="video_type" class="form-control" id="" required>
                        <option value="">--Select Type--</option>
                       
                        <option value="1">Theory</option>
                        <option value="2">Labs</option>
                       
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" class="form-control" required>
                    <?php echo csrf_field(); ?>
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
  <!-- Add Subject Modal End -->

  <!-- Edit Subject Modal -->
<div class="modal fade" id="editSubjectModal" tabindex="-1" role="dialog" aria-labelledby="editSubjectModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Subject</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="update-subject-form">
            <div class="modal-body">
                <div id="errosEditMsg"></div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" class="form-control" id="sub-year" required>
                        <option value="">--Select Year--</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr->id); ?>"><?php echo e($yr->year_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Type</label>
                    <select name="video_type" class="form-control subType" id="sub-type" required>
                        <option value="">--Select Type--</option>
                         <option value="1">Theory</option>
                        <option value="2">Labs</option>
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <input type="text" name="subject" id="sub-name" class="form-control" required>
                    <input type="hidden" name="id" id="sub-id" required>
                    <?php echo csrf_field(); ?>
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
  <!-- Edit Subject Modal End -->



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
        <form id="deleteSubjectSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="subject" id="subject-id" required>
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

        $('.subject-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.subject.list')); ?>",
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
                        {"targets": 1, "name": "subject_name", 'searchable': true, 'orderable': true},
                        {"targets": 2, "name": "year", 'searchable': false, 'orderable': false},
                        {"targets": 3, "name": "action", 'searchable': false, 'orderable': false},
                       
                    ],
                    'order': [[1, 'asc']],

            });

        $('#add-subject-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.add.subject')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                                $('#errosMsg').html(``)
                                $('#add-subject-form')[0].reset()
                                $('#addSubjectModal').modal('hide')
                                $('.subject-table').DataTable().ajax.reload(null, false);

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Edit Modal on Click
        $('body').on('click', '.editSubject', function(){

            $('#sub-id').val($(this).attr('data-id'))
            $('#sub-name').val($(this).attr('data-subject'))
            $('#sub-year').val($(this).attr('data-year'))
            $('#sub-type').val($(this).attr('data-type')).change()
            $('#editSubjectModal').modal('show')

        })

        //SUbmit Edit Modal 
        $('#update-subject-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.update.subject')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            
                            $('#errosEditMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                                 $('#errosMsg').html(``)
                                $('#update-subject-form')[0].reset()
                                $('#editSubjectModal').modal('hide')
                                $('.subject-table').DataTable().ajax.reload(
                                            null, false);

                            }, 1000);
                        }
                        else{

                            $('#errosEditMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Delete Modal on Click
        $('body').on('click', '.deleteSubject', function(){

            $('#subject-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')

        })

        //SUbmit delete Modal 
        $('#deleteSubjectSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.subject')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                                $('#deleteModal').modal('hide')
                                $('.subject-table').DataTable().ajax.reload(null, false);

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/categories/subject.blade.php ENDPATH**/ ?>