<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3><a href="<?php echo e(route('admin.media.news.list.view')); ?>"> News list </a>/ News Details</h3>
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <!-- <form method="post" action="" id="myForm" enctype="multipart/form-data"> -->
                        <?php echo csrf_field(); ?>

                        
                        <input type="hidden" value="<?php echo e($news->id); ?>" name="id">

                        <div class="transaction_listing">
                            <ul>
                                <li><strong>Image :</strong> 
                                <?php if($news->news_image): ?>
                                <p><img src="<?php echo e(asset('images/news/medianews/'.$news->news_image)); ?>" height="100px" width="100px"></p>
                                <?php endif; ?>
                                </li>
                                <li><strong>Title :</strong> <p><?php echo e($news->title); ?></p></li>
                                <li><strong>Description :</strong> <p><?php echo $news->description; ?></p></li>
                               <!--  <li><strong>Status :</strong> <p><?php echo e($news->status==1?'Active':'Inactive'); ?></p></li> -->

                              
                                
                               
                            </ul>
                        </div>
                        
                        
                        
                </div>
            </div>
        </div>
    </div>
</div>







<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/media-news/media-news-detail.blade.php ENDPATH**/ ?>