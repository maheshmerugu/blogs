<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.user.management')); ?>"> User Management </a>/ User Details</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <div id="errosMsg"></div>
                    <h3>Update User Details</h3>
                    <div class="row">
                        
                        <div class="col-md-8 offset-md-2">
                            <div class="card">
                                <div class="card-body">
                                    <form action="#" id="update-user">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Name</label>
                                                    <input type="text" placeholder="Enter Name" name="name" value="<?php echo e($userData->name); ?>" class="form-control" required>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="text" placeholder="Enter Mobile Number" name="mobile_number" value="<?php echo e($userData->mobile_number); ?>" class="form-control" required disabled>
                                                </div>
                                            </div>
                                           <!-- <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Mobile Number</label>
                                                    <input type="number" onKeyPress="if(this.value.length==16) return false;" placeholder="Enter Mobile Number"  value="<?php echo e($userData->mobile_number); ?>" name="mobile_number" class="form-control" required>
                                                </div>
                                            </div>-->

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <input type="email" placeholder="Enter Email"  value="<?php echo e($userData->email); ?>" name="email" class="form-control" disabled required>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>College</label>
                                                    <select name="college" class="form-control" name="college" required>
                                                        <option value="">---Select College---</option>
                                                       <?php $__currentLoopData = $college; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $userData->college_id ? "selected" : ""); ?>><?php echo e($item->college_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <select name="year" class="form-control" name="year" required>
                                                    <option value="">---Select Year---</option>
                                                   <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($item->id); ?>" <?php echo e($item->id == $userData->year_id ? "selected" : ""); ?>><?php echo e($item->year_name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="user_id" value="<?php echo e($userData->id); ?>">
                                            
                                        </div>
                                       
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-sm btn-primary float-right m-t-n-xs" type="submit"><strong>Update</strong></button>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                    
                        </div>
                
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>







<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
    <script>
        $(document).ready(function(){

            $('#update-user').on('submit', function(e){

                e.preventDefault()

                let fd = new FormData(this)
              $(".loader").show(); 

                $.ajax({
                        url: "<?php echo e(route('admin.user.update')); ?>",
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
                                
                                window.location.href = "<?php echo e(route('admin.user.management')); ?>"

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/user-management/edit-user.blade.php ENDPATH**/ ?>