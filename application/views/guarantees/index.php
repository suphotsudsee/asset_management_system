<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shield-alt"></i>
        ข้อมูลค้ำประกันสัญญา
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('guarantees/add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> เพิ่มค้ำประกันใหม่
            </a>
            <a href="<?php echo base_url('guarantees/expiring'); ?>" class="btn btn-warning">
                <i class="fas fa-clock"></i> ใกล้หมดอายุ
            </a>
            <a href="<?php echo base_url('guarantees/export'); ?>" class="btn btn-success">
                <i class="fas fa-download"></i> ส่งออก CSV
            </a>
        </div>
    </div>
</div>

<!-- Alert Messages -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Statistics Cards -->
<?php if (!empty($guarantee_stats)): ?>
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            ค้ำประกันทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($guarantee_stats['total_guarantees'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
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
                            ใช้งานได้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($guarantee_stats['active_guarantees'] ?? 0); ?>
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
                            ใกล้หมดอายุ (30 วัน)
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($guarantee_stats['expiring_soon'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            หมดอายุแล้ว
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?php echo number_format($guarantee_stats['expired_guarantees'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('guarantees'); ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">ค้นหา</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo htmlspecialchars($search_keyword); ?>"
                           placeholder="ชื่อครุภัณฑ์, ผู้จำหน่าย, เลขสัญญา">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status">สถานะ</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">ทั้งหมด</option>
                        <option value="ใช้งาน" <?php echo ($selected_status == 'ใช้งาน') ? 'selected' : ''; ?>>ใช้งาน</option>
                        <option value="หมดอายุ" <?php echo ($selected_status == 'หมดอายุ') ? 'selected' : ''; ?>>หมดอายุ</option>
                        <option value="ยกเลิก" <?php echo ($selected_status == 'ยกเลิก') ? 'selected' : ''; ?>>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="vendor">ผู้จำหน่าย</label>
                    <select class="form-control" id="vendor" name="vendor">
                        <option value="">ทั้งหมด</option>
                        <?php if (!empty($vendors)): ?>
                            <?php foreach ($vendors as $vendor): ?>
                                <option value="<?php echo htmlspecialchars($vendor['vendor_name']); ?>" 
                                        <?php echo ($selected_vendor == $vendor['vendor_name']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($vendor['vendor_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="expiry_filter">การหมดอายุ</label>
                    <select class="form-control" id="expiry_filter" name="expiry_filter">
                        <option value="">ทั้งหมด</option>
                        <option value="expired" <?php echo ($selected_expiry_filter == 'expired') ? 'selected' : ''; ?>>หมดอายุแล้ว</option>
                        <option value="expiring_30" <?php echo ($selected_expiry_filter == 'expiring_30') ? 'selected' : ''; ?>>ใกล้หมดอายุ 30 วัน</option>
                        <option value="expiring_60" <?php echo ($selected_expiry_filter == 'expiring_60') ? 'selected' : ''; ?>>ใกล้หมดอายุ 60 วัน</option>
                        <option value="expiring_90" <?php echo ($selected_expiry_filter == 'expiring_90') ? 'selected' : ''; ?>>ใกล้หมดอายุ 90 วัน</option>
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

<!-- Guarantees Table -->
<div class="card">
    <div class="card-header">
        <h6 class="m-0 font-weight-bold text-primary">
            รายการค้ำประกันสัญญา
            <?php if (!empty($guarantees)): ?>
                (<?php echo number_format(count($guarantees)); ?> รายการ)
            <?php endif; ?>
        </h6>
    </div>
    <div class="card-body">
        <?php if (!empty($guarantees)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="guaranteesTable">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ครุภัณฑ์</th>
                            <th>ประเภทค้ำประกัน</th>
                            <th>ผู้จำหน่าย/ผู้ให้บริการ</th>
                            <th>เลขที่สัญญา</th>
                            <th>วันที่เริ่มต้น</th>
                            <th>วันที่สิ้นสุด</th>
                            <th>วันคงเหลือ</th>
                            <th>สถานะ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($guarantees as $guarantee): ?>
                            <?php 
                            $days_remaining = ceil((strtotime($guarantee['end_date']) - time()) / (60 * 60 * 24));
                            $is_expired = $days_remaining < 0;
                            $is_expiring_soon = $days_remaining <= 30 && $days_remaining >= 0;
                            ?>
                            <tr class="<?php echo $is_expired ? 'table-danger' : ($is_expiring_soon ? 'table-warning' : ''); ?>">
                                <td>
                                    <a href="<?php echo base_url('guarantees/view/' . $guarantee['guarantee_id']); ?>" 
                                       class="text-decoration-none font-weight-bold">
                                        #<?php echo $guarantee['guarantee_id']; ?>
                                    </a>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($guarantee['asset_name']); ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <?php echo htmlspecialchars($guarantee['asset_id']); ?>
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($guarantee['guarantee_type']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong><?php echo htmlspecialchars($guarantee['vendor_name']); ?></strong>
                                        <?php if (!empty($guarantee['vendor_contact'])): ?>
                                            <br>
                                            <small class="text-muted">
                                                <i class="fas fa-phone"></i>
                                                <?php echo htmlspecialchars($guarantee['vendor_contact']); ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if (!empty($guarantee['contract_number'])): ?>
                                        <span class="badge badge-secondary">
                                            <?php echo htmlspecialchars($guarantee['contract_number']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($guarantee['start_date'])); ?>
                                </td>
                                <td>
                                    <?php echo date('d/m/Y', strtotime($guarantee['end_date'])); ?>
                                </td>
                                <td class="text-center">
                                    <?php if ($is_expired): ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i>
                                            หมดอายุ <?php echo abs($days_remaining); ?> วัน
                                        </span>
                                    <?php elseif ($is_expiring_soon): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i>
                                            <?php echo $days_remaining; ?> วัน
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i>
                                            <?php echo $days_remaining; ?> วัน
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="guarantee-status"><?php echo $guarantee['status']; ?></span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo base_url('guarantees/view/' . $guarantee['guarantee_id']); ?>" 
                                           class="btn btn-sm btn-outline-primary" title="ดูรายละเอียด">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('guarantees/edit/' . $guarantee['guarantee_id']); ?>" 
                                           class="btn btn-sm btn-outline-warning" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($guarantee['status'] == 'ใช้งาน' && !$is_expired): ?>
                                            <a href="<?php echo base_url('guarantees/renew/' . $guarantee['guarantee_id']); ?>" 
                                               class="btn btn-sm btn-outline-info" title="ต่ออายุ">
                                                <i class="fas fa-redo"></i>
                                            </a>
                                            <a href="<?php echo base_url('guarantees/claim/' . $guarantee['guarantee_id']); ?>" 
                                               class="btn btn-sm btn-outline-success" title="เคลมประกัน">
                                                <i class="fas fa-hand-holding-usd"></i>
                                            </a>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                onclick="confirmDelete(<?php echo $guarantee['guarantee_id']; ?>)" title="ลบ">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-shield-alt fa-5x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลค้ำประกันสัญญา</h5>
                <p class="text-muted">
                    <?php if (!empty($search_keyword) || !empty($selected_status) || !empty($selected_vendor) || !empty($selected_expiry_filter)): ?>
                        ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?php echo base_url('guarantees'); ?>">ดูทั้งหมด</a>
                    <?php else: ?>
                        <a href="<?php echo base_url('guarantees/add'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> เพิ่มค้ำประกันใหม่
                        </a>
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    ยืนยันการลบ
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลค้ำประกันสัญญานี้?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    การดำเนินการนี้ไม่สามารถยกเลิกได้
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> ลบ
                </a>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    $('#guaranteesTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
        },
        "responsive": true,
        "pageLength": 25,
        "order": [[0, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": [9] },
            { "className": "text-center", "targets": [7, 8] }
        ]
    });
    
    // Guarantee status badges
    $('.guarantee-status').each(function() {
        var status = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-danger badge-warning');
        
        switch(status) {
            case 'ใช้งาน':
                $(this).addClass('badge badge-success');
                break;
            case 'หมดอายุ':
                $(this).addClass('badge badge-danger');
                break;
            case 'ยกเลิก':
                $(this).addClass('badge badge-secondary');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
    
    // Auto-submit form on filter change
    $('#status, #vendor, #expiry_filter').on('change', function() {
        $(this).closest('form').submit();
    });
    
    // Search on Enter key
    $('#search').on('keypress', function(e) {
        if (e.which === 13) {
            $(this).closest('form').submit();
        }
    });
});

function confirmDelete(guaranteeId) {
    $('#confirmDeleteBtn').attr('href', '<?php echo base_url('guarantees/delete/'); ?>' + guaranteeId);
    $('#deleteModal').modal('show');
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

.table-responsive {
    font-size: 0.875rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    background-color: #f8f9fc;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}
</style>

