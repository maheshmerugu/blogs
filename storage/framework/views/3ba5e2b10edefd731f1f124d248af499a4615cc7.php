

<?php $__env->startSection('css'); ?>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/css/bootstrap2/bootstrap-switch.min.css">

    <style>
        /* Custom style to decrease the size of Bootstrap Switch */
        .bootstrap-switch-container {
            width: 32px; /* Adjust the width as needed */
        }

        .bootstrap-switch-label {
            font-size: 8px; /* Adjust the font size as needed */
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Coupon Codes</h3>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-10 float-left"></div>
                        <div class="col-md-2 float-right">
                            <a href="<?php echo e(route('admin.create')); ?>">
                                <button type="button" class="btn btn-primary">
                                    <i class="fa fa-plus mr-2"></i>Add Coupon Code
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="table-responsive mt-5">
                        <table class="table table-striped coupon-list">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Phone Number</th>
                                    <th>Code</th>
                                    <th>Discount Type</th>
                                    <th>Discount</th>
                                    <th>Expiry Date</th>
                                    <th>Usage Count</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $coupons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $coupon): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td><?php echo e($coupon->username); ?></td>
                                        <td><?php echo e($coupon->phonenumber); ?></td>
                                        <td><?php echo e($coupon->code); ?></td>
                                        <td><?php echo e($coupon->discount_type); ?></td>
                                        <td><?php echo e($coupon->discount); ?></td>
                                        <td><?php echo e($coupon->expiry_date); ?></td>
                                        <td><?php echo e($coupon->usage_count); ?></td>
                                        <td>
                                            <input type="checkbox" class="status-switch" data-id="<?php echo e($coupon->id); ?>" <?php echo e($coupon->status == '1' ? 'checked' : ''); ?>>
                                        </td>
                                        <td>
                                            
                                            <a href="<?php echo e(route('admin.coupons.edit', $coupon->id)); ?>">Edit</a>
                                            <a href="#" class="delete-coupon" data-toggle="modal" data-target="#deleteModal" data-couponid="<?php echo e($coupon->id); ?>">Delete</a>
                                            
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="10">No data available.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Coupon Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteSubmit" method="POST" action="<?php echo e(route('admin.coupons.delete')); ?>">
                    <?php echo method_field('DELETE'); ?>
                    <?php echo csrf_field(); ?>
                    <div id="errorsDeleteMsg"></div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this coupon code?</p>
                        <input type="hidden" name="coupon" id="coupon-id" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger" id="deleteCouponBtn">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.4/js/bootstrap-switch.min.js"></script>

    
    <script>
        $(document).ready(function () {
            // Initialize Bootstrap Switch
            $('.status-switch').bootstrapSwitch();

            // Handle switch state change
            $('.status-switch').on('switchChange.bootstrapSwitch', function (event, state) {
                var couponId = $(this).data('id');
                console.log('Coupon ID:', couponId); // Debug statement

                // Set the coupon ID in the modal form action
                var deleteForm = $('#deleteSubmit');
                deleteForm.attr('action', '<?php echo e(route("admin.coupons.delete")); ?>');

                // Set coupon ID in the hidden input
                $('#coupon-id').val(couponId);
            });

            // Handle click event for the "Delete" button in the modal
            $('#deleteCouponBtn').on('click', function () {
                $.ajax({
                    url: $('#deleteSubmit').attr('action'),
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: '<?php echo e(csrf_token()); ?>',
                    },
                    success: function (response) {
                        console.log(response);
                        // Handle success, if needed

                        // Close the modal
                        $('#deleteModal').modal('hide');
                        // Reload the page to reflect changes
                        location.reload();
                    },
                    error: function (error) {
                        console.error(error);
                        // Handle error, if needed
                    }
                });
            });
        });
    </script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/coupons/index.blade.php ENDPATH**/ ?>