<?php $__env->startSection('css'); ?>
<link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
   <div class="col-lg-12">
      <div class="ibox float-e-margins">
         <div class="ibox-title">
            <h3><a href="<?php echo e(route('admin.video.list.view')); ?>"> Video list </a>/ Edit video</h3>
         </div>
         <div class="ibox-content">
            <div class="col-md-12 errorMsg"></div>
            <div class="row">
               <div class="col-md-12">
                  <div class="card">
                     <div class="card-body">
                        <h4>Update Video</h4>
                        <form action="#" id="update-video" class="uploadForm" enctype="multipart/form-data">
                           <?php echo csrf_field(); ?>
                           <div class="row">
                              <div class="col-md-3">
                                 <input type="hidden" name="id" value="<?php echo e($video->id); ?>" required>
                                 <label>Year</label>
                                 <select class="form-control" name="year" id="year_id" required>
                                    <option>--Select Year--</option>
                                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $video->year_id ? "selected" : ""); ?>><?php echo e($item->year_name); ?></option>
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
                                 <label>Module</label>
                                 <select class="form-control" name="module" id="module_id" required>
                                    <option>--Select Module--</option>
                                 </select>
                              </div>
                              <div class="col-md-3">
                                 <label>Topic</label>
                                 <select class="form-control" name="topic" id="topic_id" required>
                                    <option>--Select Topic--</option>
                                 </select>
                              </div>
                               <div class="col-md-4 mt-3">
                                 <label>Video Title</label>
                                 <input type="text" name="video_title" id="" class="form-control" required placeholder="Enter video title" value="<?php echo e($video->video_title); ?>">
                              </div>
                              <div class="col-md-4 mt-3">
                                 <label>Video Duration (in sec:)</label>
                                 <input type="number" name="duration" id="" class="form-control" required placeholder="Duration..." value="<?php echo e($video->video_total_time); ?>">
                              </div>
                              <!--  <div class="col-md-6 mt-3">
                                 <label>Choose Video</label>
                                 <input type="file" name="video" id="" class="form-control video"  accept="video/*" >Video Id : <?php echo e($video->video_url); ?>

                                 
                                 
                                 </div> -->
                              <div class="col-md-6 mt-3">
                                 <label>Choose Video</label>
                                 <div class="radio-container">
                                    <label>
                                    <input type="radio" name="video_option" value="1" <?php echo e($video->video_option == 1 ? 'checked' : ''); ?>>
                                    Upload Video
                                    </label>
                                     <label>
                                    <input type="radio" name="video_option" value="2" <?php echo e($video->video_option == 2 ? 'checked' : ''); ?>>
                                    Video URL
                                    </label>
                                 </div>
                                 <div class="col-md-6 mt-3">
                                 <label>Video Locker </label>
                                 <div class="radio-container">
                                    <label>
                                    <input type="radio" name="video_type" value="1" <?php echo e($video->video_type == 1 ? 'checked' : ''); ?>>
                                    Free
                                    </label>
                                     <label>
                                    <input type="radio" name="video_type" value="0" <?php echo e($video->video_type == 0 ? 'checked' : ''); ?>>
                                   Paid
                                    </label>
                                 </div></div>
                                <!--  <div class="radio-container">
                                   
                                 </div> -->
                                 <div class="upload-container">
                                    <label>Choose Video</label>
                                    <input type="file" name="video" id="video" class="form-control video" accept="video/*"><?php if($video->video_option == 1): ?><?php echo e($video->video_url); ?><?php endif; ?>
                                    <div class="error-msgs"></div>
                                 </div>
                                 <div class="url-container" style="display: none;">
                                    <label>Enter Video Id</label>
                                    <input type="text" name="video_id" placeholder="Enter Id" id="video_url" class="form-control" value="<?php if($video->video_option == 2): ?><?php echo e($video->video_url); ?><?php endif; ?>">
                                 </div>
                              </div>
                              <div class="col-md-6 mt-3">
                                 <label>Document</label>
                                 <input type="file" name="document" id="" class="form-control" >
                                 <a href="<?php echo e(asset('storage/app/documents/' . $video->doc_url)); ?>" download><?php echo e($video->doc_url); ?></a>
                              </div>
                              <br>
                               <div class="col-md-6 mt-3">
                                                <label>Choose Video Thumbnail</label>
                                                <input type="file" name="video_thumbnail" id="" class="form-control"  accept="image/*">
                                               <?php if($video->video_thumbnail): ?>
        <img src="<?php echo e(asset('video/thumbnail/' . $video->video_thumbnail)); ?>" height="100px" width="100px" alt="Video Thumbnail">
    
    <?php endif; ?>

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
<link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>
<script>

 $(document).ready(function() {
  $('.uploadForm .video').on('change', function(event) {
    var videoInput = $(this)[0];
    var videoFile = videoInput.files[0];
    var updateButton = $('#btn-create'); // Replace 'updateButton' with the actual ID or selector of your update button

    if (videoFile) {
      var fileSize = videoFile.size; // File size in bytes
      var maxSize = 200 * 1024 * 1024; // 200 MB limit in bytes

      if (fileSize > maxSize) {
        $('.error-msgs').html('The video file size should not exceed 200 MB.');
        videoInput.value = ''; // Clear the file input value
        videoInput.classList.add('is-invalid'); // Apply CSS class for error styling
        updateButton.prop('disabled', true); // Disable the update button

      } else {
        var errorMsg = $(this).closest('.uploadForm').find('.error-msgs')[0];
        $(errorMsg).empty(); // Clear the error message
        videoInput.classList.remove('is-invalid'); // Remove error styling CSS class
        updateButton.prop('disabled', false); // Enable the update button

      }
    }
  });
});


   document.addEventListener('DOMContentLoaded', function() {
   var uploadContainer = document.querySelector('.upload-container');
   var urlContainer = document.querySelector('.url-container');
   var uploadRadio = document.querySelector('input[name="video_option"][value="1"]');
   var urlRadio = document.querySelector('input[name="video_option"][value="2"]');
   var videoFileInput = document.getElementById('video');
   var videoUrlInput = document.getElementById('video_url');
   
   // Show/hide containers based on radio button selection
   function toggleVideoContainers() {
   if (uploadRadio.checked) {
     uploadContainer.style.display = 'block';
     urlContainer.style.display = 'none';
    // videoUrlInput.value = ''; // Clear the video URL input value
   } else if (urlRadio.checked) {
     uploadContainer.style.display = 'none';
     urlContainer.style.display = 'block';
   //  videoFileInput.value = ''; // Clear the file input value
   }
   }
   
   // Initial state
   toggleVideoContainers();
   
   // Add event listeners to radio buttons
   uploadRadio.addEventListener('change', toggleVideoContainers);
   urlRadio.addEventListener('change', toggleVideoContainers);
   });
   
 
   
