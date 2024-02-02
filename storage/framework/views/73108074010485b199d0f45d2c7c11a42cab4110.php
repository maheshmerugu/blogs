<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.schedule_classes.list.view')); ?>"> Schedule class list </a>/ Add schedule class</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="offset-md-2 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h4>Create Schedule class</h4>
                                    <form action="#" id="create-video">
                                        <?php echo csrf_field(); ?>
                                        <div class="row">
                                            <div class="col-md-4 mt-3">
                                                <label>Year</label>
                                                <select class="form-control" name="year" id="year_id" required>
                                                    <option>--Select Year--</option>
                                                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->year_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4 mt-3">
                                                <label>Subject</label>
                                                <select class="form-control" name="subject" id="subject_id" required>
                                                    <option>--Select Subject--</option>
                                                </select>
                                            </div>
                                             <div class="col-md-4 mt-3">
                                                <label>Teacher</label>
                                                <select class="form-control" name="teacher" id="teacher_id" required>
                                                    <option>--Select Teacher--</option>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-4 mt-3">
                                                <label>Topic</label>
                                                <select class="form-control" name="topic" id="topic_id" required>
                                                    <option>--Select Topic--</option>
                                                </select>
                                            </div>
                                          <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email"  name="email" class="form-control" id="email" required>
                                        </div>
                                    </div>
                                     <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <input type="date" min="<?= date('Y-m-d')?>" name="date" class="form-control" id="date" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">Start Time</label>
                                           <input type="time" class="form-control" id="start-time" name="start_time" value="<?php echo e(old('start-time')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">End Time</label>
                                           <input type="time" class="form-control" id="end-time" name="end_time" value="<?php echo e(old('end-time')); ?>">
                                        </div>
                                    </div>
                                    
                                     <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="">Zoom Link</label>
                                            <input type="url" name="zoom_link" class="form-control"  placeholder="http://www.example.com"  id="zoom_link" required>
                                        </div>
                                    </div>
                                           
                                            <div class="col-md-12 mt-3 text-center">
                                                <button class="btn btn-md btn-primary" type="submit" id="btn-create">Submit</button>
                                            </div>
                                            <div class="col-md-12 errorMsg"></div>
                                        </div>
                                    </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
               

               
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
$(function() {
    // Get the start and end time input fields
    var $startTime = $('#start-time');
    var $endTime = $('#end-time');
    var $dateField = $('#date');

    // Add a change event handler to the date input field
    $dateField.change(function() {
        // Get the selected date value
        var selectedDate = $dateField.val();

        // Get the current date value in "YYYY-MM-DD" format
        var currentDate = new Date().toISOString().split('T')[0];

        // Disable the end time field if the selected date is a previous date
        if (selectedDate < currentDate) {
            $endTime.prop('disabled', true);
        } else {
            $endTime.prop('disabled', false);
        }
    });

    // Add a change event handler to the end time input field
    $endTime.change(function() {
        // Get the values of both input fields
        var startTimeValue = $startTime.val();
        var endTimeValue = $endTime.val();

        // Compare the start and end times
        if (endTimeValue <= startTimeValue) {
            // If the end time is less than or equal to the start time, show an error message
            alert('End time must be greater than start time.');
            // Reset the end time to the previous value
            $endTime.val($endTime.data('previous-value'));
        } else {
            // If the end time is greater than the start time, save the end time value
            $endTime.data('previous-value', endTimeValue);
        }
    });
});

   $(document).ready(function(){
   
        $('#create-video').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)
              $(".loader").show(); 

            $.ajax({
                    url: "<?php echo e(route('admin.schedule_classes.store')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
              $(".loader").hide(); 

                        console.log(data)

                        if(data.status){
                            $('.errorMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            
                            setTimeout(() => {
                                window.location.href = "<?php echo e(route('admin.schedule_classes.list.view')); ?>"
                            }, 3000);
                        }
                        else{
                            $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`)
                        }
                        
                
                    }
                });

        })

      
        

        function getOptionData(yearId = null, subjectId = null,teacherID=null){
         //   console.log(teacherID)
            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('subject_id', subjectId)
                fd.append('teacher_id', teacherID)
                fd.append('_token', "<?php echo e(csrf_token()); ?>")

            $.ajax({
                    url: "<?php echo e(route('admin.get.topicTeacherByYear')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {

                        $('#subject_id').html(`<option value="">--Select Subject--</option>`)                    
                        $('#teacher_id').html(`<option value="">--Select Teacher--</option>`)                    
                        $('#topic_id').html(`<option value="">--Select Topic--</option>`)
                        

                    },
                    success: function (data) {

                        
                        if(data.data.subjects.length > 0){

                            let options = '<option value="">--Select Subject--</option>'

                            $.each(data.data.subjects, function (i, val) { 
                                
                                options += `<option value="${val.id}" ${(val.id == subjectId) ? "selected" : ""}>${val.subject_name}</option>`
                            });

                            $('#subject_id').html(options)
                            

                        }
                        if(data.data.teacher.length > 0){

                            let options = '<option value="">--Select Teacher--</option>'

                            $.each(data.data.teacher, function (i, val) { 
                              
                                options += `<option value="${val.id}" ${(val.id == teacherID) ? "selected" : ""}>${val.teacher_name}</option>`
                            });

                            
                            
                            $('#teacher_id').html(options)
                            

                        }
                        
                        //console.log(data)
                        if(data.data.topics.length > 0){

                             options = '<option value="">--Select Topic--</option>'

                            $.each(data.data.topics, function (i, val) { 
                                
                                options += `<option value="${val.id}">${val.topic}</option>`
                            });

                            $('#topic_id').html(options)
                            
                        } 
                       
                       
                        
                    }
                });

        }




        $('#year_id').on('change', function(){

            let yearId = $(this).val()
            getOptionData(yearId)

        })

        $('#subject_id').on('change', function(){

            let yearID = $('#year_id').val()
            let subjectID = $(this).val()

            getOptionData(yearID,subjectID)
        })

        $('#teacher_id').on('change', function(){

            let yearID = $('#year_id').val()
            let subjectID = $('#subject_id').val()
            let teacherID = $(this).val()


            getOptionData(yearID,subjectID,teacherID)

        })
                

        
       

   })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/schedule_classes/add.blade.php ENDPATH**/ ?>