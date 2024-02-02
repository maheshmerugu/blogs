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
            <!-- <li class="<?php echo e(Request::is('admind/*')?'active':''); ?>">
                <a href="<?php echo e(url('/')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span></a>
            </li> -->

            <li class="<?php echo e(Request::is('admin/dashboard*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard </span></a>

            </li>
            <li class="<?php echo e(Request::is('admin/mcq/lists*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.mcq.list.view')); ?>"><i class="fa fa-question-circle"></i> <span class="nav-label">Daily MCQ</span>
                </a>
            </li> 
              <li class="<?php echo e(Request::is('admin/categories*')?'active':''); ?>">
                <a href="#"><i class="fa fa-solid fa-list"></i> <span class="nav-label">Categories</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">
                    <li class="<?php echo e(Request::is('admin/categories/year*')?'active':''); ?>"><a href="<?php echo e(route('admin.category.year')); ?>"><span class="nav-label">Year</span></a></li>
                    <li class="<?php echo e(Request::is('admin/categories/subject*')?'active':''); ?>"><a href="<?php echo e(route('admin.category.subject')); ?>"><span class="nav-label">Subject</span></a></li>
                    <li class="<?php echo e(Request::is('admin/categories/teacher*')?'active':''); ?>"><a href="<?php echo e(route('admin.category.teacher')); ?>"><span class="nav-label">Teacher</span></a></li>
                    <li class="<?php echo e(Request::is('admin/categories/module*')?'active':''); ?>"><a href="<?php echo e(route('admin.category.module')); ?>"><span class="nav-label">Module</span></a></li>
                    <li class="<?php echo e(Request::is('admin/categories/topic*')?'active':''); ?>"><a href="<?php echo e(route('admin.category.topic')); ?>"><span class="nav-label">Topic</span></a></li>
                </ul>
            </li>
             <li class="<?php echo e(Request::is('admin/cms*')?'active':''); ?>">
                <a href="#"><i class="fa fa-solid fa-list"></i> <span class="nav-label">CMS</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">
                    <li class="<?php echo e(Request::is('admin/contacts/lists*')?'active':''); ?>"><a href="<?php echo e(route('admin.contact.list.view')); ?>"><span class="nav-label">Contact Us</span></a></li>
                    <li class="<?php echo e(Request::is('admin/cms/lists*')?'active':''); ?>"><a href="<?php echo e(route('admin.cms.list.view')); ?>"><span class="nav-label">Content Management</span></a></li>
                    <li class="<?php echo e(Request::is('admin/faq/lists*')?'active':''); ?>"><a href="<?php echo e(route('admin.faq.list.view')); ?>"><span class="nav-label">Faq Management</span></a></li>
                   
                   
                </ul>
            </li>
             <li class="<?php echo e(Request::is('admin/news*')?'active':''); ?>">
                <a href="#"><i class="fa fa-newspaper-o"></i> <span class="nav-label">News</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">
                    <li class="<?php echo e(Request::is('admin/app-news*')?'active':''); ?>"><a href="<?php echo e(route('admin.appNews')); ?>"><span class="nav-label">App News</span></a></li>
                    <li class="<?php echo e(Request::is('admin/media-news*')?'active':''); ?>"><a href="<?php echo e(route('admin.media.news.list.view')); ?>"><span class="nav-label">Media News</span></a></li>
               
                   
                </ul>
            </li>

            <li class="<?php echo e(Request::is('admin/coupons*') ? 'active' : ''); ?>">
                <a href="#"><i class="fa fa-tags"></i> <span class="nav-label">Coupons</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">
                    <li class="<?php echo e(Request::is('admin/coupons') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.coupons.index')); ?>">
                            <span class="nav-label">View Coupons</span>
                        </a>
                    </li>
                    <li class="<?php echo e(Request::is('admin/coupons/create') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.create')); ?>">
                            <span class="nav-label">Create Coupon</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            
            <li class="<?php echo e(Request::is('admin/zoom*') ? 'active' : ''); ?>">
                <a href="#"><i class="fa fa-video-camera"></i> <span class="nav-label">Zoom</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">
                    
                    <li class="<?php echo e(Request::is('admin/zoom/create') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.zoom.create')); ?>">
                            <span class="nav-label">Schedule Zoom Meeting</span>
                        </a>
                    </li>
                    <li class="<?php echo e(Request::is('admin/zoom') ? 'active' : ''); ?>">
                        <a href="<?php echo e(route('admin.zoom.index')); ?>">
                            <span class="nav-label">View Zoom Meetings</span>
                        </a>
                    </li>
                </ul>
            </li>

             <li class="<?php echo e(Request::is('admin/plan-subscriptions/lists*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.plans.list.view')); ?>"><i class="fa fa-question-circle"></i> <span class="nav-label">Plan Subscription</span>
                </a>
             </li> 
             <li class="<?php echo e(Request::is('admin/user-management*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.user.management')); ?>"><i class="fa fa-solid fa-user"></i> <span class="nav-label">User Management</span>
                </a>
            </li>
           
          
            
            
          

           <!--  <li class="<?php echo e(Request::is('admin/mcq*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Daily MCQ</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">

                      <li class="<?php echo e(Request::is('admin/mcq/create-mcq*')?'active':''); ?>"><a href="<?php echo e(route('admin.mcq.add')); ?>"><span class="nav-label">Create Daily MCQ</span></a></li>
                  <li class="<?php echo e(Request::is('admin/mcq/lists*')?'active':''); ?>"><a href="<?php echo e(route('admin.mcq.list.view')); ?>"><span class="nav-label">Daily MCQ Lists</span></a></li>
                </ul>
            </li>   -->  

           
            <li class="<?php echo e(Request::is('admin/video/lists*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.video.list.view')); ?>"><i class="fa fa-th-large"></i> <span class="nav-label">Videos</span></a>
                   
            </li>
            
           
              <li class="<?php echo e(Request::is('admin/qb/lists*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.qb.list.view')); ?>"><i class="fa fa-question"></i> <span class="nav-label">Question Bank</span>
                </a>
             </li> 
              <li class="<?php echo e(Request::is('admin/custom_qb/lists*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.custom_qb.list.view')); ?>"><i class="fa fa-bars"></i> <span class="nav-label">Custom Question Bank</span>
                </a>
             </li> 
               <li class="<?php echo e(Request::is('admin/custom_qb/lists*')?'active':''); ?>">
                <a href="<?php echo e(route('admin.schedule_classes.list.view')); ?>"><i class="fa fa-clock-o"></i> <span class="nav-label">Schedule Classes</span>
                </a>
             </li> 
             
             
             

         <!--    <li class="<?php echo e(Request::is('admin/answer-key-solution*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Answer Key Solution</span>
                </a>
            </li> -->
            <!-- <li class="<?php echo e(Request::is('admin/employee_mgmt*')?'active':''); ?>">
                <a href=""><i class="fa fa-database"></i> <span class="nav-label">Answer Key Details Page</span>
                    <span class="fa arrow"></span></a>
            </li> -->
           <!--  <li class="<?php echo e(Request::is('admin/online-offline-management*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Online & Offline Page</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">

                    <li class="<?php echo e(Request::is('admin/online-offline-management/Banner-Management*')?'active':''); ?>"><a href="#"><span class="nav-label">Banner Management</span></a></li>
                    <li class="<?php echo e(Request::is('admin/online-offline-management/Target-Courses*')?'active':''); ?>"><a href="#"><span class="nav-label">Target Courses</span></a></li>
                </ul>
            </li>
            <li class="<?php echo e(Request::is('admin/career*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Career</span>
                </a>

            </li>
            <li class="<?php echo e(Request::is('admin/enquiry*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Enquiry</span>
                    <span class="fa arrow"></span></a>
            </li>
            <li class="<?php echo e(Request::is('admin/cms*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">CMS Management</span>
                </a>

            </li> -->
            
            
           
          <!--   <li class="<?php echo e(Request::is('admin/payment-management*')?'active':''); ?>">
                <a href="#"><i class="fa fa-database"></i> <span class="nav-label">Payment Management</span>
                </a>
            </li> -->

            <li class="<?php echo e(Request::is('admin/contacts*')?'active':''); ?>">
                <a href="#"><i class="fa fa-gear"></i> <span class="nav-label">Settings</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level collapse active">
                    <li class="<?php echo e(Request::is('admin/contacts/get-contact-details*')?'active':''); ?>"><a href="<?php echo e(route('admin.app.contact.details')); ?>"><span class="nav-label">App Contact Details</span></a></li>
                    <li class="<?php echo e(Request::is('admin/contacts/get-razorpay-key*')?'active':''); ?>"><a href="<?php echo e(route('admin.app.razorpay.key')); ?>"><span class="nav-label">App Razorpay Key</span></a></li>
                    <li class="<?php echo e(Request::is('admin/contacts/get-college-list*')?'active':''); ?>"><a href="<?php echo e(route('admin.college.list')); ?>"><span class="nav-label">College List</span></a></li>
                </ul>
            </li>
        </ul>

    </div>
</nav><?php /**PATH /home/e42hkfu2q4wn/public_html/dev.auricle.co.in/resources/views/admin/layouts/partials/sidebar.blade.php ENDPATH**/ ?>