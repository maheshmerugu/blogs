<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
             <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.qb.list.view')); ?>"> QB list </a>/ Edit QB</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Update Question Bank</h4>
                                    <form action="#" id="update-qb" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                         <input type="hidden" name="id" value="<?php echo e($qb->id); ?>">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Year</label>
                                                <select class="form-control" name="year" id="year_id" required>
                                                    <option>--Select Year--</option>
                                                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $qb->year_id ? "selected" : ""); ?>><?php echo e($item->year_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Subject</label>
                                                <select class="form-control" name="subject" id="subject_id" required>
                                                    <option>--Select Subject--</option>
                                                </select>
                                            </div>
                                             <!-- <div class="col-md-3">
                                                <label>Teacher</label>
                                                <select class="form-control" name="teacher" id="teacher_id" required>
                                                    <option>--Select Teacher--</option>
                                                </select>
                                            </div> -->
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
                                           
                                            <div class="col-md-3">
                                                <label>Difficulty level</label>
                                                <select class="form-control" name="difficulty_level_id" id="difficulty_level" required>
                                                    <option>--Select Difficulty level--</option>
                                                    <?php $__currentLoopData = $difficulty_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($dl->id); ?>" <?php echo e($dl->id == $qb->difficulty_level_id ? "selected" : ""); ?>><?php echo e($dl->name); ?></option>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Refrence</label>
                                                    <textarea class="summernote" name="reference" id="refrence_id"  rows="3" ><?php echo e($qb->reference); ?></textarea>
                                                    <input type="file" name="refrence_image" >
                                                    <?php if($qb->refrence_image): ?>
                                                    <img src="<?php echo e(asset('images/question_bank/refrence/'.$qb->refrence_image)); ?>" height="100px" width="100px">
                                                    <?php endif; ?>
                                                   
                                                </div>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Question</label>
                                                    <textarea class="summernote" name="question" rows="5" ><?php echo e($qb->qb_question); ?></textarea>
                                                    <input type="file" class="form-control" name="question_image" value="<?php echo e($qb->question_image); ?>">
                                                    <?php if($qb->question_image): ?>
                                                    <img src="<?php echo e(asset('images/question_bank/question_image/'.$qb->question_image)); ?>" height="100px" width="100px">
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="row mt-3" id="optionAppend">

                                                    <?php $__currentLoopData = $options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $vl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div class="col-md-6 qoption">
                                                        <input type="radio" value="<?php echo e($vl->id); ?>" name="answer" class="float-right optionsSel" data-id="<?php echo e($vl->id); ?>" <?php echo e(($vl->id == $qb->qb_answer) ? "checked" : ""); ?> required>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Option</label>
                                                            <textarea class="summernote" name="option[]" rows="3" ><?php echo e($vl->option); ?></textarea>
                                                             <input type="file" name="option_image[]" >
                                                             <?php if(!empty($vl->option_image)): ?>
                                        <?php if(file_exists(public_path('images/question_bank/option_image/' . $vl->option_image))): ?>

                                                             <img src="<?php echo e(asset('images/question_bank/option_image/'.$vl->option_image)); ?>" height="100px" width="100px">
                                                                             <button type="button" class="btn btn-danger btn-sm delete-option-image" data-id="<?php echo e($vl->id); ?>"
                        onclick="deleteOptionImage('<?php echo e(route('delete.option.image', ['optionID' => $vl->id])); ?>')">
                        <i class="fa fa-trash"></i> <?php endif; ?>  

                                                             <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                            

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Explanation</label>
                                                    <textarea class="summernote" id="explanation" name="explanation" rows="3" ><?php echo e($qb->qb_explanation); ?></textarea>
                                                      <input type="file" name="explanation_image" value="<?php echo e($qb->explanation_image); ?>">
                                                    <?php if($qb->explanation_image): ?>
                                                    <img src="<?php echo e(asset('images/question_bank/explanation_image/'.$qb->explanation_image)); ?>" height="100px" width="100px">
                                                    <?php endif; ?>
                                                    
                                                </div>
                                            </div>
                                           
                                           
                                            <div class="col-md-12 text-center">
                                                <button class="btn btn-md btn-primary" type="submit" id="btn-create">Update</button>
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


<!-- SUMMERNOTE -->
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>
<script>
           function deleteOptionImage(url) {
        var confirmation = confirm("Are you sure you want to delete this option image?");
        if (confirmation) {
            $.ajax({
                url: url,
                type: "DELETE",
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === "success") {
                        // Hide the option image from the UI
                        var optionImage = $('#image_' + response.option_id);
                        optionImage.hide();

                        // Remove the delete button
                        var deleteButton = $('.delete-option-image[data-url="' + url + '"]');
                        deleteButton.remove();
                        location.reload();
                    } else {
                        // Show an error message
                        alert(response.message);
                    }
                },
                error: function() {
                    // Show an error message
                    alert("Something went wrong, please try again");
                }
            });
        }
    }
    $(document).ready(function() {
        // Initialize Summernote
        $('.summernote').summernote();

        // Add event listener to Summernote
        $('.summernote').on('summernote.change', function() {
            // Get the content of Summernote editor
            var content = $(this).summernote('code');

            // Check if the content is empty (no text)
            if (!content.trim()) {
                // If Summernote is blank, reset the textboxes
                $('#explanation').val('');
               
            }
        });
    });
