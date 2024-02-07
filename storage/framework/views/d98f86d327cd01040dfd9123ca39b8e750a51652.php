

<?php $__env->startSection('css'); ?>
    <!-- Include your CSS stylesheets here -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
    <link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">
    <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <?php echo \Livewire\Livewire::styles(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Blogs</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">
                        <!-- Add any additional content here -->
                    </div>
                    <div class="col-md-2 float-right">
                        <a href="<?php echo e(route('admin.blogs.add')); ?>">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Blog
                            </button>
                        </a>
                    </div>
                </div>
              <?php
if (! isset($_instance)) {
    $html = \Livewire\Livewire::mount('admin.blogs')->html();
} elseif ($_instance->childHasBeenRendered('tAKavK7')) {
    $componentId = $_instance->getRenderedChildComponentId('tAKavK7');
    $componentTag = $_instance->getRenderedChildComponentTagName('tAKavK7');
    $html = \Livewire\Livewire::dummyMount($componentId, $componentTag);
    $_instance->preserveRenderedChild('tAKavK7');
} else {
    $response = \Livewire\Livewire::mount('admin.blogs');
    $html = $response->html();
    $_instance->logRenderedChild('tAKavK7', $response->id(), \Livewire\Livewire::getRootElementTagName($html));
}
echo $html;
?>
            </div>
        </div>
    </div>
</div>

   
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<?php echo \Livewire\Livewire::scripts(); ?>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\blogs\resources\views/admin/blogs/blog_list.blade.php ENDPATH**/ ?>