</script>
<script>
   $(document).ready(function(){
   
   
        $('.summernote').summernote();    
   
        getOptionData("<?php echo e($video->year_id); ?>","<?php echo e($video->subject_id); ?>","<?php echo e($video->teacher_id); ?>","<?php echo e($video->module_id); ?>","<?php echo e($video->topic_id); ?>")
   
        $('#update-video').on('submit', function(e){
   
            e.preventDefault()
   
             let fd = new FormData(this)
                 $(".loader").show();
                $.ajax({
                        url: "<?php echo e(route('admin.video.update')); ?>",
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
                                    window.location.href = "<?php echo e(route('admin.video.list.view')); ?>"
                                }, 1000);
                            }
                            else{
                                $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`)
                            }
                           
                    
                        }
                    });
            });
            
   
            
   
        })
   
        function getOptionData(yearId = null, subjectId = null,teacherID=null, moduleId = null, topicId = null){
   
            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('subject_id', subjectId)
                fd.append('teacher_id', teacherID)
                fd.append('module_id', moduleId)
                fd.append('_token', "<?php echo e(csrf_token()); ?>")
   
            $.ajax({
                    url: "<?php echo e(route('admin.get.subjectModuleByYear')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
   
                        $('#subject_id').html(`<option value="">--Select Subject--</option>`)                    
                        $('#teacher_id').html(`<option value="">--Select Teacher--</option>`)                    
                        $('#module_id').html(`<option value="">--Select Module--</option>`)
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
                        if(data.data.modules.length > 0){
   
                             options = '<option value="">--Select Module--</option>'
   
                            $.each(data.data.modules, function (i, val) { 
                                
                                options += `<option value="${val.id}" ${(val.id == moduleId) ? "selected" : ""}>${val.module}</option>`
                            });
   
                            $('#module_id').html(options)
                            
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
                $('#module_id').on('change', function(){
   
            let yearID = $('#year_id').val()
            let subjectID = $('#subject_id').val()
            let teacherID = $('#teacher_id').val()
            let moduleID = $(this).val()
   
   
            getOptionData(yearID,subjectID,teacherID,moduleID)
   
        })
   
   
   
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/video/video_edit.blade.php ENDPATH**/ ?>