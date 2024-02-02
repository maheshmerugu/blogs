<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element">
                    <img alt="image" src="{{ asset('website') }}/images/auricle_logo.png" />
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
           

            <li class="{{Request::is('admin/dashboard*')?'active':''}}">
                <a href="{{route('admin.dashboard')}}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard </span></a>

            </li>

            <li class="{{Request::is('admin/plan-subscriptions/lists*')?'active':''}}">
                <a href="{{ route('admin.plans.list.view') }}"><i class="fa fa-question-circle"></i> <span class="nav-label">plans</span>
                </a>
            </li> 
          
            <li class="{{Request::is('admin/blogs/list*')?'active':''}}">
                <a href="{{ route('admin.blogs.list.view') }}"><i class="fa fa-question-circle"></i> <span class="nav-label">Blogs</span>
                </a>
            </li> 

        </ul>

    </div>
</nav>