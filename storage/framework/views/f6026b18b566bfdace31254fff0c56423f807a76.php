<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
            <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.appNews')); ?>"> Appnews Management </a>/ Edit App News</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <div id="errosMsg"></div>
                    <form id="app-news-submit">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="form-group col-md-6">

                                <b><label for="">Title</label></b>
                                <div class=" form-group">
                                   
                                    <input type="text" name="title" value="<?php echo e($news->title); ?>" class="form-control" required>
                                </div>
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-danger" role="alert">
                                    <strong id="error"><?php echo e($message); ?></strong>
                                </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    
                            </div><div class="form-group col-md-6">
    
                                <b><label for="">Image</label></b>
                                 <br>
                                     <img src="<?php echo e(asset('images/news/appnews/'.$news->news_image)); ?>" height="100px" width="100px" class="img-fluid">
                                <div class=" form-group">
                                   <input type="file" name="news_image" accept="image/*" class="form-control">
                                   <input type="hidden" name="id" value="<?php echo e($news->id); ?>" required>
                                    
                                </div>
                                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-danger" role="alert">
                                    <strong id="error"><?php echo e($message); ?></strong>
                                </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    
                            </div>
                            <div class="form-group col-md-12">
    
                                <b><label for="">Description</label></b>
                                <div class=" form-group">
                                   <textarea name="description" class="summernote" required><?php echo e($news->description); ?></textarea>
                                </div>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="text-danger" role="alert">
                                    <strong id="error"><?php echo e($message); ?></strong>
                                </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    
                            </div>

                            <div class="col-sm-12 text-center">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
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
    $('#showOnAchievers').css('display', 'none');;

    $(document).ready(function() {

        $('.summernote').summernote();    

          $('#app-news-submit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.app.news.update')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            
                            $('#errosMsg').html(`<div class="alert alert-success">${data.message}</div>`)

                            setTimeout(() => {
                                
                                window.location.href = "<?php echo e(route('admin.appNews')); ?>"

                            }, 1000);
                        }
                        else{

                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });

        })



    });

  
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/app-news/edit.blade.php ENDPATH**/ ?>