<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Topic </h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addTopicModal">
                            <i class="fa fa-plus"></i> Add Topic</button>
                    </div>
                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped topic-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Topic</th>
                                <th>Module</th>
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

<!-- Add Topic Modal -->
<div class="modal fade" id="addTopicModal" tabindex="-1" role="dialog" aria-labelledby="addTopicModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Add Topic</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="add-topic-form">
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
                    
                </div>
                 <div class="form-group">
                    <label>Teacher</label>
                    <select name="teacher" class="form-control" id="selTeacher" required>
                        <option value="">--Select Teacher--</option>
                        
                    </select>
                    
                </div>

                <div class="form-group">
                    <label>Module</label>
                    <select name="module" class="form-control" id="selModule" required>
                        <option value="">--Select Module--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Topic</label>
                    <input type="text" name="topic" class="form-control" required>
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
  <!-- Add Topic Modal End -->

  <!-- Edit Subject Modal -->
<div class="modal fade" id="editTopicModal" tabindex="-1" role="dialog" aria-labelledby="editTopicModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Edit Topic</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="" id="update-topic-form">
            <div class="modal-body">
                <div id="errosEditMsg"></div>
                <div class="form-group">
                    <label>Year</label>
                    <select name="year" class="form-control" id="top-year" required>
                        <option value="">--Select Year--</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $yr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($yr->id); ?>"><?php echo e($yr->year_name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php echo csrf_field(); ?>
                </div>
                <div class="form-group">
                    <label>Subject</label>
                    <select name="subject" class="form-control" id="top-subject" required>
                        <option value="">--Select Subject--</option>
                        
                    </select>
                    
                </div>
                 <div class="form-group">
                    <label>Teacher</label>
                    <select name="teacher" class="form-control" id="top-teacher" required>
                        <option value="">--Select Teacher--</option>
                        
                    </select>
                    
                </div>

                <div class="form-group">
                    <label>Module</label>
                    <select name="module" class="form-control" id="top-module" required>
                        <option value="">--Select Module--</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Topic</label>
                    <input type="text" name="topic" id="top-name" class="form-control" required>
                    <input type="hidden" name="id" id="top-id" required>
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
        <form id="deleteTopicSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="topic" id="topic-id" required>
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

        $('.topic-table').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.topic.list')); ?>",
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
                        {"targets": 1, "name": "topic", 'searchable': true, 'orderable': true},
                        {"targets": 2, "name": "module", 'searchable': false, 'orderable': true},
                        {"targets": 3, "name": "subject_name", 'searchable': false, 'orderable': true},
                        {"targets": 4, "name": "year_name", 'searchable': false, 'orderable': false},
                        {"targets": 5, "name": "action", 'searchable': false, 'orderable': false},
                       
                    ],
                    'order': [[1, 'asc']],

            });

            $('#selYear').on('change', function(){

                let yearId = $(this).val()
                subjectSubMod(yearId)

            })
            

            $('#selSubject').on('change', function(){

                let yearId = $('#selYear').val()
                let subjectId = $(this).val()

                subjectSubMod(yearId, subjectId)

            })
             $('#selTeacher').on('change', function(){

                let yearId = $('#selYear').val()
                let subjectId = $('#selSubject').val()
                let teacherId = $(this).val()
                subjectSubMod(yearId, subjectId,teacherId)

            })
            

        $('#add-topic-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.add.topic')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {



                        if(data.status){
                            
                            $('#errosMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.topic-table').DataTable().ajax.reload(null, false);

                            setTimeout(() => {
                                $('#add-topic-form')[0].reset()
                                $('#errosMsg').html(``)
                                $('#addTopicModal').modal('hide')

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Edit Modal on Click
        $('body').on('click', '.editTopic', function(){
            console.log($(this).attr('data-module'));
            $('#top-id').val($(this).attr('data-id'))
            $('#top-name').val($(this).attr('data-topic'))
            $('#top-module').val($(this).attr('data-module'))
            $('#top-year').val($(this).attr('data-year'))
            $('#top-subject').val($(this).attr('data-subject'))
            $('#top-teacher').val($(this).attr('data-teacher'))
            $('#editTopicModal').modal('show')

            subjectSubMod($(this).attr('data-year'), $(this).attr('data-subject'), $(this).attr('data-teacher'),$(this).attr('data-module'))

        })

        $('body').on('change','#top-year', function(){

            let yearId = $(this).val()
            let subjectId = $('#top-subject').val()
            let teacherId = $('#top-teacher').val()
            let moduleId = $('#top-module').val()
           
            subjectSubMod(yearId,subjectId,moduleId,teacherId)

        })

        $('body').on('change','#top-subject', function(){

            let yearId = $('#top-year').val()
            let subjectId = $(this).val()
            let teacherId = $('#top-teacher').val()
            let moduleId = $('#top-module').val()
            subjectSubMod(yearId,subjectId,moduleId,teacherId)

        })

        $('body').on('change','#top-teacher', function(){

            let yearId = $('#top-year').val()
            let subjectId = $('#top-subject').val()
            let teacherId = $(this).val()
            let moduleId = $('#top-module').val()
            subjectSubMod(yearId,subjectId,teacherId,moduleId)

        })

        function subjectSubMod(yearId = null,subjectId = null,teacherId = null, moduleId = null){

            console.log(teacherId)

            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('subject_id', subjectId)
                fd.append('teacher_id', teacherId)
                fd.append('_token', "<?php echo e(csrf_token()); ?>")

            $.ajax({
                    url: "<?php echo e(route('admin.get.subjectModuleByYear')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {

                        $('#top-subject').html(`<option value="">--Select Subject--</option>`)                    
                        $('#top-teacher').html(`<option value="">--Select Teacher--</option>`)                    
                        $('#top-module').html(`<option value="">--Select Module--</option>`)
                        $('#selSubject').html(`<option value="">--Select Subject--</option>`)
                        $('#selTeacher').html(`<option value="">--Select Teacher--</option>`)
                        $('#selModule').html(`<option value="">--Select Module--</option>`)

                    },
                    success: function (data) {

                     console.log(data)
                            
                        if(data.data.subjects.length > 0){

                            let options = '<option value="">--Select Subject--</option>'

                            $.each(data.data.subjects, function (i, val) { 
                              
                                options += `<option value="${val.id}" ${(val.id == subjectId) ? "selected" : ""}>${val.subject_name}</option>`
                            });

                            
                            $('#top-subject').html(options)
                       
                            $('#selSubject').html(options)
                            

                        }if(data.data.teacher.length > 0){

                            let options = '<option value="">--Select Teacher--</option>'

                            $.each(data.data.teacher, function (i, val) { 
                              
                                options += `<option value="${val.id}" ${(val.id == teacherId) ? "selected" : ""}>${val.teacher_name}</option>`
                            });

                            
                            $('#top-teacher').html(options)
                       
                            $('#selTeacher').html(options)
                            

                        }
                       
                        if(data.data.modules.length > 0){

                             options = '<option value="">--Select Module--</option>'

                            $.each(data.data.modules, function (i, val) { 
                                
                                options += `<option value="${val.id}" ${(val.id == moduleId) ? "selected" : ""}>${val.module}</option>`
                            });

                            if(subjectId != null && moduleId != null && teacherId != null){

                                $('#top-module').html(options)
                            }else{

                                $('#selModule').html(options)
                            }
                        }   
                       
                         
                        
                    }
                });


        }

       

        //SUbmit Edit Modal 
        $('#update-topic-form').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.update.topic')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            
                            $('#errosEditMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.topic-table').DataTable().ajax.reload(null, false);

                            setTimeout(() => {
                                $('#errosMsg').html(``)
                                $('#update-topic-form')[0].reset()
                                $('#errosEditMsg').html(``)
                                $('#editTopicModal').modal('hide')

                            }, 1000);
                        }
                        else{

                            $('#errosEditMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })

        //Show Delete Modal on Click
        $('body').on('click', '.deleteTopic', function(){

            $('#topic-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')

        })

        //SUbmit delete Modal 
        $('#deleteTopicSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.delete.topic')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.topic-table').DataTable().ajax.reload(null, false);

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/categories/topic.blade.php ENDPATH**/ ?>