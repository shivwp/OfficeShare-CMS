 <script type="text/javascript">
  function openNav() {
   //jQuery (".sidebar").css("width", "260px");
  jQuery (".nav-burger").css("display", "none");
  jQuery (".closebtn").addClass("active");
  jQuery (".nav-burger").removeClass("active");
  jQuery (".closebtn").css("display", "block");
   //jQuery (".sidebar").css("display", "block");
   jQuery (".sidebar").addClass("active");

}

function closeNav() {
   // var loadingBar = 0;
    // jQuery (".sidebar").css("width", "0px");
  //document.getElementsByClassName("sidebar").style.width = (loadingBar+"px");
  jQuery (".closebtn").css("display", "none");
  jQuery (".nav-burger").css("display", "block");
  jQuery (".closebtn").removeClass("active");
  jQuery (".nav-burger").addClass("active");
 //jQuery (".sidebar").css("visibility", "hidden");
 jQuery (".sidebar").removeClass("active");
}


</script> 
<!-- Sidebar -->
<div class="sidebar" data-background-color="black">

    <!-- Brand Logo -->
    <div class="logo">
        <a href="#" class="simple-text logo-normal" style="font-family: 'Libre Baskerville', serif;overflow-wrap: break-word;">
            <img src="{{asset('logo/logo_cms.png')}}" class="menu_logo">
        </a>
    </div>
    <!-- Sidebar Menu -->
    <div class="sidebar-wrapper">
        <div class="curtain">
            <ul class="nav" data-widget="treeview" role="menu" data-accordion="false">
             
                <li class="nav-item {{ request()->is('/dashboard') ? 'active' : '' }}">
                    <a href="{{ route("dashboard.home") }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <p>
                            <i class="fas fa-fw fa-tachometer-alt"> </i>
                            <span>{{ trans('global.dashboard') }}</span>
                        </p>
                    </a>
                </li>
                @can('product_setting')
                <li class="nav-item has-treeview {{ request()->is('dashboard/color*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/attribute*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/attribute-value*') ? 'menu-open active' : '' }}{{ request()->is('dashboard/product-style-customization*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/tax*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/selling-zone*') ? 'menu-open active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#product_setting">
                        <i class="fas fa-screwdriver"></i>
                        <p>
                            <span>Product Setting</span>
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->is('dashboard/color*') ? 'show' : '' }} {{ request()->is('dashboard/attribute*') ? 'show' : '' }} {{ request()->is('dashboard/attribute-value*') ? 'show' : '' }}{{ request()->is('dashboard/product-style-customization*') ? 'show' : '' }} {{ request()->is('dashboard/tax*') ? 'show' : '' }} {{ request()->is('dashboard/selling-zone*') ? 'show' : '' }}" id="product_setting">
                        <ul class="nav">
                            @can('color_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.color.index") }}" class="nav-link {{ request()->is('dashboard/color') || request()->is('dashboard/color/*') ? 'active' : '' }}">
                                    <i class="fas fa-palette"></i>
                                    <span>Color</span>
                                </a>
                            </li>
                            @endcan

                            @can('style_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.product-style-customization.index") }}" class="nav-link {{ request()->is('dashboard/product-style-customization') || request()->is('dashboard/product-style-customization/*') ? 'active' : '' }}">
                                    <i class="fas fa-tshirt"></i>
                                    <span> Style Customization </span>
                                </a>
                            </li>
                            @endcan
                            @can('tax_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.tax.index") }}" class="nav-link {{ request()->is('dashboard/tax') || request()->is('dashboard/tax/*') ? 'active' : '' }}">
                                    <i class="fas fa-industry"></i>
                                    <span>Tax Setting</span>
                                </a>
                            </li>
                            @endcan
                            @can('zone_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.selling-zone.index") }}" class="nav-link {{ request()->is('dashboard/selling-zone') || request()->is('dashboard/selling-zone/*') ? 'active' : '' }}">
                                    <i class="fab fa-shopware"></i>
                                    <span>Shipping Zone</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan

                @can('office_management')

                 <li class="nav-item">
                    <a href="{{ route("dashboard.office.index") }}" class="nav-link {{ request()->is('dashboard/office') 
                                    || request()->is('dashboard/office*') ? 'active' : '' }}">
                        <p>
                            <i class="fas fa-building"></i>
                            <span>Properties</span>
                        </p>
                    </a>
                </li>

                 @can('attr_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.attribute.index") }}" class="nav-link {{ request()->is('dashboard/attribute') || request()->is('dashboard/attribute/*') ? 'active' : '' }}">
                                    <p>
                                    {{-- <i class="fab fa-adn"></i> --}}
                                    <i class="fa fa-puzzle-piece"></i>
                                    <span>Attributes</span>
                                    </p>
                                </a>
                            </li>
                 @endcan

                 <li class="nav-item">
                    <a href="{{ route("dashboard.space.index") }}" class="nav-link {{ request()->is('dashboard/space') 
                                || request()->is('dashboard/space*') ? 'active' : '' }}">
                        <p>
                            {{-- <i class="fas fa-bed"></i> --}}
                            <i class="fas fa-building"></i>
                            {{-- <i class="fa fa-pie-chart" aria-hidden="true"></i> --}}
                            <span>Space</span>
                        </p>
                    </a>
                </li>

                

                @endcan

                @can('booking_management')

                 <li class="nav-item">
                    <a href="{{ route('dashboard.bookings.index') }}" class="nav-link {{ request()->is('dashboard/bookings') 
                                || request()->is('dashboard/bookings*') ? 'active' : '' }}">
                        <p>
                            <i class="fas fa-calendar-check"></i>
                            <span>Bookings</span>
                        </p>
                    </a>
                </li>

                @endcan

               @can('permission_create')
                <li class="nav-item has-treeview {{ request()->is('dashboard/plan-manage*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/plan*') ? 'menu-open active' : '' }} ">
                    <a class="nav-link" data-toggle="collapse" href="#plan_management">
                        <i class="fas fa-box-open"></i>
                        <p>
                            <span>Subscription Package</span>
                            {{--<b class="caret"></b>--}}
                            <i class="fas fa-chevron-right"></i>
                        </p>
                    </a>
                      <div class="collapse {{ request()->is('dashboard/plan*') ? 'show' : '' }}
                      {{ request()->is('dashboard/plan-feature*') ? 'show' : '' }} " id="plan_management">
                        <ul class="nav">
                               <li class="nav-item">
                                <a href="{{ route("dashboard.plan.index") }}" class="nav-link {{ request()->is('dashboard/plan') ? 'active' : '' }}">
                                    </i><i class="fas fa-archive"></i>
                                    <span>Packages</span>
                                </a>
                            </li>
                            
                            <li class="nav-item">
                                <a href="{{ route("dashboard.plan-feature.index") }}" class="nav-link {{ request()->is('dashboard/plan-feature') ? 'active' : '' }}">
                                    <i class="far fa-calendar-alt"></i>
                                    <span>Package Features</span>
                                </a>
                            </li>
                           
                          
                         
                           
                        </ul>
                    </div>

                </li>
                @endcan

                @can('blog_management')
                <li class="nav-item has-treeview {{ request()->is('dashboard/blog*') ? 'menu-open active' : '' }}
                  {{ request()->is('dashboard/blog-category') || request()->is('dashboard/blog-category/*') ? 'menu-open active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#blog_management">
                       <i class="fas fa-newspaper"></i>
                        <p>
                            <span>Articles</span>
                            <i class="fas fa-chevron-right"></i>
                            
                        </p>
                    </a>
                    <div class="collapse {{ request()->is('dashboard/blog-category*') ? 'show' : '' }}
                      {{ request()->is('dashboard/blog*') ? 'show' : '' }} " id="blog_management">
                        <ul class="nav">
                             @can('blog_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.blog.index") }}" class="nav-link {{ request()->is('dashboard/blog') ? 'active' : '' }}">
                                   <i class="fas fa-newspaper"></i>
                                    <span>Articles</span>
                                </a>
                            </li>
                            @endcan
                            @can('blogcategory_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.blog-category.index") }}" class="nav-link {{ request()->is('dashboard/blog-category') 
                                    || request()->is('dashboard/blog-category*') ? 'active' : '' }}">
                                  <i class="fa fa-list-ul"></i>
                                    <span>Articles Category</span>
                                </a>
                            </li>
                            @endcan
                           
                        </ul>
                    </div>
                </li>
                @endcan

                 @can('mail_template')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.mail-template.index") }}" class="nav-link {{ request()->is('dashboard/mail-template') || request()->is('dashboard/mail-template/*') ? 'active' : '' }}">
                                    <p>
                                    <i class="fas fa-mail-bulk"></i>
                                    <span>Email Template</span>
                                    </p>
                                </a>
                            </li>
                @endcan

                @can('newsletter_access')
                            <!-- <li class="nav-item">
                                <a href="{{ route("dashboard.newsletter.index") }}" class="nav-link {{ request()->is('dashboard/newsletter') || request()->is('dashboard/newsletter/*') ? 'active' : '' }}">
                                    <p>
                                    <i class="fab fa-mailchimp"></i>
                                    <span>Newsletter</span>
                                    </p>
                                </a>
                            </li> -->
                @endcan

                @can('setting_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.setting.index") }}" class="nav-link {{ request()->is('dashboard/setting') || request()->is('dashboard/setting*') ? 'active' : '' }}">
                                    <p>
                                    <i class="fas fa-cog"></i>
                                    <span>Website Setting</span>
                                    </p>
                                </a>
                            </li>
                @endcan

                @can('setting_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.pages.index") }}" class="nav-link {{ request()->is('dashboard/pages') || request()->is('dashboard/pages*') ? 'active' : '' }}">
                                    <p>
                                  <i class="fa fa-file" aria-hidden="true"></i>
                                    <span>Pages</span>
                                    </p>
                                </a>
                            </li>
                @endcan

                 @can('setting_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.notifications.index") }}" class="nav-link {{ request()->is('dashboard/notifications') || request()->is('dashboard/notifications*') ? 'active' : '' }}">
                                    <p>
                                    <i class="far fa-bell"></i>
                                    <span>Notifications</span>
                                    </p>
                                </a>
                            </li>
                 @endcan


                @can('page_management')
                {{--<li class="nav-item has-treeview {{ request()->is('dashboard/pages*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/load-page*') ? 'menu-open active' : '' }} ">
                    <a class="nav-link" data-toggle="collapse" href="#page_management">
                        <i class="fas fa-file-alt"></i>
                        <p>
                            <span>Pages</span>
                            <b class="caret"></b>
                        </p>
                    </a>
                    <div class="collapse {{ request()->is('dashboard/pages*') ? 'show' : '' }} {{ request()->is('dashboard/load-page*') ? 'show' : '' }}" id="page_management">
                        <ul class="nav">
                            @can('page_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.pages.index") }}" class="nav-link {{ request()->is('dashboard/pages') || request()->is('dashboard/pages*') ? 'active' : '' }}">
                                    <i class="far fa-file-alt"></i>
                                    <span>Static Pages</span>
                                </a>
                            </li>
                            @endcan
                            @can('home_page_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.load-page") }}" class="nav-link {{ request()->is('dashboard/load-page') || request()->is('dashboard/load-page/*') ? 'active' : '' }}">
                                    <i class="far fa-file-alt"></i>
                                    <span>Home Page</span>
                                </a>
                            </li>
                            @endcan

                        </ul>
                    </div>
                </li>--}}
                @endcan

            
                @can('report_access')
                <li class="nav-item">
                    <a href="{{ route('dashboard.report.index') }}" class="nav-link {{ request()->is('dashboard/report') || request()->is('dashboard/report*') ? 'active' : '' }}">
                        <i class="fas fa-file-excel"></i>
                        <span>Reports</span>

                    </a>
                </li>
                @endcan

            
                @can('user_management_access')
                <li class="nav-item has-treeview {{ request()->is('dashboard/permissions*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/roles*') ? 'menu-open active' : '' }} {{ request()->is('dashboard/users*') ? 'menu-open active' : '' }}">
                    <a class="nav-link" data-toggle="collapse" href="#user_management">
                        <i class="fa-fw fas fa-users"></i>
                        <p>
                            <span>{{ trans('cruds.userManagement.title') }}</span>
                           {{--<b class="caret"></b>--}} 
                            <i class="fas fa-chevron-right"></i>
                        </p>
                    </a>
                    <div class="collapse {{ request()->is('dashboard/permissions*') ? 'show' : '' }} {{ request()->is('dashboard/roles*') ? 'show' : '' }} {{ request()->is('dashboard/users*') ? 'show' : '' }}" id="user_management">
                        <ul class="nav">
                            @can('permission_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.permissions.index") }}" class="nav-link {{ request()->is('dashboard/permissions') || request()->is('dashboard/permissions/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-unlock-alt"></i>
                                    <span>Role Permissions</span>
                                </a>
                            </li>
                            @endcan
                            @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.roles.index") }}" class="nav-link {{ request()->is('dashboard/roles') || request()->is('dashboard/roles/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-briefcase"></i>
                                    <span>User Roles</span>
                                </a>
                            </li>
                            @endcan
                            @can('user_access')
                            <li class="nav-item">
                                <a href="{{ route("dashboard.users.index") }}" class="nav-link {{ request()->is('dashboard/users') || request()->is('dashboard/users/*') ? 'active' : '' }}">
                                    <i class="fa-fw fas fa-user">
                                    </i><span>{{ trans('cruds.user.title') }}</span>
                                </a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcan
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                        <p>
                            <i class="fas fa-fw fa-sign-out-alt"></i>
                            <span>{{ trans('global.logout') }}</span>
                        </p>
                    </a>
                </li>
            </ul>
        </div>
        <!-- /.sidebar-menu -->
    </div>
</div>

<!-- /.sidebar -->
