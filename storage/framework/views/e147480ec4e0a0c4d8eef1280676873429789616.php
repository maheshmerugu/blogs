<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.plans.list.view')); ?>"> Plan Subscription list </a>/ Plan Details</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <!-- <form method="post" action="" id="myForm" enctype="multipart/form-data"> -->
                        <?php echo csrf_field(); ?>

                        
                       
                        <div class="transaction_listing">
                            <ul>
                               
                                <li><strong>Plan name :</strong> <p><?php echo e($plan->plan_name); ?></p></li>
                                <li><strong>Months :</strong> <p><?php echo e($plan->months); ?></p></li>
                                <li><strong>Watch hours :</strong> <p><?php echo e($plan->watch_hours); ?></p></li>
                                <li><strong>Plan Type :</strong> <?php if($plan->plan_type == 1): ?><p>Video</p>
                                <?php else: ?>
                                <p>Notes</p></li>
                                <?php endif; ?>
                                 <li><strong>Access to video :</strong> <?php if($plan->access_to_video == 1): ?><p>Yes</p>
                                <?php else: ?>
                                <p>No</p></li>
                                <?php endif; ?>
                                <li><strong>Access to notes :</strong> <?php if($plan->access_to_notes == 1): ?><p>Yes</p>
                                <?php else: ?>
                                <p>No</p></li>
                                <?php endif; ?>
                                <li><strong>Access to question bank:</strong> <?php if($plan->access_to_question_bank == 1): ?><p>Yes</p>
                                <?php else: ?>
                                <p>No</p></li>
                                <?php endif; ?>
                                <li><strong>Amount :</strong> <p><?php echo e($plan->amount); ?></p></li>
                                <li><strong>Discount :</strong> <p><?php echo e($plan->discount); ?></p></li>
                                <li><strong>Payble amount :</strong> <p><?php echo e($plan->payble_amount); ?></p></li>
                            
                              
                                
                               
                            </ul>
                        </div>
                        
                        
                        
                </div>
            </div>
        </div>
    </div>
</div>







<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/plan-subscriptions/view.blade.php ENDPATH**/ ?>