
<?php $__env->startSection('css'); ?>
    
    <link href="<?php echo e(asset('admin')); ?>/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h3>Blogs</h3>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="col-md-10 float-left">

                    </div>
                    <div class="col-md-2 float-right">
                        <a href="<?php echo e(route('admin.blogs.create')); ?>">
                            <button type="button" class="btn btn-primary">
                                <i class="fa fa-plus mr-2"></i>Add Blog
                            </button>
                        </a>
                    </div>
                </div>
                <div class="row mt-3 ">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-3"></div>
                </div>
                <div class="table-responsive mt-5">
                    <table id="blog-list" class="table table-striped blog-list">
                        <thead>
                            <tr>
                                <th>Sr.No</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Categories</th>
                                <th>Tags</th>
                                <th>Images</th>
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
                    <p>Are you sure, you want to delete this?</p>
                    <input type="hidden" name="blog" id="blog-id" required>
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
<!-- Delete Modal End -->
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/datatables.min.js"></script>
<script src="<?php echo e(asset('admin')); ?>/js/plugins/dataTables/dataTables.bootstrap4.min.js"></script>
<script>
$(document).ready(function () {
    $('#blog-list').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [],
        "processing": true,
        "serverSide": true,
        "ajax": "<?php echo e(route('admin.blogs.get-list')); ?>",
        'columns': [
            { 'data': 'id', 'name': 'id', 'visible': false },
            { 'data': 'DT_RowIndex', 'name': 'DT_RowIndex', 'searchable': false, 'orderable': false, 'width': '3%' },
            { 'data': 'title', 'name': 'title', 'searchable': true, 'orderable': true },
            { 'data': 'description', 'name': 'description', 'searchable': false, 'orderable': true },
            {
                'data': 'categories',
                'name': 'categories',
                'render': function (data, type, row) {
                    return data.join(', '); // Join array elements with a comma
                },
                'searchable': false,
                'orderable': true
            },
            {
                'data': 'tags',
                'name': 'tags',
                'render': function (data, type, row) {
                    return data.join(', '); // Join array elements with a comma
                },
                'searchable': false,
                'orderable': true
            },
            {
                'data': 'images',
                'name': 'images',
                'render': function (data, type, row) {
                    // Display the first image in case there are multiple images
                    return data.length > 0 ? '<img src="' + data[0] + '" alt="Image" class="img-thumbnail" width="50">' : '';
                },
                'searchable': false,
                'orderable': true
            },
            { 'data': 'action', 'name': 'action', 'searchable': false, 'orderable': false },
        ],
        'order': [[1, 'desc']],
    });
});


// Show Delete Modal on Click
$('body').on('click', '.deleteBlog', function () {
    $('#blog-id').val($(this).attr('data-id'))
    $('#deleteModal').modal('show')
})

// Submit delete Modal 
$('#deleteSubmit').on('submit', function (e) {
    e.preventDefault()
    let fd = new FormData(this)
    $.ajax({
        url: "<?php echo e(route('admin.delete.blog')); ?>",
        type: 'POST',
        dataType: 'json',
        data: fd,
        contentType: false,
        processData: false,
        success: function (data) {
            if (data.status) {
                $('#errosDeleteMsg').html(`<div class="alert alert-success">${data.message}</div>`)
                $('.blog-list').DataTable().ajax.reload(null, false);
                setTimeout(() => {
                    $('#errosDeleteMsg').html(``)
                    $('#deleteModal').modal('hide')
                }, 1000);
            } else {
                $('#errosDeleteMsg').html(`<div class="alert alert-danger">${data.message}</div>`)
            }
        }
    });
})

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\blogs\resources\views/admin/blogs/blog_list.blade.php ENDPATH**/ ?>