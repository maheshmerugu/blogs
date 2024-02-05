<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
             <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.contact.list.view')); ?>"> ContactUs list</a>/ <?php echo e($user->name); ?> Queries</h3>
            </div>
           
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">

                    </div>
                   

                </div>
                <div class="row mt-3 ">
                    <div class="col-sm-4"></div>
                 
                    <div class="col-sm-3"></div>

                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped contact-list">
                        <thead>
                            <tr>
                                <th>Sr.No </th>
                                <th>User name</th>
                                <th>Title </th>
                                <th>Description </th>
                               
                            </tr>
                        </thead>
                        <tbody>

                                    <?php $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>

                                        <td><?php echo e($data->id); ?></td>
                                        <td><?php echo e($data->name); ?></td>
                                        <td><?php echo e($data->title); ?></td>
                                        <td><?php echo e($data->description); ?></td>
                                    </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  
                        </tbody>
                    </table>
                </div>

                <div class="pull-left" id="meta_data"></div>
                <div class="btn-group pull-right" id="pagination">
                    
                </div>
                <br><br>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/contactUs/logs_of_users.blade.php ENDPATH**/ ?>