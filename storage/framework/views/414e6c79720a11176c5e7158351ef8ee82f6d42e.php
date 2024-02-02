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
                    <!-- <form method="post" action="" id="myForm" enctype="multipart/form-data"> -->
                        <?php echo csrf_field(); ?>

                        
                        <input type="hidden" value="<?php echo e($userDetails->id); ?>" name="id">

                        <div class="transaction_listing">
                            <ul>
                                <li><strong>Name :</strong> <p><?php echo e($userDetails->name); ?></p></li>
                                <li><strong>Mobile No. :</strong> <p><?php echo e($userDetails->mobile_number); ?></p></li>
                                <li><strong>Email :</strong> <p><?php echo e($userDetails->email); ?></p></li>
                                <li><strong>Status :</strong> <p><?php echo e($userDetails->status==1?'Active':'Inactive'); ?></p></li>

                                <li><strong>College :</strong> <p><?php echo e($college->college_name); ?></p></li>
                                  <li><strong>Year :</strong> <p><?php echo e($years->year_name); ?></p></li>
                                  <li><strong>Streak Count :</strong> <p><?php echo e($streakCount); ?></p></li>
                                
                               
                            </ul>
                        </div>
                        
                        
                        
                </div>
            </div>


            <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Video Watch Hours </h3>
            </div>
            <div class="ibox-content">
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Topic</th>
                                <th>Watch time</th>
                                
                            
                               
                            </tr>
                        </thead>
                        <tbody id="table_data">
                            <?php if($video_used_seconds->count()>0): ?>
                           
                          <?php
                            $rowNumber = 1;
                        ?>
                        
                        <?php $__currentLoopData = $video_used_seconds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $videoTime): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            
                             <?php if($videoTime->video && !$videoTime->video->isEmpty() && $videoTime->video->first()->topic && $videoTime->video->first()->topic->topic !== ''): ?>
                             
                                  <tr>
                                    <td><?php echo e($rowNumber); ?></td>
                                    <td>
                                        <?php $__currentLoopData = $videoTime->video; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($video->topic->topic); ?>

                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                  
                                    <td><?php if(($videoTime->watch_time)): ?>
                                    
                                    <?php
                                        $totalSeconds = collect($videoTime->watch_time)->sum();
                                        $hours = floor($totalSeconds / 3600);
                                        $minutes = floor(($totalSeconds % 3600) / 60);
                                        $seconds = $totalSeconds % 60;
                                    ?>
                                 
                                    <?php echo e(sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds)); ?>

                                    
                                <?php else: ?>
                                
                                    <?php
                                        $totalSeconds = $videoTime->watch_time;
                                        $hours = floor($totalSeconds / 3600);
                                        $minutes = floor(($totalSeconds % 3600) / 60);
                                        $seconds = $totalSeconds % 60;
                                    ?>
                                
                                    <?php echo e(sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds)); ?>

                                <?php endif; ?></td>
                                </tr>
                                <?php
                                    $rowNumber++;
                                ?>
                                <?php endif; ?>
                        
                          
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                           <?php else: ?>

                           <tr><td colspan="3" style="text-align: center;">No data Found.</td></tr>
                           <?php endif; ?>
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

 <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Video Rating Feedback </h3>
            </div>
            <div class="ibox-content">
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Video Name</th>
                                <th>Rating</th>
                                <th>Feedback</th>
                                
                            
                               
                            </tr>
                        </thead>
                        <tbody id="table_data">
                            <?php if($videoRatingFeedback->count()>0): ?>
                             <?php
                                $count = 1;
                            ?>
                           <?php $__currentLoopData = $videoRatingFeedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $videoRating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $videoRating->video; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($video->topic && $video->topic->topic): ?>
                                        <tr>
                                            <td><?php echo e($count); ?></td>
                                            <td><?php echo e($video->topic->topic); ?></td>
                                            <td><?php echo e($videoRating->rating); ?></td>
                                            <td><?php echo e($videoRating->content); ?></td>
                                        </tr>
                                        <?php
                                            $count++;
                                        ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                           <?php else: ?>

                           <tr><td colspan="3" style="text-align: center;">No data Found.</td></tr>
                           <?php endif; ?>
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
 <div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Contact Us Feedback </h3>
            </div>
            <div class="ibox-content">
                <div class="table-responsive mt-5">
                    <table class="table table-striped users-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                               
                                
                            
                               
                            </tr>
                        </thead>
                        <tbody id="table_data">
                            <?php if($contactUsFeedback->count()>0): ?>
                             <?php
                                $count = 1;
                            ?>
                            <?php $__currentLoopData = $contactUsFeedback; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                           <tr>
                                <td><?php echo e($count); ?></td>
                               
                                    <td><?php echo e($feedback->title); ?></td>
                                    <td><?php echo e($feedback->description); ?></td>
                             
                           </tr>
                            <?php
                                $count++;
                            ?>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           <?php else: ?>

                           <tr><td colspan="3" style="text-align: center;">No data Found.</td></tr>
                           <?php endif; ?>
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
        </div>
    </div>
</div>







<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin/dropify/js/dropify.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script>
    $('#showOnAchievers').hide();

    $(document).ready(function() {
        // get_data()
        $('.dropify').dropify();

        $("#cuisineForm").validate({
            // in 'rules' user have to specify all the constraints for respective fields
            rules: {
                banner_type: "required",
            }

        });



    });

    $('#banner_section').on('change', function() {
        if (this.value == 2) {
            $('#showOnAchievers').show();
        } else {
            $('#showOnAchievers').hide();
        }
    });
</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/user-management/user-detail.blade.php ENDPATH**/ ?>