</script>

<script>
   

   $(document).ready(function(){


        // $('.summernote').summernote(); 
          

        getOptionData("<?php echo e($qb->year_id); ?>","<?php echo e($qb->subject_id); ?>","<?php echo e($qb->module_id); ?>","<?php echo e($qb->topic_id); ?>")

        $('#update-qb').on('submit', function(e){

            e.preventDefault()

            let myarray = [];
            var dataOption = $('.optionsSel').map(function() {
                 
                myarray.push($(this).attr('data-id'))
            });
            

             let fd = new FormData(this)
              $(".loader").show(); 

            fd.append('optionID',myarray)

                $.ajax({
                        url: "<?php echo e(route('admin.qb.update')); ?>",
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
                                    window.location.href = "<?php echo e(route('admin.qb.list.view')); ?>"
                                }, 1000);
                            }
                            else{
                                $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`)
                            }
                           
                    
                        }
                    });

        })

        function getOptionData(yearId = null, subjectId = null, moduleId = null, topicId = null){

            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('subject_id', subjectId)
                // fd.append('teacher_id', teacherID)
                fd.append('module_id', moduleId)
                fd.append('_token', "<?php echo e(csrf_token()); ?>")

            $.ajax({
                    url: "<?php echo e(route('admin.get.mcqSubjectModuleByYear')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {

                        $('#subject_id').html(`<option value="">--Select Subject--</option>`)                    
                       // $('#teacher_id').html(`<option value="">--Select Teacher--</option>`)                    
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
                       /* if(data.data.teacher.length > 0){

                            let options = '<option value="">--Select Teacher--</option>'

                            $.each(data.data.teacher, function (i, val) { 
                               console.log(teacherID)
                                options += `<option value="${val.id}" ${(val.id == teacherID) ? "selected" : ""}>${val.teacher_name}</option>`
                            });

                            
                            
                            $('#teacher_id').html(options)
                            

                        }*/
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
/*
        $('#teacher_id').on('change', function(){

            let yearID = $('#year_id').val()
            let subjectID = $('#subject_id').val()
            let teacherID = $(this).val()


            getOptionData(yearID,subjectID,teacherID)

        })*/
                $('#module_id').on('change', function(){

            let yearID = $('#year_id').val()
            let subjectID = $('#subject_id').val()
           // let teacherID = $('#teacher_id').val()
            let moduleID = $(this).val()


            getOptionData(yearID,subjectID,moduleID)

        })


   })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/question_bank/qb_edit.blade.php ENDPATH**/ ?>