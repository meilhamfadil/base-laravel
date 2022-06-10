 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">

     <!-- Brand Logo -->
     <a href="{{ url('') }}" class="brand-link">
         <img src="/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8">
         <span class="brand-text font-weight-light">Base Projek</span>
     </a>
     <!-- Sidebar -->
     <div class="sidebar">
         <!-- Sidebar user (optional) -->
         <div class="user-panel mt-3 pb-3 mb-3 d-flex">
             <div class="image">
                 <img src="/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
             </div>
             <div class="info">
                 <a href="#" class="d-block">Users</a>
             </div>
         </div>

         <!-- Sidebar Menu -->
         <nav class="mt-2">
             <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                 data-accordion="false">

                 @php
                     $appMenu = isset($app_menu) ? $app_menu : [];
                     $labels = array_filter($appMenu, function ($item) {
                         return $item['parent'] == '0' && $item['type'] == 'label';
                     });
                     $padding = 'padding: 0rem 1rem .5rem !important;';
                 @endphp

                 @foreach ($labels as $label)
                     <li class="nav-header" style="{{ $padding }}">{{ strtoupper($label['name']) }}</li>

                     @php
                         $padding = '';
                         $menus = array_filter($appMenu, function ($item) use ($label) {
                             return $item['parent'] == $label['id'];
                         });
                     @endphp

                     @foreach ($menus as $menu)
                         @php
                             $submenus = array_filter($appMenu, function ($item) use ($menu) {
                                 return $item['parent'] == $menu['id'];
                             });
                             $submenuclass = empty($submenus) ? '' : 'has-treeview';
                         @endphp

                         <li class="nav-item {{ $submenuclass }}">
                             <a href="{{ url($menu['link']) }}" class="nav-link"
                                 target="{{ $menu['target'] }}">
                                 <i class="nav-icon fas fa-{{ $menu['icon'] }}"></i>
                                 <p>
                                     {{ $menu['name'] }}
                                     @if (!empty($submenus))
                                         <i class="right fas fa-angle-left"></i>
                                     @endif
                                 </p>
                             </a>

                             @if (!empty($submenus))
                                 <ul class="nav nav-treeview">
                                     @foreach ($submenus as $submenu)
                                         <li class="nav-item">
                                             <a href="{{ url($submenu['link']) }}" class="nav-link"
                                                 target="{{ $submenu['target'] }}">
                                                 <i class="fas fa-circle nav-icon"></i>
                                                 <p>{{ $submenu['name'] }}</p>
                                             </a>
                                         </li>
                                     @endforeach
                                 </ul>
                             @endif

                         </li>
                     @endforeach
                 @endforeach

             </ul>
         </nav>
         <!-- /.sidebar-menu -->
     </div>
     <!-- /.sidebar -->
 </aside>
