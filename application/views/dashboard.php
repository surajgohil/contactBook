<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light" style="background: #343a40;">
    <!-- Hamburger Menu Icon -->
    <ul class="navbar-nav headerNav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fa-solid fa-bars" style="font-size: 25px;color: #fff;"></i>
            </a>
        </li>
        <li class="nav-item options">
            <button class="btn btn-danger deleteMultipleContacts" disabled>Delete All</button>
            <!-- <button class="btn btn-info"></button> -->
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#groupModal">Create Group</button>
        </li>
        <li class="nav-item">
            <!-- <div><i class="fa-solid fa-plus"></i></div>
            <div><label class="pt-1">Add New</label></div> -->
            <button class="btn btn-primary addContactBtn" type="button" data-toggle="modal" data-target="#addNew">
                <i class="fa-solid fa-plus"></i>
                <label class="addNewBtn">Add New</label>
            </button>
        </li>
    </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div href="#" class="brand-link d-flex justify-content-between align-items-center" style="height: 10%;">
        <div>
            <img src="<?= $this->session->userdata('image') ?>" alt="" style="width: 40px;height: 40px;object-fit: cover;border-radius: 50%;transform: translateX(10px);">
        </div>
        <div class="brand-text font-weight-light" style="text-transform: capitalize;font-weight: 900 !important;">
            <?= $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?>
        </div>
        <div class="btn btn-danger d-flex justify-content-center align-items-center h-100" id="logOut">
            <span><i class="fas fa-sign-out"></i></span>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar sidebar-expanded">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul id="navbarMenu" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li> -->
                <!-- Add more sidebar items as needed -->
            </ul>
        </nav>
    </div>
</aside>


<div class="content-wrapper" style="background: #222529;">
    <!-- <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Dashboard</h1>
                </div>
            </div>
        </div>
    </div> -->

    <section class="content" style="width:97% !important; margin: auto; margin-top: 20px;">
        <form id="selectContact">
            <table id="contactNumberListing" class="table-striped table-bordered w-100" style="color: #fff;">
                <thead>
                    <tr>
                        <th></th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </form>
    </section>
</div>