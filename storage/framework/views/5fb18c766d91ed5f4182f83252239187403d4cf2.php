<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
           <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.mcq.list.view')); ?>"> Mcq list </a>/ Add mcq</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <h4>Create Daily MCQ</h4>
                                    <form action="#" id="create-daily-mcq">
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
                                                <select class="form-control" name="subject" id="subject_id" >
                                                    <option value="">--Select Subject--</option>
                                                </select>
                                            </div>
                                            <!--  <div class="col-md-3">
                                                <label>Teacher</label>
                                                <select class="form-control" name="teacher" id="teacher_id" >
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
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Schedule date</label>
                                                    <input type="date" name="date" id="mcqDate"  class="form-control" >
                                                </div>
                                                <?php echo csrf_field(); ?>
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Question</label>
                                                    <textarea class="summernote" name="question" rows="5" ></textarea>
                                                    <input type="file" name="question_image" >

                                                </div>
                                            </div>
                                            
                                         <button class="btn btn-info float-right" type="button" id="optionClick"><i class="fa fa-plus"></i> Add More Option</button>
                                                <br>
                                                <div class="col-md-12">
    <div class="row mt-3" id="optionAppend">
        <div class="col-md-6 qoption">
            <input type="radio" value="1" name="answer" class="float-right" required>
            <div class="form-group">
                <label class="font-weight-bold">Option</label>
                <!-- Add the 'summernote' class to the textarea -->
                <textarea class="summernote" name="option[]" id="summernoteTextarea" rows="3"></textarea>
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
                                                <button class="btn btn-md btn-primary submitButton" type="submit" id="btn-create">Submit</button>
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
        
       
        $('#create-daily-mcq').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)
              $(".loader").show(); 
          $('.submitButton').prop('disabled', true);
            $.ajax({
                    url: "<?php echo e(route('admin.mcq.create')); ?>",
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
                                window.location.href = "<?php echo e(route('admin.mcq.list.view')); ?>"
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

      function appendOption() {
        var optionCount = $('.qoption').length;

        if (optionCount < 5) {
            var optionID = optionCount + 1;

            var optionHTML = `
            <div class="col-md-6 qoption">
                <input type="radio" value="${optionID}" name="answer" class="float-right">
                <div class="form-group">
                    <label class="font-weight-bold">Option</label>
                    <textarea class="summernote" name="option[]" rows="3"></textarea>
                    <input type="file" name="option_image[]">
                </div>
                <button type="button" class="btn btn-danger removeOption">Remove</button>
            </div>`;

            $('#optionAppend').append(optionHTML);
            $('.summernote').summernote();
        }/* else {
            alert('Maximum 5 options allowed.');
        }*/
    }

    $('body').on('click', '#optionClick', function() {
        appendOption();
    });

    $('body').on('click', '.removeOption', function() {
        $(this).closest('.qoption').remove();
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

       // Function to disable previous dates in the date picker
function setMinDate() {
    var today = new Date().toISOString().split('T')[0];
    $('#mcqDate').attr('min', today);
}

// Call the setMinDate function when the page loads
$(document).ready(function() {
    setMinDate();
});
       

   })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/mcq/index.blade.php ENDPATH**/ ?>