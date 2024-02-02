<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
            <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.appNews')); ?>"> Appnews Management </a>/ Add a New News</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" action="<?php echo e(route('admin.storeNews')); ?>" id="myForm" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="form-group col-md-6">

                                <b><label for="">Title</label></b>
                                <div class=" form-group">
                                   
                                    <input type="text" name="title" class="form-control" required>
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
    
                            </div>
                            <div class="form-group col-md-6">

                                <b><label for="">Views Count</label></b>
                                <div class=" form-group">
                                   
                                    <input type="number" name="views" class="form-control" required>
                                </div>
                                <?php $__errorArgs = ['views'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="number-danger" role="alert">
                                    <strong id="error"><?php echo e($message); ?></strong>
                                </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
    
                            </div>
                            <div class="form-group col-md-6">
                                <b><label for="schedule_date">Schedule Date</label></b>
                                <div class="form-group">
                                    <input type="date" name="schedule_date" class="form-control" required>
                                </div>
                                <?php $__errorArgs = ['schedule_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="number-danger" role="alert">
                                        <strong id="error"><?php echo e($message); ?></strong>
                                    </p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group col-md-6">
    
                                <b><label for="">Image</label></b>
                                <div class=" form-group">
                                   <input type="file" name="news_image" accept="image/*" class="form-control" required>
                                    
                                </div>
                               
    
                            </div>
                            <div class="form-group col-md-12">
    
                                <b><label for="">Description</label></b>
                                <div class=" form-group">
                                   <textarea name="description" class="summernote" required></textarea>
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
                                <button class="btn btn-primary" type="submit">Add</button>
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
<script src="<?php echo e(asset('admin/dropify/js/dropify.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>

<script>
    $('#showOnAchievers').css('display', 'none');;

    $(document).ready(function() {

        $('.summernote').summernote();    

        // get_data()
        $('.dropify').dropify();

        // $("#myForm").validate({
        //     // in 'rules' user have to specify all the constraints for respective fields
        //     rules: {
        //         paper_type_2: "required",

        //     },

        // });



    });

    $('#banner_section').on('change', function() {
        if (this.value == 4) {
            $('#showOnAchievers').css('display', 'flex');
        } else {
            $('#showOnAchievers').css('display', 'none');
        }
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/app-news/add.blade.php ENDPATH**/ ?>