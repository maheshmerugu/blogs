<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
           <div class="ibox-title">
               <h3><a href="<?php echo e(route('admin.cms.list.view')); ?>"> Content management </a>/ Update cms</h3> 
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <h4>Update Cms</h4>
                    <form id="cms-submit">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                               <input type="hidden" name="id" value="<?php echo e($cms->id); ?>" required>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo e($cms->title); ?>" required>
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">Description</label>
                                    <textarea name="description" class="summernote" id="description" rows="10"><?php echo e($cms->description); ?></textarea>
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

        $('#cms-submit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)
              $(".loader").show(); 

            $.ajax({
                    url: "<?php echo e(route('admin.cms.update')); ?>",
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
                                
                                window.location.href = "<?php echo e(route('admin.cms.list.view')); ?>"

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/content-management/edit.blade.php ENDPATH**/ ?>