<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    
    <link rel="shortcut icon" href="<?php echo e(asset('icon.png')); ?>" />

    <!-- CSRF Token -->
    

    <title>Auricle | Admin</title>

    <link href="<?php echo e(asset('admin/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/font-awesome/css/font-awesome.css')); ?>" rel="stylesheet">
    <!-- Styles -->
    <link href="<?php echo e(asset('admin/css/animate.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/css/style.css')); ?>" rel="stylesheet">

</head>

<body class="gray-bg">
    <div id="app">      
        <div class="middle-box text-center loginscreen animated fadeInDown">
            <div class="login_section">
                <div style="margin-top: 100px">
                    <img src="<?php echo e(asset('website')); ?>/images/auricle_logo.png" style="max-width: 300px; max-hight: 400px; " />
                </div>
                <!-- <h3> <?php echo e(config('app.name', 'Motion')); ?></h3> -->
                <p>Login in. To see it in action.</p>

                <form class="m-t" role="form" method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <input id="email" type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e(old('email')); ?>" placeholder="Username" required  autocomplete="email" autofocus>

                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-danger" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <input id="password" type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" placeholder="Password" required autocomplete="current-password">
                        <a href="javascript:;" onclick="showHide()" class="show-hide">
                            <i id="show_hide_icon" class="fa fa-eye-slash"></i>
                        </a>
                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="text-danger" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    

                    <?php $__errorArgs = ['error'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <p class="text-danger" role="alert">
                            <strong><?php echo e($message); ?></strong>
                        </p>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    <button type="submit" class="btn btn-primary block full-width m-b"><?php echo e(__('Login')); ?></button>

                    <?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('admin.reset.password')); ?>">
                            <small><?php echo e(__('Forgot Your Password?')); ?></small>
                        </a>
                    <?php endif; ?>
                </form>
                <!-- <p class="m-t"> <small><?php echo e(config('app.name', 'Motion')); ?> &copy; 2022-23</small> </p> -->
            </div>
        </div>
    </div>

    <script>
        function showHide()
        {
            let e = document.getElementById('show_hide_icon');
            if(document.getElementById('password').getAttribute('type') == 'password')
            {
                document.getElementById('password').setAttribute('type', 'text');
                e.classList.remove('fa-eye-slash');
                e.classList.add('fa-eye');
            }
            else
            {
                document.getElementById('password').setAttribute('type', 'password');
                e.classList.remove('fa-eye');
                e.classList.add('fa-eye-slash');
            }
        }
    </script>

</body>
</html>
<?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/auth/login.blade.php ENDPATH**/ ?>