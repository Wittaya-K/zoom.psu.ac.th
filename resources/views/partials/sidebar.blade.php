@inject('request', 'Illuminate\Http\Request')
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu">

             
            <li class="header">เมนูหลัก</li>
            <li class="{{ $request->segment(2) == 'home' ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <i class="fa fa-home"></i>
                    <span class="title">หน้าหลัก</span>
                </a>
            </li>

            
            @can('user_management_access')
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-users"></i>
                    <span class="title">จัดการบัญชีผู้ใช้งาน</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                
                @can('role_access')
                <li class="{{ $request->segment(1) == 'roles' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.roles.index') }}">
                            <i class="fa fa-briefcase"></i>
                            <span class="title">
                                สิทธิ์
                            </span>
                        </a>
                    </li>
                @endcan
                @can('user_access')
                <li class="{{ $request->segment(1) == 'users' ? 'active active-sub' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            <span class="title">
                                ผู้ใช้งาน
                            </span>
                        </a>
                    </li>
                @endcan
                </ul>
            </li>
            @endcan

            @can('country_access')
            {{-- <li class="{{ $request->segment(2) == 'countries' ? 'active' : '' }}">
                <a href="{{ route('admin.countries.index') }}">
                    <i class="fa fa-gears"></i>
                    <span class="title">@lang('quickadmin.countries.title')</span>
                </a>
            </li> --}}
            @endcan
            @can('category_create')
                <li class="{{ $request->segment(2) == 'categories' ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="fa fa-cogs" aria-hidden="true"></i>
                            <span class="title">
                                สถานะบัญชีผู้ใช้งาน
                            </span>
                        </a>
                    </li>
            @endcan

            {{-- @can('customer_access')
            <li class="{{ $request->segment(2) == 'customers' ? 'active' : '' }}">
                <a href="{{ route('admin.customers.index') }}">
                    <i class="fa fa-low-vision"></i>
                    <span class="title">@lang('quickadmin.customers.title')</span>
                </a>
            </li>
            @endcan --}}

            {{-- @can('room_access')
            <li class="{{ $request->segment(1) == 'rooms' ? 'active' : '' }}">
                <a href="{{ route('admin.rooms.index') }}">
                    <i class="fa fa-list-ul"></i>
                    <span class="title">รายการบัญชีใช้งาน</span>
                </a>
            </li>
            @endcan --}}

            @can('zoom_access')
            <li class="{{ $request->segment(2) == 'zooms' ? 'active' : '' }}">
                <a href="{{ route('admin.zooms.index') }}">
                    <i class="fa fa-list-ul"></i>
                    <span class="title">รายการบัญชีใช้งาน</span>
                </a>
            </li>
            @endcan

            @can('booking_access')
            <li class="{{ $request->segment(2) == 'bookings' ? 'active' : '' }}">
                <a href="{{ route('admin.bookings.index') }}">
                    <i class="fa fa-calendar-check-o"></i>
                    <span class="title">การจองบัญชีใช้งาน</span>
                </a>
            </li>
            @endcan

            {{-- @can('find_room_access')
            <li class="{{ $request->segment(1) == 'find_rooms' ? 'active' : '' }}">
                <a href="{{ route('admin.find_rooms.index') }}">
                    <i class="fa fa-search"></i>
                    <span class="title">ค้นหา</span>
                </a>
            </li>
            @endcan --}}


            <li class="header">จัดการผู้ใช้</li>

            {{-- <li class="{{ $request->segment(1) == 'change_password' ? 'active' : '' }}">
                <a href="{{ route('auth.change_password') }}">
                    <i class="fa fa-key"></i>
                    <span class="title">เปลี่ยนรหัสผ่าน</span>
                </a>
            </li> --}}

            <li>
                <a href="#logout" onclick="$('#logout').submit();">
                    <i class="fa fa-arrow-left"></i>
                    <span class="title">ออกจากระบบ</span>
                </a>
            </li>
        </ul>
    </section>
</aside>