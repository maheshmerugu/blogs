

<?php $__env->startSection('css'); ?>
    <!-- Include any additional CSS stylesheets or links here -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3><a href="<?php echo e(route('admin.categories.list.view')); ?>">Categories List </a>/ Add Category</h3>
                </div>
                <div class="ibox">
                    <div class="ibox-content">
                        <h4>Create Category</h4>
                        <form id="category-submit">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Category Name</label>
                                        <input type="text" name="name" class="form-control" required>
                                    </div>
                                </div>
                                <!-- Add more form fields as needed -->

                                <div class="col-md-12 text-center">
                                    <button class="btn btn-primary" id="submitButton" type="submit">Submit</button>
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
    <!-- Include any additional scripts or links to JS files here -->
    <script>
        $(document).ready(function () {
            $('#category-submit').on('submit', function (e) {
                e.preventDefault();

                let fd = new FormData(this);
                $(".loader").show();
                $('#submitButton').prop('disabled', true);

                $.ajax({
                    url: "<?php echo e(route('admin.categories.create')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        $(".loader").hide();

                        if (data.status) {
                            $('#errosMsg').html(`<div class="alert alert-success">${data.message}</div>`);

                            setTimeout(() => {
                                window.location.href = "<?php echo e(route('admin.categories.list.view')); ?>";
                            }, 1000);
                        } else {
                            $('#errosMsg').html(`<div class="alert alert-danger">${data.message}</div>`);
                        }
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\blogs\resources\views/admin/categories/add.blade.php ENDPATH**/ ?>