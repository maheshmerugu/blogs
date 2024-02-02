

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h3>Edit Coupon Code</h3>
                </div>
                <div class="ibox-content">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger">
                            <ul>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.update', $coupon->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input type="text" name="username" class="form-control" placeholder="Enter username" value="<?php echo e($coupon->username); ?>">
                                </div>

                                <div class="form-group">
                                    <label for="phonenumber">Phone Number:</label>
                                    <input type="text" name="phonenumber" class="form-control" placeholder="Enter phone number" value="<?php echo e($coupon->phonenumber); ?>">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="code">Code:</label>
                                    <input type="text" name="code" class="form-control" value="<?php echo e($coupon->code); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="discount_type">Discount Type:</label>
                                    <select name="discount_type" class="form-control" required>
                                        <option value="fixed" <?php echo e($coupon->discount_type == 'fixed' ? 'selected' : ''); ?>>Fixed</option>
                                        <option value="percentage" <?php echo e($coupon->discount_type == 'percentage' ? 'selected' : ''); ?>>Percentage</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="discount">Discount:</label>
                                    <input type="number" name="discount" class="form-control" value="<?php echo e($coupon->discount); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="expiry_date">Expiry Date:</label>
                                    <input type="date" name="expiry_date" class="form-control" value="<?php echo e($coupon->expiry_date); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="status">Status:</label>
                                    <select name="status" class="form-control" required>
                                        <option value="1" <?php echo e($coupon->status == '1' ? 'selected' : ''); ?>>Enable</option>
                                        <option value="0" <?php echo e($coupon->status == '0' ? 'selected' : ''); ?>>Disable</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Coupon Code</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/coupons/edit.blade.php ENDPATH**/ ?>