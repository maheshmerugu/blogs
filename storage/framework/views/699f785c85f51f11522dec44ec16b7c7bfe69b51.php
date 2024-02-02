<?php $__env->startSection('css'); ?>
    
 <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>App News</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">

                    </div>
                    <div class=" col-md-2 float-right">
                        <a href="<?php echo e(route('admin.addNews')); ?>"> <button type="button" class="btn  btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add App News
                            </button></a>
                    </div>

                </div>
                <div class="row mt-3 ">
                    <div class="col-sm-4"></div>
                 
                    <div class="col-sm-3"></div>

                </div>
                <div class="table-responsive mt-5">
                    <table class="table table-striped news-list">
                        <thead>
                            <tr>
                                <th>Sr.No </th>
                                <th>Title </th>
                              <!--   <th>Description </th> -->
                                <th>Image </th>
                                <th>Action</th>
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

 <!-- Delete Modal -->
 <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteModalLabel">Delete</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="deleteSubmit">
            <div id="errosDeleteMsg"></div>
            <div class="modal-body">
                <p>Are you sure, you want to delete this ?</p>
                <input type="hidden" name="news" id="news-id" required>
                <?php echo csrf_field(); ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Delete</button>
              </div>
        </form>
        
      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script>


    $(document).ready(function(){
            $('.news-list').DataTable({
                pageLength: 25,
                responsive: true,
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    
                ],
                "serverSide": true,
                    "ajax": {
                        url: "<?php echo e(route('admin.appNewsList')); ?>",
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
                        {"targets": 1, "name": "title", 'searchable': true, 'orderable': true},
                       
                        {"targets": 2, "name": "news_image", 'searchable': false, 'orderable': true},
                        {"targets": 3, "name": "news_image", 'searchable': false, 'orderable': true},
                       
                    ],
                    'order': [[1, 'desc']],
            });


            //Show Delete Modal on Click
        $('body').on('click', '.deleteNews', function(){

            $('#news-id').val($(this).attr('data-id'))
            $('#deleteModal').modal('show')

        })

        //SUbmit delete Modal 
        $('#deleteSubmit').on('submit', function(e){

            e.preventDefault()

            let fd = new FormData(this)

            $.ajax({
                    url: "<?php echo e(route('admin.news.delete')); ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: fd,
                    contentType: false,
                    processData: false,
                    success: function (data) {

                        if(data.status){
                            $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                            $('.news-list').DataTable().ajax.reload(null, false);

                            setTimeout(() => {
                                $('#errosDeleteMsg').html(``)
                                $('#deleteModal').modal('hide')

                            }, 1000);
                        }
                        else{

                            $('#errosDeleteMsg').html(`<div class="alert alert-danger">${data.message}</div>`)

                        }
                        
                    }
                });
        })
        });

    

   
    
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/app-news/index.blade.php ENDPATH**/ ?>