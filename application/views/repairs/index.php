<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tools"></i>
        รายการซ่อมแซมครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('repairs/add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> ขออนุญาตซ่อมแซม
            </a>
            <a href="<?php echo base_url('repairs/export'); ?>" class="btn btn-success">
                <i class="fas fa-download"></i> ส่งออก CSV
            </a>
            <a href="<?php echo base_url('repairs/report'); ?>" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> รายงาน
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('repairs'); ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">ค้นหา</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="ชื่อครุภัณฑ์, ผู้แจ้ง, รายละเอียดปัญหา..." 
                           value="<?php echo htmlspecialchars($search_keyword ?? ''); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status">สถานะ</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">ทั้งหมด</option>
                        <option value="รอพิจารณา" <?php echo ($selected_status == 'รอพิจารณา') ? 'selected' : ''; ?>>รอพิจารณา</option>
                        <option value="อนุมัติ" <?php echo ($selected_status == 'อนุมัติ') ? 'selected' : ''; ?>>อนุมัติ</option>
                        <option value="ไม่อนุมัติ" <?php echo ($selected_status == 'ไม่อนุมัติ') ? 'selected' : ''; ?>>ไม่อนุมัติ</option>
                        <option value="กำลังซ่อม" <?php echo ($selected_status == 'กำลังซ่อม') ? 'selected' : ''; ?>>กำลังซ่อม</option>
                        <option value="เสร็จสิ้น" <?php echo ($selected_status == 'เสร็จสิ้น') ? 'selected' : ''; ?>>เสร็จสิ้น</option>
                        <option value="ยกเลิก" <?php echo ($selected_status == 'ยกเลิก') ? 'selected' : ''; ?>>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="priority">ความสำคัญ</label>
                    <select class="form-control" id="priority" name="priority">
                        <option value="">ทั้งหมด</option>
                        <option value="สูง" <?php echo ($selected_priority == 'สูง') ? 'selected' : ''; ?>>สูง</option>
                        <option value="ปานกลาง" <?php echo ($selected_priority == 'ปานกลาง') ? 'selected' : ''; ?>>ปานกลาง</option>
                        <option value="ต่ำ" <?php echo ($selected_priority == 'ต่ำ') ? 'selected' : ''; ?>>ต่ำ</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_from">วันที่เริ่มต้น</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="<?php echo htmlspecialchars($date_from ?? ''); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_to">วันที่สิ้นสุด</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="<?php echo htmlspecialchars($date_to ?? ''); ?>">
                </div>
                <div class="col-md-1 mb-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="<?php echo base_url('repairs'); ?>" class="btn btn-secondary btn-block mt-1">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Repairs Table -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($repairs)): ?>
            <div class="table-responsive">
                <table class="table table-striped data-table" id="repairsTable">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ครุภัณฑ์</th>
                            <th>ปัญหา</th>
                            <th>ประเภท</th>
                            <th>ความสำคัญ</th>
                            <th>ผู้แจ้ง</th>
                            <th>วันที่แจ้ง</th>
                            <th>สถานะ</th>
                            <th>ค่าใช้จ่าย</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($repairs as $repair): ?>
                            <tr>
                                <td><?php echo $repair['repair_id']; ?></td>
                                <td>
                                    <a href="<?php echo base_url('assets/view/' . $repair['asset_id']); ?>" 
                                       class="text-decoration-none font-weight-bold">
                                        <?php echo htmlspecialchars($repair['asset_name']); ?>
                                    </a>
                                    <?php if (!empty($repair['serial_number'])): ?>
                                        <br><small class="text-muted">SN: <?php echo htmlspecialchars($repair['serial_number']); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
    <small>
        <?php 
            if (isset($repair['problem_description'])) {
                $desc = $repair['problem_description'];
                echo htmlspecialchars(substr($desc, 0, 50)) . (strlen($desc) > 50 ? '...' : '');
            } else {
                echo '<span style="color: gray;">ไม่มีข้อมูล</span>';
            }
        ?>
    </small>
</td>
                         <td>
    <span class="badge badge-secondary">
        <?php 
            echo isset($repair['repair_type']) 
                ? htmlspecialchars($repair['repair_type']) 
                : '<span style="color: #999;">ไม่ระบุประเภท</span>';
        ?>
    </span>
</td>
                                <td>
    <span class="priority-badge">
        <?= isset($repair['priority']) ? htmlspecialchars($repair['priority']) : '<span style="color: gray;">-</span>'; ?>
    </span>
</td>
                                <td>
                                    <small><?php echo htmlspecialchars($repair['requested_by']); ?></small>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($repair['request_date'])); ?></td>
                                <td>
                                    <span class="repair-status"><?php echo $repair['status']; ?></span>
                                </td>
                                <td>
    <?php
        $actual  = isset($repair['actual_cost']) ? (float)$repair['actual_cost'] : 0;
        $estimate = isset($repair['estimated_cost']) ? (float)$repair['estimated_cost'] : 0;
    ?>

    <?php if ($actual > 0): ?>
        <strong><?= number_format($actual, 2); ?></strong>
        <br><small class="text-muted">จริง</small>
    <?php elseif ($estimate > 0): ?>
        <?= number_format($estimate, 2); ?>
        <br><small class="text-muted">ประมาณ</small>
    <?php else: ?>
        <span class="text-muted">-</span>
    <?php endif; ?>
