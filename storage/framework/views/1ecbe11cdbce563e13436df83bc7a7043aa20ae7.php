<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('admin/dropify/css/dropify.min.css')); ?>">
<link href="<?php echo e(asset('admin')); ?>/css/plugins/summernote/summernote-bs4.css" rel="stylesheet">

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
 <script>
        // Function to enable/disable the submit button based on checkbox states
        function updateSubmitButton() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const submitButton = document.getElementById('submitButton');
            let atLeastOneChecked = false;

            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    atLeastOneChecked = true;
                }
            });

            submitButton.disabled = !atLeastOneChecked;
        }
    </script>
<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins anskey">
            <div class="ibox-title">
               <h3><a href="<?php echo e(route('admin.plans.list.view')); ?>">Plan Subscription List </a>/ Edit Plan Subscription </h3> 
            </div>
            <div class="ibox">
                <div class="ibox-content">
                    <h4>Edit Plan</h4>
                    <form id="plan-submit">
                        <?php echo csrf_field(); ?>
                        <div class="row">
                            <input type="hidden" name="id" value="<?php echo e($plan->id); ?>">
                               <div class="col-md-6">
                              <div class="form-group">
                           
                                    <label for="">Plan Type</label>
                                    <select name="plan_type" class="form-control" id="" required>
                                    <option value="">--Select plan type--</option>
                                  
                                    <option value="1" <?php echo e($plan->plan_type == 1  ? 'selected' : ''); ?>>Video</option>
                                    <option value="2" <?php echo e($plan->plan_type == 2  ? 'selected' : ''); ?>>Notes</option>
                                   
                                </select>
                                </div>
                                </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Plan Name</label>
                                    <input type="text" name="plan_name" class="form-control" value="<?php echo e($plan->plan_name); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Months</label>
                                    <input type="number" name="months" class="form-control" value="<?php echo e($plan->months); ?>" required>
                                </div>
                            </div>
                              <div class="col-md-6" >
                                <div class="form-group">
                                    <label for="">Access to video</label>
                                    <input type="checkbox" value="1" onclick="ShowHideDiv(this)" name="access_to_video" onchange="updateSubmitButton()" class="form-control" id="access_to_video" <?php if(isset($plan->access_to_video)): ?> <?php if(1 == $plan->access_to_video): ?> <?php echo e('checked == "checked"'); ?>  <?php endif; ?> <?php endif; ?> ></input>
                                </div>
                            </div>
                            <div class="col-md-6" style="display: <?php echo e($plan->access_to_video ? 'block' : 'none'); ?>"  id="watch_hours" >
                                <div class="form-group">
                                    <label for="">Watch Hour</label>
                                    <input name="watch_hours" value="<?php echo e($plan->watch_hours); ?>"  class="form-control"  ></input>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Access to notes</label>
                                    <input type="checkbox" value="1" name="access_to_notes" onchange="updateSubmitButton()" class="form-control" id="access_to_notes" <?php if(isset($plan->access_to_notes)): ?> <?php if(1 == $plan->access_to_notes): ?> <?php echo e('checked == "checked"'); ?>  <?php endif; ?> <?php endif; ?> ></input>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Access to question bank</label>
                                    <input type="checkbox" value="1" name="access_to_question_bank"onchange="updateSubmitButton()" class="form-control" id="access_to_question_bank" <?php if(isset($plan->access_to_question_bank)): ?> <?php if(1 == $plan->access_to_question_bank): ?> <?php echo e('checked == "checked"'); ?>  <?php endif; ?> <?php endif; ?> ></input>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Amount</label>
                                    <input type="number" name="amount" class="form-control" id="amount" value="<?php echo e($plan->amount); ?>" min="1" required></input>
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Discount (in percentage)</label>
                                    <input type="number" name="discount" class="form-control" id="discount" value="<?php echo e($plan->discount); ?>"></input>
                                </div>
                            </div>  <div class="col-md-6 result" style="display:none;">
                                <div class="form-group">
                                    <label for="">Payble amount</label>
                                    <span id="percentage-result"></span>
                                </div>
                            </div>
                            
<input type="hidden" name="payble_amount" id="payble_amount" value="<?php echo e($plan->payble_amount); ?>">
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
<script src="<?php echo e(asset('admin')); ?>/js/plugins/summernote/summernote-bs4.js"></script>
<script>
   $("#discount, #amount").keyup(function() {
                var amount = parseFloat($("#amount").val());
                var discount = parseFloat($("#discount").val());
                if (!isNaN(amount) && !isNaN(discount)) {
                    var percentage = amount - (amount * discount) / 100;
                    percentage = Math.max(percentage, 0); // Ensure it's not negative
                    if (discount > amount) {
                        percentage = 0; // Set payable amount to 0 if discount exceeds amount
                    }
                    $("#percentage-result").text('Rs ' + Math.round(percentage));
                    $("#payble_amount").val(Math.round(percentage));
                    $(".result").show();
                } else {
                    $(".result").hide();
                }
            });
           
</script>
<script>

        function ShowHideDiv(access_to_video) {
        var watch_hours = document.getElementById("watch_hours");

       
        watch_hours.style.display = access_to_video.checked ? "block" : "none";

    }
    $(document).ready(function(){
         updateSubmitButton();
        $('.summernote').summernote();    

        $('#plan-submit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)
              $(".loader").show(); 

            $.ajax({
                    url: "<?php echo e(route('admin.plan.update')); ?>",
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
                                
                                window.location.href = "<?php echo e(route('admin.plans.list.view')); ?>"

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
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/plan-subscriptions/edit.blade.php ENDPATH**/ ?>