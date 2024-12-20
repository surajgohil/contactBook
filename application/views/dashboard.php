<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light">
    <!-- Hamburger Menu Icon -->
    <ul class="navbar-nav headerNav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fa-solid fa-bars" style="font-size: 25px;"></i>
            </a>
        </li>
        <li class="nav-item options">
            <button class="btn btn-danger deleteMultipleContacts">Delete</button>
            <button class="btn btn-info">Create Group</button>
        </li>
        <li class="nav-item">
            <!-- <div><i class="fa-solid fa-plus"></i></div>
            <div><label class="pt-1">Add New</label></div> -->
            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addNew">
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
        <div class="brand-text font-weight-light">
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
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Add more sidebar items as needed -->
            </ul>
        </nav>
    </div>
</aside>


<div class="content-wrapper">
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
            <table id="contactNumberListing" class="table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Select Rows</th>
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