</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo base_url('repairs/view/' . $repair['repair_id']); ?>" 
                                           class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($repair['status'] == 'รอพิจารณา'): ?>
                                            <a href="<?php echo base_url('repairs/approve/' . $repair['repair_id']); ?>" 
                                               class="btn btn-sm btn-success btn-approve" title="อนุมัติ"
                                               data-item="การซ่อมแซม #<?php echo $repair['repair_id']; ?>">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?php echo base_url('repairs/reject/' . $repair['repair_id']); ?>" 
                                               class="btn btn-sm btn-warning btn-reject" title="ไม่อนุมัติ"
                                               data-item="การซ่อมแซม #<?php echo $repair['repair_id']; ?>">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo base_url('repairs/print_request/' . $repair['repair_id']); ?>" 
                                           class="btn btn-sm btn-secondary" title="พิมพ์หนังสือ" target="_blank">
                                            <i class="fas fa-print"></i>
                                        </a>
                                        
                                        <a href="<?php echo base_url('repairs/edit/' . $repair['repair_id']); ?>" 
                                           class="btn btn-sm btn-warning" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <a href="<?php echo base_url('repairs/delete/' . $repair['repair_id']); ?>" 
                                           class="btn btn-sm btn-danger btn-delete" title="ลบ"
                                           data-item="การซ่อมแซม #<?php echo $repair['repair_id']; ?>">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Summary -->
            <div class="row mt-3">
                <div class="col-md-12">
                    <p class="text-muted">
                        แสดง <?php echo number_format(count($repairs)); ?> รายการ
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-tools fa-5x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลการซ่อมแซม</h5>
                <p class="text-muted">
                    <?php if (!empty($search_keyword) || !empty($selected_status) || !empty($selected_priority) || !empty($date_from) || !empty($date_to)): ?>
                        ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?php echo base_url('repairs'); ?>">ดูทั้งหมด</a>
                    <?php else: ?>
                        เริ่มต้นด้วยการขออนุญาตซ่อมแซมครุภัณฑ์
                    <?php endif; ?>
                </p>
                <a href="<?php echo base_url('repairs/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> ขออนุญาตซ่อมแซม
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with custom settings
    $('#repairsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
        },
        "responsive": true,
        "pageLength": 25,
        "order": [[0, "desc"]],
        "columnDefs": [
            { "orderable": false, "targets": [9] } // Disable sorting for action column
        ]
    });
    
    // Priority badges
    $('.priority-badge').each(function() {
        var priority = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-danger badge-warning');
        
        switch(priority) {
            case 'สูง':
                $(this).addClass('badge badge-danger');
                break;
            case 'ปานกลาง':
                $(this).addClass('badge badge-warning');
                break;
            case 'ต่ำ':
                $(this).addClass('badge badge-success');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
    
    // Repair status badges
    $('.repair-status').each(function() {
        var status = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-danger badge-warning badge-info badge-primary');
        
        switch(status) {
            case 'เสร็จสิ้น':
                $(this).addClass('badge badge-success');
                break;
            case 'รอพิจารณา':
                $(this).addClass('badge badge-warning');
                break;
            case 'อนุมัติ':
                $(this).addClass('badge badge-info');
                break;
            case 'กำลังซ่อม':
                $(this).addClass('badge badge-primary');
                break;
            case 'ไม่อนุมัติ':
            case 'ยกเลิก':
                $(this).addClass('badge badge-danger');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
    
    // Confirmation for approve/reject actions
    $('.btn-approve').on('click', function(e) {
        e.preventDefault();
        var item = $(this).data('item');
        var url = $(this).attr('href');
        
        if (confirm('คุณแน่ใจหรือไม่ที่จะอนุมัติ ' + item + '?')) {
            window.location.href = url;
        }
    });
    
    $('.btn-reject').on('click', function(e) {
        e.preventDefault();
        var item = $(this).data('item');
        var url = $(this).attr('href');
        
        if (confirm('คุณแน่ใจหรือไม่ที่จะไม่อนุมัติ ' + item + '?')) {
            window.location.href = url;
        }
    });
    
    // Status update via AJAX
    $('.status-select').on('change', function() {
        var repairId = $(this).data('repair-id');
        var newStatus = $(this).val();
        var originalStatus = $(this).data('original-status');
        
        if (confirm('คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะ?')) {
            $.ajax({
                url: '<?php echo base_url('repairs/api_update_status'); ?>',
                method: 'POST',
                data: {
                    repair_id: repairId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        // Update the status badge
                        var badge = $('.repair-status[data-repair-id="' + repairId + '"]');
                        badge.text(newStatus);
                        badge.removeClass('badge-success badge-danger badge-warning badge-secondary badge-info badge-primary');
                        
                        switch(newStatus) {
                            case 'เสร็จสิ้น':
                                badge.addClass('badge badge-success');
                                break;
                            case 'รอพิจารณา':
                                badge.addClass('badge badge-warning');
                                break;
                            case 'อนุมัติ':
                                badge.addClass('badge badge-info');
                                break;
                            case 'กำลังซ่อม':
                                badge.addClass('badge badge-primary');
                                break;
                            case 'ไม่อนุมัติ':
                            case 'ยกเลิก':
                                badge.addClass('badge badge-danger');
                                break;
                            default:
                                badge.addClass('badge badge-secondary');
                        }
                    } else {
                        showAlert('danger', response.message);
                        // Revert the select value
                        $(this).val(originalStatus);
                    }
                },
                error: function() {
                    showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
                    // Revert the select value
                    $(this).val(originalStatus);
                }
            });
        } else {
            // Revert the select value
            $(this).val(originalStatus);
        }
    });
});
</script>

