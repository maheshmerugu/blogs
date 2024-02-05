<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
            <div class="ibox-title">
               <h3><a href="<?php echo e(route('admin.faq.list.view')); ?>">Faq management </a>/ Add cms</h3> 
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <h4>Create Faq</h4>
                    <form id="faq-submit">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Qustion</label>
                                    <input type="text" name="question" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Answer</label>
                                    <textarea name="answer" class="summernote" id="answer" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12 text-center">
                                <button class="btn btn-primary" type="submit">Submit</button>
                            </div>
                            <div class="col-md-12" id="errosMsg"></div>
                        </div>
                        
                </form>

            </div>
        </div>

    </div>
</div>
</div>




<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>
<script>
    $(document).ready(function(){

        $('.summernote').summernote();    

        $('#faq-submit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)
        $(".loader").show(); 
            
            $.ajax({
                    url: "<?php echo e(route('admin.faq.create')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                     $(".loader").hide(); 
                        
                        if(data.status){
                            
                            $('#errosMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                                
                                window.location.href = "<?php echo e(route('admin.faq.list.view')); ?>"

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });

        })
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/faq/add.blade.php ENDPATH**/ ?>