<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
             <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.custom_qb.list.view')); ?>">Custom QB list </a>/ View (<?php echo e($user->name); ?>) Custom QB</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Detail QB</h4>
                                    <form action="#" id="update-daily-qb">
                                       
                                        <?php $__currentLoopData = $custom_qb; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $qb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="row">
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group img_block">
                                                    <label class="font-weight-bold">Question</label>
                                                    <p class="" name="question" rows="5" required><?php echo $qb->qb_question; ?></p>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="row mt-3" id="optionAppend">
                                        <?php
                                         $i=1;
                                        ?> 
                            <?php
                              $userAnswer = App\Models\CustomQBAnalytic::where('qb_id',$qb->id)->first();
                             ?>
                                    <?php if(isset($options) && $options != null): ?>
                                                    <?php $__currentLoopData = $qb->option; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            
                                                 
                                                    <div class="col-md-6 qoption" class="d-block">
                                                        
                                                        <input type="checkbox" onclick="return false" value="<?php echo e($value->id); ?>" name="answer" class="float-right optionsSel" data-id="<?php echo e($value->id); ?>" <?php echo e(($value->id == $qb->qb_answer) ? "checked" : ""); ?> required>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Option<?php echo e($i++); ?> 
                                                        <?php if($value->id == $qb->qb_answer): ?>
                                                        <p><b>(correct answer)</b></p>
                                                        <?php endif; ?>
                                                        
                                                        <?php if($value->id == $userAnswer->user_answer): ?>
                                                        <p><b>(user answer)</b></p>
                                                        <?php endif; ?></label>
                                                            <p class="" name="option[]" rows="3" required><?php echo $value->option; ?></p>
                                                        </div>

                                                    </div>
                                                   
                                                    
                                                   
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                     <?php endif; ?>
                                                </div>
                                            </div>
                                            

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Explanation</label>
                                                    <p class="" name="explanation" rows="3" required ><?php echo $qb->qb_explanation; ?></p>
                                                    
                                                </div>
                                            </div>
                                       
                                            <div class="col-md-12 errorMsg"></div>
                                        </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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

        getOptionData("<?php echo e($qb->year_id); ?>","<?php echo e($qb->subject_id); ?>","<?php echo e($qb->module_id); ?>","<?php echo e($qb->topic_id); ?>")

        $('#update-daily-qb').on('submit', function(e){

            e.preventDefault()

            let myarray = [];
            var dataOption = $('.optionsSel').map(function() {
                 
                myarray.push($(this).attr('data-id'))
            });
            

             let fd = new FormData(this)

            fd.append('optionID',myarray)

                $.ajax({
                        url: "<?php echo e(route('admin.qb.update')); ?>",
                        type: 'POST',
                        dataType: 'json',
                        data: fd,
                        contentType: false,
                        processData: false,
                        success: function (data) {

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/custom_qb/cqb_detail.blade.php ENDPATH**/ ?>