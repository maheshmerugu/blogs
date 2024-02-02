<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
           <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.qb.list.view')); ?>"> Question Bank list </a>/ Add qb</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Create Question Bank</h4>
                                    <form action="#" id="create-daily-qb">
                                        <?php echo csrf_field(); ?>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label>Year</label>
                                                <select class="form-control" name="year" id="year_id" >
                                                    <option value="">--Select Year--</option>
                                                    <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>"><?php echo e($item->year_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Subject</label>
                                                <select class="form-control" name="subject" id="subject_id">
                                                    <option value="">--Select Subject--</option>
                                                </select>
                                            </div>
                                            <!--  <div class="col-md-3">
                                                <label>Teacher</label>
                                                <select class="form-control" name="teacher" id="teacher_id" required>
                                                    <option>--Select Teacher--</option>
                                                </select>
                                            </div> -->
                                            <div class="col-md-3">
                                                <label>Module</label>
                                                <select class="form-control" name="module" id="module_id" >
                                                    <option value="">--Select Module--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Topic</label>
                                                <select class="form-control" name="topic" id="topic_id" >
                                                    <option value="">--Select Topic--</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label>Difficulty level</label>
                                                <select class="form-control" name="difficulty_level_id" id="difficulty_level" >
                                                    <option value="">--Select Difficulty level--</option>
                                                    <?php $__currentLoopData = $difficulty_level; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($dl->id); ?>"><?php echo e($dl->name); ?></option>

                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                          <!--   <div class="col-md-3">
                                                <label>Refrence</label>
                                                <input type="text" class="form-control" name="reference" id="refrence_id" required>
                                                   
                                            </div> -->
                                             <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Refrence</label>
                                                    <textarea class="summernote" name="reference" rows="3"  id="refrence_id" ></textarea>
                                                    <input type="file" name="refrence_image" >
                                                </div>
                                            </div>
                                           
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Question</label>
                                                    <textarea class="summernote" name="question" rows="5"></textarea>
                                                    <input type="file" name="question_image" >

                                                </div>
                                            </div>
                                            
                                            <button class="btn btn-info float-right" type="button" id="optionClick"><i class="fa fa-plus"></i> Add More option</button>
                                            <br>
                                            <div class="col-md-12">
                                                <div class="row mt-3" id="optionAppend">
                                                    <div class="col-md-6 qoption">
                                                        <input type="radio" value="1" name="answer" class="float-right" required>
                                                        <div class="form-group">
                                                            <label class="font-weight-bold">Option</label>
                                                            <textarea class="summernote" name="option[]" rows="3"></textarea>
                                                            <input type="file" name="option_image[]" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                           
                                                
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Explanation</label>
                                                    <textarea class="summernote" name="explanation" rows="3" ></textarea>
                                                        <input type="file" name="explanation_image" >

                                                    
                                                </div>
                                            </div>
                                            
                                           
                                            <div class="col-md-12 text-center">
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


<!-- SUMMERNOTE -->
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>
<script>
   $(document).ready(function(){


        $('.summernote').summernote();   
        
       
        $('#create-daily-qb').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)
              // $(".loader").show(); 

            $.ajax({
                    url: "<?php echo e(route('admin.qb.create')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
              // $(".loader").hide(); 

                        if(data.status){
                            $('.errorMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            
                            setTimeout(() => {
                                window.location.href = "<?php echo e(route('admin.qb.list.view')); ?>"
                            }, 3000);
                        }
                        else{
                            $('.errorMsg').html(`<div class="alert alert-danger">${data.message}</div>`)
                             setTimeout(function() {
                                    $('.errorMsg').html('');
                                }, 2000);
                        }
                        
                
                    }
                });

        })

        // function appendOption(){

        //     var optionID = $('.qoption').length + 1;

        //     $('#optionAppend').append(`<div class="col-md-6 qoption">
        //         <input type="radio" value="${optionID}" name="answer" class="float-right">
        //         <div class="form-group">
        //             <label class="font-weight-bold">Option</label>
        //             <textarea class="summernote" name="option[]" rows="3" required></textarea>
        //         </div>
        //     </div>`)

        // $('.summernote').summernote();

        // }

        // $('body').on('click', '#optionClick', function(){


        //      appendOption()
            
        // })
        
var optionCounter = 0; // To keep track of the number of options added

function appendOption() {
    if (optionCounter < 5) {
        var optionID = optionCounter + 1; // Create a unique ID for the option
        var optionHTML = `
        <div class="col-md-6 qoption">
            <input type="radio" value="${optionID}" name="answer" class="float-right">
            <div class="form-group">
                <label class="font-weight-bold">Option</label>
                <textarea class="summernote" name="option[]" rows="3" ></textarea>
                <input type="file" name="option_image[]" >
            </div>
            <button type="button" class="btn btn-danger removeOption">Remove</button>
        </div>`;

        $('#optionAppend').append(optionHTML);
        $('.summernote').summernote();

        optionCounter++; // Increment the option counter
        if (optionCounter === 4) {
            $('#optionClick').attr('disabled', true); // Disable the button when the limit is reached
        }
    }
}

$('body').on('click', '#optionClick', function() {
    appendOption();
});

$('body').on('click', '.removeOption', function() {
    $(this).closest('.qoption').remove();
    optionCounter--; // Decrement the option counter when an option is removed
    $('#optionClick').attr('disabled', false); // Re-enable the button when an option is removed
});
        function getOptionData(yearId = null, subjectId = null, moduleId = null){
            
            let fd = new FormData()
                fd.append('year_id', yearId)
                fd.append('subject_id', subjectId)
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

       /* $('#teacher_id').on('change', function(){

            let yearID = $('#year_id').val()
            let subjectID = $('#subject_id').val()
            let teacherID = $(this).val()


            getOptionData(yearID,subjectID,teacherID)

        })*/
                $('#module_id').on('change', function(){

            let yearID = $('#year_id').val()
            let subjectID = $('#subject_id').val()
          //  let teacherID = $('#teacher_id').val()
            let moduleID = $(this).val()


            getOptionData(yearID,subjectID,moduleID)

        })

        
       

   })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/question_bank/index.blade.php ENDPATH**/ ?>