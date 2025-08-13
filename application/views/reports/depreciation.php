<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-chart-line-down"></i>
        รายงานค่าเสื่อมราคาครุภัณฑ์ ปี <?php echo ($selected_year + 543); ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('reports/print_depreciation?' . $_SERVER['QUERY_STRING']); ?>" 
               class="btn btn-secondary" target="_blank">
                <i class="fas fa-print"></i> พิมพ์
            </a>
            <a href="<?php echo base_url('reports/export_depreciation?' . $_SERVER['QUERY_STRING']); ?>" 
               class="btn btn-success">
                <i class="fas fa-download"></i> ส่งออก CSV
            </a>
            <a href="<?php echo base_url('reports'); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('reports/depreciation'); ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="year">ปีงบประมาณ</label>
                    <select class="form-control" id="year" name="year">
                        <?php for ($y = date('Y'); $y >= date('Y') - 10; $y--): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($selected_year == $y) ? 'selected' : ''; ?>>
                                <?php echo ($y + 543); ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="month">เดือน</label>
                    <select class="form-control" id="month" name="month">
                        <option value="">ทั้งปี</option>
                        <?php 
                        $months = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                                      'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
                        for ($m = 1; $m <= 12; $m++): 
                        ?>
                            <option value="<?php echo $m; ?>" <?php echo ($selected_month == $m) ? 'selected' : ''; ?>>
                                <?php echo $months[$m]; ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="category">ประเภท</label>
                    <select class="form-control" id="category" name="category">
                        <option value="">ทั้งหมด</option>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                                        <?php echo ($selected_category == $cat['category']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> ค้นหา
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Summary Cards -->
<?php if (!empty($depreciation_summary)): ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            มูลค่าทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($depreciation_summary['total_cost'], 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            ค่าเสื่อมรายปี
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($depreciation_summary['annual_depreciation'], 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line-down fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            ค่าเสื่อมสะสม
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($depreciation_summary['accumulated_depreciation'], 2); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-minus-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            มูลค่าตามบัญชี
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ฿<?php echo number_format($depreciation_summary['book_value'], 2); ?>
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
<?php endif; ?>

<!-- Charts Row -->
<div class="row mb-4">
    <div class="col-xl-8">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">ค่าเสื่อมราคารายเดือน</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="monthlyDepreciationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">ค่าเสื่อมตามประเภท</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4">
                    <canvas id="categoryDepreciationChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Depreciation Table -->
<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">
            รายละเอียดค่าเสื่อมราคาครุภัณฑ์
            <?php if (!empty($depreciation_data)): ?>
                (<?php echo number_format(count($depreciation_data)); ?> รายการ)
            <?php endif; ?>
        </h6>
    </div>
    <div class="card-body">
        <?php if (!empty($depreciation_data)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="depreciationTable">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อครุภัณฑ์</th>
                            <th>ประเภท</th>
                            <th>วันที่จัดซื้อ</th>
                            <th>ราคาทุน</th>
                            <th>อายุการใช้งาน</th>
                            <th>วิธีคิดค่าเสื่อม</th>
                            <th>ค่าเสื่อมรายปี</th>
                            <th>ค่าเสื่อมสะสม</th>
                            <th>มูลค่าตามบัญชี</th>
                            <th>สถานะ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($depreciation_data as $item): ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('assets/view/' . $item['asset_id']); ?>" 
                                       class="text-decoration-none font-weight-bold">
                                        <?php echo htmlspecialchars($item['asset_code']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($item['asset_name']); ?></td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo htmlspecialchars($item['category']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($item['purchase_date'])); ?></td>
                                <td class="text-right">
                                    <?php echo number_format($item['purchase_price'], 2); ?>
                                </td>
                                <td class="text-center">
                                    <?php echo $item['useful_life']; ?> ปี
                                </td>
                                <td class="text-center">
                                    <small><?php echo htmlspecialchars($item['depreciation_method']); ?></small>
                                </td>
                                <td class="text-right">
                                    <span class="text-warning">
                                        <?php echo number_format($item['annual_depreciation'], 2); ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="text-danger">
                                        <?php echo number_format($item['accumulated_depreciation'], 2); ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <strong><?php echo number_format($item['book_value'], 2); ?></strong>
                                </td>
                                <td>
                                    <span class="asset-status"><?php echo $item['status']; ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <th colspan="4">รวม</th>
                            <th class="text-right">
                                <?php 
                                $total_cost = array_sum(array_column($depreciation_data, 'purchase_price'));
                                echo number_format($total_cost, 2);
                                ?>
                            </th>
                            <th></th>
                            <th></th>
                            <th class="text-right">
                                <?php 
                                $total_annual = array_sum(array_column($depreciation_data, 'annual_depreciation'));
                                echo number_format($total_annual, 2);
                                ?>
                            </th>
                            <th class="text-right">
                                <?php 
                                $total_accumulated = array_sum(array_column($depreciation_data, 'accumulated_depreciation'));
                                echo number_format($total_accumulated, 2);
                                ?>
                            </th>
                            <th class="text-right">
                                <strong><?php echo number_format($total_cost - $total_accumulated, 2); ?></strong>
                            </th>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-chart-line-down fa-5x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลค่าเสื่อมราคา</h5>
                <p class="text-muted">
                    <?php if (!empty($selected_month) || !empty($selected_category)): ?>
                        ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?php echo base_url('reports/depreciation?year=' . $selected_year); ?>">ดูทั้งหมด</a>
                    <?php else: ?>
                        ไม่มีข้อมูลค่าเสื่อมราคาในปีที่เลือก
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#depreciationTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
        },
        "responsive": true,
        "pageLength": 50,
        "order": [[0, "asc"]],
        "columnDefs": [
            { "className": "text-right", "targets": [4, 7, 8, 9] },
            { "className": "text-center", "targets": [5, 6] }
        ]
    });
    
    // Asset status badges
    $('.asset-status').each(function() {
        var status = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-danger badge-warning');
        
        switch(status) {
            case 'ใช้งาน':
                $(this).addClass('badge badge-success');
                break;
            case 'ซ่อมแซม':
                $(this).addClass('badge badge-warning');
                break;
            case 'ไม่ใช้งาน':
                $(this).addClass('badge badge-secondary');
                break;
            case 'จำหน่ายแล้ว':
                $(this).addClass('badge badge-danger');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
    
    // Load depreciation charts
    loadDepreciationCharts();
    
    // Auto-submit form on change
    $('#year, #month, #category').on('change', function() {
        $(this).closest('form').submit();
    });
});

function loadDepreciationCharts() {
    $.ajax({
        url: '<?php echo base_url('reports/api_depreciation_chart'); ?>',
        method: 'GET',
        data: {
            year: <?php echo $selected_year; ?>,
            month: '<?php echo $selected_month; ?>',
            category: '<?php echo $selected_category; ?>'
        },
        dataType: 'json',
        success: function(data) {
            // Monthly depreciation chart
            if (data.monthly_depreciation && data.monthly_depreciation.length > 0) {
                createMonthlyDepreciationChart(data.monthly_depreciation);
            }
            
            // Category depreciation chart
            if (data.category_depreciation && data.category_depreciation.length > 0) {
                createCategoryDepreciationChart(data.category_depreciation);
            }
        },
        error: function() {
            console.log('Error loading depreciation charts');
        }
    });
}

function createMonthlyDepreciationChart(data) {
    var ctx = document.getElementById('monthlyDepreciationChart').getContext('2d');
    
    var months = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.',
                  'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];
    
    var labels = data.map(function(item) { return months[item.month - 1]; });
    var values = data.map(function(item) { return parseFloat(item.depreciation_amount); });
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'ค่าเสื่อมราคา (บาท)',
                data: values,
                borderColor: '#f6c23e',
                backgroundColor: 'rgba(246, 194, 62, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '฿' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'ค่าเสื่อมราคา: ฿' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
}

function createCategoryDepreciationChart(data) {
    var ctx = document.getElementById('categoryDepreciationChart').getContext('2d');
    
    var labels = data.map(function(item) { return item.category; });
    var values = data.map(function(item) { return parseFloat(item.depreciation_amount); });
    var colors = [
        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b',
        '#858796', '#5a5c69', '#2e59d9', '#17a2b8', '#28a745'
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
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            var total = context.dataset.data.reduce((a, b) => a + b, 0);
                            var percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ฿' + context.parsed.toLocaleString() + ' (' + percentage + '%)';
                        }
                    }
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

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.chart-area {
    position: relative;
    height: 20rem;
}

.chart-pie {
    position: relative;
    height: 15rem;
}

.table-responsive {
    font-size: 0.875rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fc;
}

.table tfoot th {
    background-color: #e3f2fd;
    font-weight: 600;
}
</style>

