<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Teacher </h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTeacherModal">
                            <i class="fa fa-plus"></i> Add Teacher</button>
                    </div>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table teacher-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Teacher</th>
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

<!-- Add Module Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" role="dialog" aria-labelledby="addModuleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add Teacher</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="add-teacher-form" enctype="multipart/form-data">
            <div class="modal-body">
                <div id="errosMsg"></div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" class="form-control" id="selYear" required>
                        <option value="">--Select Year--</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr->id); ?>"><?php echo e($yr->year_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject" class="form-control" id="selSubject" required>
                        <option value="">--Select Subject--</option>
                        
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Teacher</label>
                    <input type="text" name="teacher_name" placeholder="Enter teacher name" class="form-control" required>
                    <?php echo csrf_field(); ?>
                </div>
                 <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" placeholder="Enter teacher qualification" class="form-control" required>
                    <?php echo csrf_field(); ?>
                </div>
                 <div class="form-group">
                    <label>Teacher Image</label>
                    <input type="file" name="teacher_image"  class="form-control" required>
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
  <!-- Add Module Modal End -->

  <!-- Edit Subject Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1" role="dialog" aria-labelledby="editTeacherModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Teacher</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="update-teacher-form" enctype="multipart/form-data">
            <div class="modal-body">
                <div id="errosEditMsg"></div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" class="form-control" id="teacher-year" required>
                        <option value="">--Select Year--</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr->id); ?>"><?php echo e($yr->year_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject" class="form-control" id="teacher-subject" required>
                        <option value="">--Select Subject--</option>
                        
                    </select>
                 
                </div>
                <div class="form-group">
                    <label>Teacher Name</label>
                    <input type="text" name="teacher_name" class="form-control" id="teacher-name" required>
                    <input type="hidden" name="id" id="teacher-id" required>
                    <?php echo csrf_field(); ?>
                </div>

                  <div class="form-group">
                    <label>Qualification</label>
                    <input type="text" name="qualification" id="qualification" placeholder="Enter teacher qualification" class="form-control" required>
                    <?php echo csrf_field(); ?>
                </div>
                 <div class="form-group">
                    <label>Teacher Image</label>
                    <input type="file" name="teacher_image"  id="teacher_image" class="form-control" >
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
<div class="modal fade" id="deleteTeacher" tabindex="-1" role="dialog" aria-labelledby="deleteTeacherLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteTeacherLabel">Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="deleteTeacherSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="teacher" id="teacher-del-id" required>
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

        $('.teacher-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.teacher.list')); ?>",
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

            $('#selYear').on('change', function(){

                let yearId = $(this).val()
                subjectSelected(yearId, null)

            })

            

        $('#add-teacher-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.add.teacher')); ?>",
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
                                $('#add-teacher-form')[0].reset()
                                $('#addTeacherModal').modal('hide')
                                $('.teacher-table').DataTable().ajax.reload(null, false);

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Edit Modal on Click
        $('body').on('click', '.editTeacher', function(){

            $('#teacher-id').val($(this).attr('data-id'))
            $('#teacher-name').val($(this).attr('data-module'))
            $('#teacher-year').val($(this).attr('data-year'))
            $('#teacher-subject').val($(this).attr('data-subject'))
            $('#qualification').val($(this).attr('data-qualification'))
            $('#editTeacherModal').modal('show')

            subjectSelected($(this).attr('data-year'), $(this).attr('data-subject'))

        })

        $('body').on('change','#teacher-year', function(){

            let yearId = $(this).val()
            let subjectId = $('#teacher-subject').val()
           

            subjectSelected(yearId,subjectId)

        })

        function subjectSelected(yearId = null,subjectId = null){

            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('_token', "<?php echo e(csrf_token()); ?>")

            $.ajax({
                    url: "<?php echo e(route('admin.get.subjectByYear')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        console.log(data)


                        if(data.data.subjects.length > 0){

                            let options = '<option value="">--Select Subject--</option>'

                            $.each(data.data.subjects, function (i, val) { 
                                
                                options += `<option value="${val.id}" ${(val.id == subjectId) ? "selected" : ""}>${val.subject_name}</option>`
                            });

                            if(subjectId != null){
                                console.log("yesid")
                                $('#teacher-subject').html(options)
                            }else{
                                console.log("noid")

                                $('#selSubject').html(options)
                            }


                        }
                        else{


                            console.log("NO DATA")
                            //NO DATA
                        }
                       
                        
                    }
                });


        }

       

        //SUbmit Edit Modal 
        $('#update-teacher-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.update.teacher')); ?>",
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
                                $('#update-teacher-form')[0].reset()
                                $('#editTeacherModal').modal('hide')
                                $('.teacher-table').DataTable().ajax.reload(null, false);

                            }, 1000);
                        }
                        else{

                            $('#errosEditMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Delete Modal on Click
        $('body').on('click', '.deleteTeacher', function(){

            $('#teacher-del-id').val($(this).attr('data-id'))
            $('#deleteTeacher').modal('show')

        })

        //SUbmit delete Modal 
        $('#deleteTeacherSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.teacher')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                                $('#deleteTeacher').modal('hide')
                                $('.teacher-table').DataTable().ajax.reload(null, false);

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/categories/teacher.blade.php ENDPATH**/ ?>