<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-search"></i>
        รายงานสำรวจครุภัณฑ์ประจำปี <?php echo ($selected_year + 543); ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('reports/print_survey?' . $_SERVER['QUERY_STRING']); ?>" 
               class="btn btn-secondary" target="_blank">
                <i class="fas fa-print"></i> พิมพ์
            </a>
            <a href="<?php echo base_url('reports/export_survey?' . $_SERVER['QUERY_STRING']); ?>" 
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
        <form method="GET" action="<?php echo base_url('reports/annual_survey'); ?>">
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
                    <label for="location">สถานที่</label>
                    <select class="form-control" id="location" name="location">
                        <option value="">ทั้งหมด</option>
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?php echo htmlspecialchars($loc['current_location']); ?>" 
                                        <?php echo ($selected_location == $loc['current_location']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($loc['current_location']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
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
                    <label for="status">สถานะ</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">ทั้งหมด</option>
                        <option value="ใช้งาน" <?php echo ($selected_status == 'ใช้งาน') ? 'selected' : ''; ?>>ใช้งาน</option>
                        <option value="ซ่อมแซม" <?php echo ($selected_status == 'ซ่อมแซม') ? 'selected' : ''; ?>>ซ่อมแซม</option>
                        <option value="ไม่ใช้งาน" <?php echo ($selected_status == 'ไม่ใช้งาน') ? 'selected' : ''; ?>>ไม่ใช้งาน</option>
                    </select>
                </div>
                <div class="col-md-1 mb-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Survey Statistics -->
<?php if (!empty($survey_stats)): ?>
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ครุภัณฑ์ทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($survey_stats['total_assets']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
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
                            สำรวจแล้ว
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($survey_stats['surveyed']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check fa-2x text-gray-300"></i>
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
                            ยังไม่สำรวจ
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($survey_stats['not_surveyed']); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            ความคืบหน้า
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format(($survey_stats['surveyed'] / $survey_stats['total_assets']) * 100, 1); ?>%
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Assets Table -->
<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">
            รายการครุภัณฑ์สำหรับสำรวจ
            <?php if (!empty($assets)): ?>
                (<?php echo number_format(count($assets)); ?> รายการ)
            <?php endif; ?>
        </h6>
    </div>
    <div class="card-body">
        <?php if (!empty($assets)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="surveyTable">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อครุภัณฑ์</th>
                            <th>ประเภท</th>
                            <th>หมายเลขซีเรียล</th>
                            <th>สถานที่</th>
                            <th>ผู้รับผิดชอบ</th>
                            <th>ราคาทุน</th>
                            <th>ค่าเสื่อมสะสม</th>
                            <th>มูลค่าตามบัญชี</th>
                            <th>สถานะ</th>
                            <th>สถานะการสำรวจ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assets as $asset): ?>
                            <?php $book_value = $asset['purchase_price'] - $asset['accumulated_depreciation']; ?>
                            <tr>
                                <td>
                                    <a href="<?php echo base_url('assets/view/' . $asset['asset_id']); ?>" 
                                       class="text-decoration-none font-weight-bold">
                                        <?php echo htmlspecialchars($asset['asset_code']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($asset['asset_name']); ?></td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo htmlspecialchars($asset['category']); ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($asset['serial_number'] ?: '-'); ?></small>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($asset['current_location']); ?></small>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($asset['responsible_person']); ?></small>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($asset['purchase_price'], 2); ?>
                                </td>
                                <td class="text-right">
                                    <span class="text-danger">
                                        <?php echo number_format($asset['accumulated_depreciation'], 2); ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <strong><?php echo number_format($book_value, 2); ?></strong>
                                </td>
                                <td>
                                    <span class="asset-status"><?php echo $asset['status']; ?></span>
                                </td>
                                <td>
                                    <span class="survey-status" data-asset-id="<?php echo $asset['asset_id']; ?>">
                                        <?php echo isset($asset['survey_status']) ? $asset['survey_status'] : 'ยังไม่สำรวจ'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-info">
                            <th colspan="6">รวม</th>
                            <th class="text-right">
                                <?php 
                                $total_cost = array_sum(array_column($assets, 'purchase_price'));
                                echo number_format($total_cost, 2);
                                ?>
                            </th>
                            <th class="text-right">
                                <?php 
                                $total_depreciation = array_sum(array_column($assets, 'accumulated_depreciation'));
                                echo number_format($total_depreciation, 2);
                                ?>
                            </th>
                            <th class="text-right">
                                <strong><?php echo number_format($total_cost - $total_depreciation, 2); ?></strong>
                            </th>
                            <th colspan="2"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-search fa-5x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลครุภัณฑ์</h5>
                <p class="text-muted">
                    <?php if (!empty($selected_location) || !empty($selected_category) || !empty($selected_status)): ?>
                        ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?php echo base_url('reports/annual_survey?year=' . $selected_year); ?>">ดูทั้งหมด</a>
                    <?php else: ?>
                        ไม่มีครุภัณฑ์ในปีที่เลือก
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#surveyTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
        },
        "responsive": true,
        "pageLength": 50,
        "order": [[0, "asc"]],
        "columnDefs": [
            { "orderable": false, "targets": [10] }
        ],
        "footerCallback": function(row, data, start, end, display) {
            // Update footer totals for visible rows only
            var api = this.api();
            
            // Calculate totals for visible rows
            var totalCost = api
                .column(6, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                }, 0);
                
            var totalDepreciation = api
                .column(7, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return parseFloat(a) + parseFloat(b.replace(/,/g, ''));
                }, 0);
            
            var totalBookValue = totalCost - totalDepreciation;
            
            // Update footer
            $(api.column(6).footer()).html(totalCost.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $(api.column(7).footer()).html(totalDepreciation.toLocaleString('th-TH', {minimumFractionDigits: 2}));
            $(api.column(8).footer()).html('<strong>' + totalBookValue.toLocaleString('th-TH', {minimumFractionDigits: 2}) + '</strong>');
        }
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
    
    // Survey status badges
    $('.survey-status').each(function() {
        var status = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-warning badge-danger');
        
        switch(status) {
            case 'สำรวจแล้ว':
                $(this).addClass('badge badge-success');
                break;
            case 'ยังไม่สำรวจ':
                $(this).addClass('badge badge-warning');
                break;
            case 'ไม่พบ':
                $(this).addClass('badge badge-danger');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
    
    // Auto-submit form on change
    $('#year, #location, #category, #status').on('change', function() {
        $(this).closest('form').submit();
    });
});
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

