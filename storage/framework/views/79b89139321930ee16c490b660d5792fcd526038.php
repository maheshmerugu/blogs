

<?php $__env->startSection('css'); ?>
    <!-- Add your CSS links or styles here -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Edit Category</h3>
                </div>
                <div class="ibox-content">
                    <form id="updateCategoryForm" data-id="<?php echo e($category->id); ?>" action="<?php echo e(route('admin.category.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($category->id); ?>">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Category Name</label>
                                    <input type="text" name="name" class="form-control" value="<?php echo e($category->name); ?>" required>
                                </div>
                            </div>
                            <!-- Add more fields as needed -->

                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" id="updateCategoryBtn">Update Category</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function () {
            // Update Category button click event
            $('#updateCategoryBtn').on('click', function () {
                var formData = $('#updateCategoryForm').serialize();

                $.ajax({
                    url: "<?php echo e(route('admin.category.update')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: formData,
                    success: function (data) {
                        if (data.status) {
                            // Update successful, redirect to the list view
                            window.location.href = "<?php echo e(route('admin.categories.list.view')); ?>";
                        } else {
                            // Update failed, you can handle error actions here
                            alert(data.message);
                        }
                    },
                    error: function (xhr, status, error) {
                        // Handle Ajax errors here
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <!-- Add your additional JS scripts or links here -->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\blogs\resources\views/admin/categories/edit.blade.php ENDPATH**/ ?>