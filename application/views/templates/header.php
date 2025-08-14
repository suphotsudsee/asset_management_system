<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : 'ระบบจัดการครุภัณฑ์'; ?></title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/custom.css'); ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    
    <!-- Date Picker CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="sidebar-sticky">
                    <div class="text-center py-4">
                        <h4 class="text-white">
                            <i class="fas fa-boxes"></i>
                            ระบบครุภัณฑ์
                        </h4>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && $page_name == 'dashboard') ? 'active' : ''; ?>" 
                               href="<?php echo base_url('dashboard'); ?>">
                                <i class="fas fa-tachometer-alt"></i>
                                หน้าหลัก
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'assetmanager') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('assetmanager'); ?>">
                                <i class="fas fa-box"></i>
                                จัดการครุภัณฑ์
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'transfer') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('transfers'); ?>">
                                <i class="fas fa-exchange-alt"></i>
                                โอนย้ายครุภัณฑ์
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'disposal') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('disposals'); ?>">
                                <i class="fas fa-trash-alt"></i>
                                แทงจำหน่าย
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'repair') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('repairs'); ?>">
                                <i class="fas fa-tools"></i>
                                ซ่อมแซม
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'survey') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('surveys'); ?>">
                                <i class="fas fa-clipboard-check"></i>
                                สำรวจประจำปี
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'depreciation') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('depreciation'); ?>">
                                <i class="fas fa-chart-line"></i>
                                ค่าเสื่อมราคา
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'guarantee') !== false) ? 'active' : ''; ?>"
                               href="<?php echo base_url('guarantees'); ?>">
                                <i class="fas fa-handshake"></i>
                                ค้ำประกันสัญญา
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'user') !== false) ? 'active' : ''; ?>"
                               href="<?php echo base_url('users'); ?>">
                                <i class="fas fa-users"></i>
                                จัดการผู้ใช้
                            </a>
                        </li>

                        <li class="nav-item mt-3">
                            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
                                <span>รายงาน</span>
                            </h6>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link <?php echo (isset($page_name) && strpos($page_name, 'report') !== false) ? 'active' : ''; ?>" 
                               href="<?php echo base_url('reports'); ?>">
                                <i class="fas fa-file-alt"></i>
                                รายงานต่างๆ
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Mobile menu toggle -->
                <div class="d-md-none">
                    <button class="btn btn-primary mb-3" type="button" id="sidebarToggle">
                        <i class="fas fa-bars"></i> เมนู
                    </button>
                </div>
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb" class="mt-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="<?php echo base_url('dashboard'); ?>">
                                <i class="fas fa-home"></i> หน้าหลัก
                            </a>
                        </li>
                        <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                            <?php foreach ($breadcrumbs as $breadcrumb): ?>
                                <?php if (isset($breadcrumb['url'])): ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?php echo $breadcrumb['url']; ?>"><?php echo $breadcrumb['title']; ?></a>
                                    </li>
                                <?php else: ?>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?php echo $breadcrumb['title']; ?>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ol>
                </nav>
                
                <!-- Alert messages -->
                <div id="alerts-container">
                    <?php if ($this->session->flashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            <?php echo $this->session->flashdata('success'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('warning')): ?>
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            <?php echo $this->session->flashdata('warning'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($this->session->flashdata('info')): ?>
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i>
                            <?php echo $this->session->flashdata('info'); ?>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>

