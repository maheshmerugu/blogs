<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
            <div class="ibox-title">
               <h3><a>Settings </a>/ Contact Details</h3> 
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <h4>Contact Details</h4>
                    <form method="post" action="<?php echo e(route('admin.app.contact.details.create')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">App Email</label>
                                    <input type="text" name="email" class="form-control" value="<?php echo e($data->email); ?>" required>
                                </div>
                            </div>
                            <input type="hidden" name="id" class="form-control" value="<?php echo e($data->id); ?>" required>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">App Phone </label>
                                    <input type="text" name="phone" class="form-control" value="<?php echo e($data->phone); ?>" required>
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/app/contact_details.blade.php ENDPATH**/ ?>