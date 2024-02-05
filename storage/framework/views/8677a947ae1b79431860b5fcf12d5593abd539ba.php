<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Module </h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addModuleModal">
                            <i class="fa fa-plus"></i> Add Module</button>
                    </div>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table module-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Module</th>
                                <th>Subject</th>
                                <th>Year</th>
                                <th>Action</th>
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
<div class="modal fade" id="addModuleModal" tabindex="-1" role="dialog" aria-labelledby="addModuleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add Module</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="add-module-form">
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
                    <select name="teacher" class="form-control" id="selTeacher" required>
                        <option value="">--Select Teacher--</option>
                        
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Module</label>
                    <input type="text" name="module" class="form-control" required>
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
<div class="modal fade" id="editModuleModal" tabindex="-1" role="dialog" aria-labelledby="editModuleModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Module</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="update-module-form">
            <div class="modal-body">
                <div id="errosEditMsg"></div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" class="form-control" id="mod-year" required>
                        <option value="">--Select Year--</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr->id); ?>"><?php echo e($yr->year_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject" class="form-control" id="mod-subject" required>
                        <option value="">--Select Subject--</option>
                        
                    </select>
                 
                </div>
                  <div class="form-group">
                    <label>Teacher</label>
                    <select name="teacher" class="form-control" id="mod-teacher" required>
                        <option value="">--Select Teacher--</option>
                        
                    </select>
                 
                </div>
                <div class="form-group">
                    <label>Module</label>
                    <input type="text" name="module" class="form-control" id="mod-name" required>
                    <input type="hidden" name="id" id="mod-id" required>
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
        <form id="deleteModuleSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="module" id="module-id" required>
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

        $('.module-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.module.list')); ?>",
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
                subjectSubMod(yearId, null)

            })

              $('#selSubject').on('change', function(){

                let yearId = $('#selYear').val()
                let subjectId = $(this).val()
                subjectSubMod(yearId, subjectId)

            })


             
        $('#add-module-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.add.module')); ?>",
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
                                $('#add-module-form')[0].reset()
                                $('#addModuleModal').modal('hide')
                                $('.module-table').DataTable().ajax.reload(null, false);

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Edit Modal on Click
        $('body').on('click', '.editModule', function(){
          //  alert($(this).attr('data-teacher'));
            $('#mod-id').val($(this).attr('data-id'))
            $('#mod-name').val($(this).attr('data-module'))
            $('#mod-year').val($(this).attr('data-year'))
            $('#mod-subject').val($(this).attr('data-subject'))
            $('#mod-teacher').val($(this).attr('data-teacher'))
            $('#editModuleModal').modal('show')

            subjectSubMod($(this).attr('data-year'), $(this).attr('data-subject'), $(this).attr('data-teacher'))

        })

        $('body').on('change','#mod-year', function(){

            let yearId = $(this).val()
            let subjectId = $('#mod-subject').val()
            let teacherId = $('#mod-teacher').val()

            subjectSubMod(yearId,subjectId,teacherId)

        })
         $('body').on('change','#modx-subject', function(){

            let yearId = $('#mod-year').val()
            let subjectId = $(this).val()
            let teacherId = $('#mod-teacher').val()

            subjectSubMod(yearId,subjectId,teacherId)

        })
       

         function subjectSubMod(yearId = null,subjectId = null, teacherId = null){

           // alert(teacherId);
            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('subject_id', subjectId)
                fd.append('_token', "<?php echo e(csrf_token()); ?>")

            $.ajax({
                    url: "<?php echo e(route('admin.get.subjectModuleByYear')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {

                        $('#mod-subject').html(`<option value="">--Select Subject--</option>`)                    
                        $('#mod-teacher').html(`<option value="">--Select Teacher--</option>`)
                        $('#selSubject').html(`<option value="">--Select Subject--</option>`)
                        $('#selTeacher').html(`<option value="">--Select Teacher--</option>`)

                    },
                    success: function (data) {

                        // console.log(data)
                            
                        if(data.data.subjects.length > 0){

                            let options = '<option value="">--Select Subject--</option>'

                            $.each(data.data.subjects, function (i, val) { 
                                
                                options += `<option value="${val.id}" ${(val.id == subjectId) ? "selected" : ""}>${val.subject_name}</option>`
                            });

                           


                                /* if(subjectId != null){
                                console.log("yesid")*/
                                $('#mod-subject').html(options)
                           // }else{
                                console.log("noid")

                                $('#selSubject').html(options)
                         //   }
                            

                        }

                        if(data.data.teacher.length > 0){

                             options = '<option value="">--Select Teacher--</option>'

                            $.each(data.data.teacher, function (i, val) { 
                                
                                options += `<option value="${val.id}" ${(val.id == teacherId) ? "selected" : ""}>${val.teacher_name}</option>`
                            });

                            if(subjectId != null && teacherId != null){

                                $('#mod-teacher').html(options)
                            }else{

                                $('#selTeacher').html(options)
                            }
                        }   
                       
                       
                        
                    }
                });


        }

        //SUbmit Edit Modal 
        $('#update-module-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.update.module')); ?>",
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
                                $('#update-module-form')[0].reset()
                                $('#editModuleModal').modal('hide')
                                $('.module-table').DataTable().ajax.reload(null, false);

                            }, 1000);
                        }
                        else{

                            $('#errosEditMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Delete Modal on Click
        $('body').on('click', '.deleteModule', function(){

            $('#module-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')

        })

        //SUbmit delete Modal 
        $('#deleteModuleSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.module')); ?>",
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
                                $('.module-table').DataTable().ajax.reload(null, false);

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/categories/module.blade.php ENDPATH**/ ?>