<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" src="<?php echo e(asset('website')); ?>/images/auricle_logo.png" />
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="block m-t-xs font-bold"></span>
                        <!-- <span class="text-muted text-xs block">Admin <b class="caret"></b></span>
                    </a> -->
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="dropdown-item" href="profile.html">Profile</a></li>
                            <li><a class="dropdown-item" href="contacts.html">Contacts</a></li>
                            <li><a class="dropdown-item" href="mailbox.html">Mailbox</a></li>
                            <li class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="login.html">Logout</a></li>
                        </ul>
                </div>
                <div class="logo-element">
                    RM+ 
                </div>
            </li>
           

            <li class="<?php echo e(Request::is('admin/dashboard*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard </span></a>

            </li>

            
          
            <li class="<?php echo e(Request::is('admin/blogs/list*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.blogs.list.view')); ?>"><i class="fa fa-question-circle"></i> <span class="nav-label">Blogs</span>
                </a>
            </li> 

            <li class="<?php echo e(Request::is('admin/categories/list*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.categories.list.view')); ?>"><i class="fa fa-question-circle"></i> <span class="nav-label">Categories</span>
                </a>
            </li> 

        </ul>

    </div>
</nav><?php /**PATH C:\xampp\htdocs\blogs\resources\views/admin/layouts/partials/sidebar.blade.php ENDPATH**/ ?>