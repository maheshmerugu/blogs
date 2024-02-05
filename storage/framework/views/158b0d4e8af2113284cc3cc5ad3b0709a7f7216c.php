<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<style>
  body {
    font-family: Arial, sans-serif;
  }
  .error-message {
    color: red;
    font-size: 12px;
  }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
             <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.schedule_classes.list.view')); ?>"> Schedule Class list </a>/ Edit Schedule Class</h3>
            </div>
            <div class="ibox-content"><div class="col-md-12 errorMsg"></div>
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Update Schedule Class</h4>
                                    <form action="#" id="update-video">
                                        <?php echo csrf_field(); ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                 <input type="hidden" name="id" value="<?php echo e($scheduleClass->id); ?>" required>
                                                <label>Year</label>
                                                <select class="form-control" name="year" id="year_id" required>
                                                    <option>--Select Year--</option>
                                                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $scheduleClass->year_id ? "selected" : ""); ?>><?php echo e($item->year_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Subject</label>
                                                <select class="form-control" name="subject" id="subject_id" required>
                                                    <option>--Select Subject--</option>
                                                </select>
                                            </div>
                                             <div class="col-md-3">
                                                <label>Teacher</label>
                                                <select class="form-control" name="teacher" id="teacher_id" required>
                                                    <option>--Select Teacher--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Topic</label>
                                                <select class="form-control" name="topic" id="topic_id" required>
                                                    <option>--Select Topic--</option>
                                                </select>
                                            </div>

                                           <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" value="<?php echo e($scheduleClass->email); ?>"  name="email" class="form-control" id="email" required>
                                        </div>
                                    </div>
                                           <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">Date</label>
                                            <input type="date"  value="<?php echo e($scheduleClass->date); ?>" name="date" class="form-control" id="date" required>
                                                                                      <p class="error-message" id="errorText"></p>

                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">Start Time</label>
                                           <input type="time" class="form-control" value="<?php echo e($scheduleClass->start_time); ?>" id="start-time" name="start_time" value="<?php echo e(old('start-time')); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3">
                                        <div class="form-group">
                                            <label for="">End Time</label>
                                           <input type="time" class="form-control" id="end-time" value="<?php echo e($scheduleClass->end_time); ?>"  name="end_time" value="<?php echo e(old('end-time')); ?>" required >
                                        </div>
                                    </div>
                                     <div class="col-md-12 mt-3">
                                        <div class="form-group">
                                            <label for="">Zoom Link</label>
                                            <input type="url" placeholder="http://www.example.com"  value="<?php echo e($scheduleClass->zoom_link); ?>"  name="zoom_link" class="form-control" id="zoom_link" required>
                                        </div>
                                    </div>
                                                                                     
                                            
                                          
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-md btn-primary" type="submit" id="btn-create">Update</button>
                                            </div>
                                            
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


<!-- SUMMERNOTE -->
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>
<script>
    const datePicker = document.getElementById('date');
    const errorText = document.getElementById('errorText');

    datePicker.addEventListener('input', () => {
      // Get the selected date value
      const selectedDate = new Date(datePicker.value);
      
      // Get the current date
      const currentDate = new Date();
      
      // Compare the selected date with the current date
      if (selectedDate < currentDate) {
        if (selectedDate.toISOString().split('T')[0] !== currentDate.toISOString().split('T')[0]) {
          datePicker.value = currentDate.toISOString().split('T')[0]; // Set to current date
          errorText.textContent = 'Please select a future date.';
        } else {
          errorText.textContent = '';
        }
      } else {
        errorText.textContent = '';
      }
    });
  </script>
<script>
      $(function() {
    // Get the start and end time input fields
    var $startTime = $('#start-time');
    var $endTime = $('#end-time');

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


        $('.summernote').summernote();    

        getOptionData("<?php echo e($scheduleClass->year_id); ?>","<?php echo e($scheduleClass->subject_id); ?>","<?php echo e($scheduleClass->teacher_id); ?>","<?php echo e($scheduleClass->topic_id); ?>")

        $('#update-video').on('submit', function(e){

            e.preventDefault()

             let fd = new FormData(this)
              $(".loader").show(); 

                $.ajax({
                        url: "<?php echo e(route('admin.scheduleClass.update')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (data) {
              $(".loader").hide(); 

                            if(data.status){
                                $('.errorMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                                
                                setTimeout(() => {
                                    window.location.href = "<?php echo e(route('admin.schedule_classes.list.view')); ?>"
                                }, 1000);
                            }
                            else{
                                $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`)
                            }
                           
                    
                        }
                    });
            });
            

            

        })

        function getOptionData(yearId = null, subjectId = null,teacherID=null,  topicId = null){

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
                               console.log(teacherID)
                                options += `<option value="${val.id}" ${(val.id == teacherID) ? "selected" : ""}>${val.teacher_name}</option>`
                            });

                            
                            
                            $('#teacher_id').html(options)
                            

                        }
                         
                        
                        if(data.data.topics.length > 0){

                             options = '<option value="">--Select Topic--</option>'

                            $.each(data.data.topics, function (i, val) { 
                                
                                options += `<option value="${val.id}"  ${(val.id == topicId) ? "selected" : ""}>${val.topic}</option>`
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
               


 
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/schedule_classes/edit.blade.php ENDPATH**/ ?>