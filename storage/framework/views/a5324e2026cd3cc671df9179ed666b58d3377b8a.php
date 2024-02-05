<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Auricle</title>
    <?php echo $__env->make('admin.layouts.partials.css', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('css'); ?>

</head>

<body>
    
    <div id="wrapper">
        <?php echo $__env->make('admin.layouts.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
       
        <?php echo $__env->make('admin.layouts.partials.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <?php echo $__env->yieldContent('content'); ?>

        <?php echo $__env->make('admin.layouts.partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        

        <?php echo $__env->yieldContent('modal'); ?>
    </div>

    <?php echo $__env->make('admin.layouts.partials.js', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldContent('js'); ?>
    <div class="loader" style="display:none;">
    
</body>

</html>
<script type="text/javascript">
            /* document.onkeydown = (e) => {
                if (e.keyCode === 123) {
                    e.preventDefault();
                }
                if (e.ctrlKey && e.shiftKey && e.key === 'I') {
                    e.preventDefault();
                }
                if (e.ctrlKey && e.shiftKey && e.key === 'C') {
                    e.preventDefault();
                }
                if (e.ctrlKey && e.shiftKey && e.key === 'J') {
                    e.preventDefault();
                }
                if (e.ctrlKey && e.key === 'U') {
                    e.preventDefault();
                }
            };*/
        document.onkeydown = (e) => {
                // Check for F12 key (Chrome, Edge, Opera)
                if (e.keyCode === 123) {
                    e.preventDefault();
                }
                
                // Check for Ctrl+Shift+I (Chrome, Edge, Opera) and Ctrl+Shift+J (Firefox)
                if ((e.ctrlKey && e.shiftKey && e.key === 'I') || (e.ctrlKey && e.shiftKey && e.key === 'J')) {
                    e.preventDefault();
                }
            
                // Check for Ctrl+Shift+C (Chrome, Edge, Opera) and Ctrl+Shift+K (Firefox)
                if ((e.ctrlKey && e.shiftKey && e.key === 'C') || (e.ctrlKey && e.shiftKey && e.key === 'K')) {
                    e.preventDefault();
                }
            
                // Check for Ctrl+U (Chrome, Edge, Opera) and Ctrl+U (Firefox)
                if (e.ctrlKey && e.key === 'U') {
                    e.preventDefault();
                }
            };

            $(document).on("contextmenu",function(e){        
               e.preventDefault();
            });
     $('.summernote').summernote(
        {
          toolbar: [
            // [groupName, [list of button]]
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']]
          ]
        }
        );  
</script><?php /**PATH /home/e42hkfu2q4wn/public_html/auricle2.0/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>