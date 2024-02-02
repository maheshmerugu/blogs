<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
             <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.video.list.view')); ?>"> Video list </a>/ Video Detail</h3>
            </div>
            <div class="ibox-content"><div class="col-md-12 errorMsg"></div>
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Update Video</h4>
                                    <form action="#" id="update-video">
                                        <?php echo csrf_field(); ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                 <input type="hidden" name="id" value="<?php echo e($video->id); ?>" required>
                                                <label>Year</label>
                                                <select class="form-control" name="year" id="year_id" required disabled>
                                                    <option>--Select Year--</option>
                                                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $video->year_id ? "selected" : ""); ?>><?php echo e($item->year_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Subject</label>
                                                <select class="form-control" name="subject" id="subject_id" required disabled>
                                                    <option>--Select Subject--</option>
                                                </select>
                                            </div>
                                             <div class="col-md-3">
                                                <label>Teacher</label>
                                                <select class="form-control" name="teacher" id="teacher_id" required disabled>
                                                    <option>--Select Teacher--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Module</label>
                                                <select class="form-control" name="module" id="module_id" required disabled>
                                                    <option>--Select Module--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Topic</label>
                                                <select class="form-control" name="topic" id="topic_id" required disabled>
                                                    <option>--Select Topic--</option>
                                                </select>
                                            </div>
                                         <div class="col-md-4 mt-3">
                                                <label>Video Title</label>
                                                <input type="video_title" name="video_title" id="" class="form-control" required value="<?php echo e($video->video_title); ?>" disabled>
                                            </div>
                                          
                                             <div class="col-md-4 mt-3">
                                                <label>Video Duration ( in sec:)</label>
                                                <input type="number" name="duration" id="" class="form-control" required placeholder="Duration..." value="<?php echo e($video->video_total_time); ?>" disabled>
                                            </div>
                                         
                                            <div class="col-md-6 mt-3">
                                                <label>Document</label>
                                                <input type="file" name="document" id="" class="form-control" disabled>
                                                 <a href="<?php echo e(asset('storage/app/documents/' . $video->doc_url)); ?>" target="_blank" download><?php echo e($video->doc_url); ?></a>
                                            </div>
                                            <br>
                                            
                                            
                                          
                                           
                                            
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
   $(document).ready(function(){


        $('.summernote').summernote();    

        getOptionData("<?php echo e($video->year_id); ?>","<?php echo e($video->subject_id); ?>","<?php echo e($video->teacher_id); ?>","<?php echo e($video->module_id); ?>","<?php echo e($video->topic_id); ?>")

        $('#update-video').on('submit', function(e){

            e.preventDefault()

             let fd = new FormData(this)

                $.ajax({
                        url: "<?php echo e(route('admin.video.update')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (data) {

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/video/video_detail.blade.php ENDPATH**/ ?>