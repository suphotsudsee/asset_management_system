<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-bar"></i>
        รายงานและสถิติ
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown">
                <i class="fas fa-download"></i> ส่งออกรายงาน
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="<?php echo base_url('reports/export_survey'); ?>">
                    <i class="fas fa-file-csv"></i> รายงานสำรวจครุภัณฑ์
                </a>
                <a class="dropdown-item" href="<?php echo base_url('reports/export_depreciation'); ?>">
                    <i class="fas fa-file-csv"></i> รายงานค่าเสื่อมราคา
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ครุภัณฑ์ทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($total_assets); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            ใช้งานปกติ
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($active_assets); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            ซ่อมแซม
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($repair_assets); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            จำหน่ายแล้ว
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($disposed_assets); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-trash fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Value Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            มูลค่าทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($total_value, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            ค่าเสื่อมสะสม
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($depreciation_value, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line-down fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            มูลค่าตามบัญชี
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($book_value, 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calculator fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Repair Summary Cards -->
<div class="row mb-4">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            รอพิจารณา
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($pending_repairs); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            อนุมัติแล้ว
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($approved_repairs); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            เสร็จสิ้น
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($completed_repairs); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-double fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row">
    <!-- Asset Status Chart -->
    <div class="col-xl-6 col-lg-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">สถิติครุภัณฑ์ตามสถานะ</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="assetStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Asset Category Chart -->
    <div class="col-xl-6 col-lg-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">สถิติครุภัณฑ์ตามประเภท</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="assetCategoryChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reports Menu -->
<div class="row">
    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">รายงานต่างๆ</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-search fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold">รายงานสำรวจครุภัณฑ์ประจำปี</h6>
                                        <p class="text-muted small mb-2">รายงานการสำรวจและตรวจสอบครุภัณฑ์ประจำปี</p>
                                        <a href="<?php echo base_url('reports/annual_survey'); ?>" class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye"></i> ดูรายงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-warning h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-chart-line-down fa-2x text-warning"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold">รายงานค่าเสื่อมราคา</h6>
                                        <p class="text-muted small mb-2">รายงานการคำนวณค่าเสื่อมราคาครุภัณฑ์</p>
                                        <a href="<?php echo base_url('reports/depreciation'); ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-eye"></i> ดูรายงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-info h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-clipboard-list fa-2x text-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold">รายงานสถานะครุภัณฑ์</h6>
                                        <p class="text-muted small mb-2">รายงานสถานะและการใช้งานครุภัณฑ์</p>
                                        <a href="<?php echo base_url('reports/asset_status'); ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i> ดูรายงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-exchange-alt fa-2x text-success"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold">รายงานการโอนย้าย</h6>
                                        <p class="text-muted small mb-2">รายงานการโอนย้ายครุภัณฑ์ระหว่างสถานที่</p>
                                        <a href="<?php echo base_url('reports/transfers'); ?>" class="btn btn-success btn-sm">
                                            <i class="fas fa-eye"></i> ดูรายงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-danger h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-trash fa-2x text-danger"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold">รายงานการจำหน่าย</h6>
                                        <p class="text-muted small mb-2">รายงานการจำหน่ายครุภัณฑ์ที่หมดอายุการใช้งาน</p>
                                        <a href="<?php echo base_url('reports/disposals'); ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-eye"></i> ดูรายงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 mb-3">
                        <div class="card border-left-secondary h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="mr-3">
                                        <i class="fas fa-tools fa-2x text-secondary"></i>
                                    </div>
                                    <div>
                                        <h6 class="font-weight-bold">รายงานการซ่อมแซม</h6>
                                        <p class="text-muted small mb-2">รายงานการซ่อมแซมและบำรุงรักษาครุภัณฑ์</p>
                                        <a href="<?php echo base_url('reports/repairs'); ?>" class="btn btn-secondary btn-sm">
                                            <i class="fas fa-eye"></i> ดูรายงาน
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Load asset statistics for charts
    loadAssetStatistics();
});

function loadAssetStatistics() {
    $.ajax({
        url: '<?php echo base_url('reports/api_asset_statistics'); ?>',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            // Asset Status Pie Chart
            if (data.status_stats && data.status_stats.length > 0) {
                createAssetStatusChart(data.status_stats);
            }
            
            // Asset Category Pie Chart
            if (data.category_stats && data.category_stats.length > 0) {
                createAssetCategoryChart(data.category_stats);
            }
        },
        error: function() {
            console.log('Error loading asset statistics');
        }
    });
}

function createAssetStatusChart(data) {
    var ctx = document.getElementById('assetStatusChart').getContext('2d');
    
    var labels = data.map(function(item) { return item.status; });
    var values = data.map(function(item) { return parseInt(item.count); });
    var colors = [
        '#1cc88a', // ใช้งาน - เขียว
        '#f6c23e', // ซ่อมแซม - เหลือง
        '#e74a3b', // จำหน่ายแล้ว - แดง
        '#36b9cc', // อื่นๆ - ฟ้า
        '#858796'  // เพิ่มเติม - เทา
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

function createAssetCategoryChart(data) {
    var ctx = document.getElementById('assetCategoryChart').getContext('2d');
    
    var labels = data.map(function(item) { return item.category; });
    var values = data.map(function(item) { return parseInt(item.count); });
    var colors = [
        '#4e73df', // น้ำเงิน
        '#1cc88a', // เขียว
        '#36b9cc', // ฟ้า
        '#f6c23e', // เหลือง
        '#e74a3b', // แดง
        '#858796', // เทา
        '#5a5c69', // เทาเข้ม
        '#2e59d9'  // น้ำเงินเข้ม
    ];
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: colors.slice(0, labels.length),
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}
</script>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.border-left-secondary {
    border-left: 0.25rem solid #858796 !important;
}

.chart-pie {
    position: relative;
    height: 15rem;
}
</style>

