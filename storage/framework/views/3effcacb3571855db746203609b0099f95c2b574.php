<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Contact us</h3>
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
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody id="table_data">

                            
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
<script>

    $(document).ready(function(){
            $('.contact-list').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.contact.list')); ?>",
                        dataFilter: function (data) {
                            var json = jQuery.parseJSON(data);


                            json.recordsTotal = json.recordsTotal;
                            json.recordsFiltered = json.recordsFiltered;
                            json.data = json.data;
                            return JSON.stringify(json); // return JSON string
                        }
                    },
                    'columnDefs': [
                        {'targets': 0, 'searchable': false, 'orderable': false,},
                        {"targets": 1, "name": "name", 'searchable': true, 'orderable': true},
                        {"targets": 2, "name": "title", 'searchable': false, 'orderable': true},
                        {"targets": 3, "name": "description", 'searchable': false, 'orderable': true},
                        {"targets": 4, 'searchable': false, 'orderable': false},
                       
                       
                    ],
                    'order': [[1, 'desc']],
            });
        });

    
   

       

   
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/contactUs/list.blade.php ENDPATH**/ ?>