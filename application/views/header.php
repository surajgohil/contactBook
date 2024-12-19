<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/admin-lte@3.0.4/dist/css/adminlte.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <!-- Material Components Web CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/material-components-web/14.0.0/material-components-web.min.css" rel="stylesheet">

    <!-- DataTables Material Design integration CSS -->
    <link href="https://cdn.datatables.net/2.1.8/css/dataTables.material.css" rel="stylesheet">
    <!-- jQuery 3.7.1 -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <!-- Material Components Web JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/material-components-web/14.0.0/material-components-web.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    <!-- DataTables Material Design integration JS -->
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.material.js"></script>





    <style>
        body {
            background-color: #f8f9fa;
        }
        .signup-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar-expanded {
            width: 250px;
        }
        .sidebar-collapsed {
            width: 60px;
        }
        .headerNav{
            width: 99%;
            display: flex;
            flex-direction: row !important;
            justify-content: space-between;
            align-items: center;
        }
        .addNewBtn{
            line-height: 1px;
        }
    </style>

    <style>
        .form-container {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
        }

        .form-container input {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #45a049;
        }
    </style>

    <style>
        .wrapper{
            height : 100% !important;
        }
        .main-header{
            height : 10% !important;
        }
        .content-wrapper{
            height:80% !important;
            overflow: hidden !important;
        }
        .main-footer{
            height : 10% !important;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini" style="height:100vh !important">


<div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-light">
            <!-- Hamburger Menu Icon -->
            <ul class="navbar-nav headerNav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                        <i class="fa-solid fa-bars" style="font-size: 25px;"></i>
                    </a>
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
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light"><?= $this->session->userdata('first_name').' '.$this->session->userdata('last_name'); ?></span>
            </a>

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