  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}" href="index.html">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->

    

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Users Management</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.merchents') }}">
              <i class="bi bi-circle"></i><span>Merchents List</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.product_list') }}">
              <i class="bi bi-circle"></i><span>Users List</span>
            </a>
          </li>
        </ul>
      </li><!-- End User Nav -->
      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-journal-text"></i><span>Products</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.create_product') }}">
              <i class="bi bi-circle"></i><span>Add New</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.product_list') }}">
              <i class="bi bi-circle"></i><span>Product List</span>
            </a>
          </li>
        </ul>
      </li><!-- End Forms Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
          <i class="bi bi-layout-text-window-reverse"></i><span>Category</span><i class="bi bi-chevron-down ms-auto"></i>
        </a>
        <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
          <li>
            <a href="{{ route('admin.cate_new') }}">
              <i class="bi bi-circle"></i><span>Create Category</span>
            </a>
          </li>
          <li>
            <a href="{{ route('admin.cate_list') }}">
              <i class="bi bi-circle"></i><span>Category List</span>
            </a>
          </li>
        </ul>
      </li><!-- End Tables Nav -->

      

      

      <!-- <li class="nav-heading">Pages</li> -->

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="users-profile.html">
          <i class="bi bi-person"></i>
          <span>Profile</span>
        </a>
      </li>End Profile Page Nav -->

      <!-- <li class="nav-item">
        <a class="nav-link collapsed" href="pages-faq.html">
          <i class="bi bi-question-circle"></i>
          <span>F.A.Q</span>
        </a>
      </li>End F.A.Q Page Nav -->

     

    </ul>

  </aside>