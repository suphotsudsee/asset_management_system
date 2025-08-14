<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-boxes"></i>
        รายการครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('assetmanager/add'); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> เพิ่มครุภัณฑ์ใหม่
            </a>
            <a href="<?php echo base_url('assetmanager/export'); ?>" class="btn btn-success">
                <i class="fas fa-download"></i> ส่งออก CSV
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo base_url('assetmanager'); ?>">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="search">ค้นหา</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="ชื่อครุภัณฑ์, หมายเลขซีเรียล..." 
                           value="<?php echo htmlspecialchars($search_keyword ?? ''); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="type">ประเภท</label>
                    <select class="form-control" id="type" name="type">
                        <option value="">ทั้งหมด</option>
                        <?php if (!empty($asset_types)): ?>
                            <?php foreach ($asset_types as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>" 
                                        <?php echo ($selected_type == $type) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?>
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
                        <option value="ชำรุด" <?php echo ($selected_status == 'ชำรุด') ? 'selected' : ''; ?>>ชำรุด</option>
                        <option value="ซ่อมแซม" <?php echo ($selected_status == 'ซ่อมแซม') ? 'selected' : ''; ?>>ซ่อมแซม</option>
                        <option value="จำหน่ายแล้ว" <?php echo ($selected_status == 'จำหน่ายแล้ว') ? 'selected' : ''; ?>>จำหน่ายแล้ว</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="location">สถานที่</label>
                    <select class="form-control" id="location" name="location">
                        <option value="">ทั้งหมด</option>
                        <?php if (!empty($locations)): ?>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?php echo htmlspecialchars($location); ?>" 
                                        <?php echo ($selected_location == $location) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($location); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> ค้นหา
                        </button>
                        <a href="<?php echo base_url('assets'); ?>" class="btn btn-secondary">
                            <i class="fas fa-times"></i> ล้าง
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Assets Table -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($assets)): ?>
            <div class="table-responsive">
                <table class="table table-striped data-table" id="assetsTable">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ชื่อครุภัณฑ์</th>
                            <th>ประเภท</th>
                            <th>หมายเลขซีเรียล</th>
                            <th>วันที่จัดซื้อ</th>
                            <th>ราคา</th>
                            <th>สถานที่</th>
                            <th>สถานะ</th>
                            <th>ผู้รับผิดชอบ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($assets as $asset): ?>
                            <tr>
                                <td><?php echo $asset['asset_id']; ?></td>
                                <td>
                                    <a href="<?php echo base_url('assetmanager/view/' . $asset['asset_id']); ?>" 
                                       class="text-decoration-none font-weight-bold">
                                        <?php echo htmlspecialchars($asset['asset_name']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($asset['asset_type']); ?></td>
                                <td>
                                    <code><?php echo htmlspecialchars($asset['serial_number'] ?: '-'); ?></code>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($asset['purchase_date'])); ?></td>
                                <td>
                                ฿<?php echo number_format((float)$asset['purchase_price'], 2); ?>
                                    
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($asset['current_location']); ?></small>
                                </td>
                                <td>
                                    <span class="asset-status"><?php echo $asset['status']; ?></span>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars($asset['responsible_person']); ?></small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo base_url('assetmanager/view/' . $asset['asset_id']); ?>" 
                                           class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url('assetmanager/edit/' . $asset['asset_id']); ?>" 
                                           class="btn btn-sm btn-warning" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($asset['status'] != 'จำหน่ายแล้ว'): ?>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-secondary dropdown-toggle" 
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-cog"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" href="<?php echo base_url('transfers/add?asset_id=' . $asset['asset_id']); ?>">
                                                        <i class="fas fa-exchange-alt"></i> โอนย้าย
                                                    </a>
                                                    <a class="dropdown-item" href="<?php echo base_url('repairs/add?asset_id=' . $asset['asset_id']); ?>">
                                                        <i class="fas fa-tools"></i> แจ้งซ่อม
                                                    </a>
                                                    <a class="dropdown-item" href="<?php echo base_url('disposals/add?asset_id=' . $asset['asset_id']); ?>">
                                                        <i class="fas fa-trash-alt"></i> จำหน่าย
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item text-danger btn-delete" 
                                                       href="<?php echo base_url('assetmanager/delete/' . $asset['asset_id']); ?>"
                                                       data-item="<?php echo htmlspecialchars($asset['asset_name']); ?>">
                                                        <i class="fas fa-trash"></i> ลบ
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Summary -->
            <div class="row mt-3">
                <div class="col-md-6">
                    <p class="text-muted">
                        แสดง <?php echo number_format(count($assets)); ?> รายการ
                    </p>
                </div>
                <div class="col-md-6 text-right">
                    <p class="text-muted">
                        มูลค่ารวม: 
                        <span class="currency font-weight-bold">
                            <?php 
                            $total_value = array_sum(array_column($assets, 'purchase_price'));
                            echo number_format($total_value, 2); 
                            ?>
                        </span>
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-5x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลครุภัณฑ์</h5>
                <p class="text-muted">
                    <?php if (!empty($search_keyword) || !empty($selected_type) || !empty($selected_status) || !empty($selected_location)): ?>
                        ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?php echo base_url('assets'); ?>">ดูทั้งหมด</a>
                    <?php else: ?>
                        เริ่มต้นด้วยการเพิ่มครุภัณฑ์ใหม่
                    <?php endif; ?>
                </p>
                <a href="<?php echo base_url('assets/add'); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> เพิ่มครุภัณฑ์ใหม่
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with custom settings
    $('#assetsTable').DataTable({
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
    
    // Status update via AJAX
    $('.status-select').on('change', function() {
        var assetId = $(this).data('asset-id');
        var newStatus = $(this).val();
        var originalStatus = $(this).data('original-status');
        
        if (confirm('คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะ?')) {
            $.ajax({
                url: '<?php echo base_url('assetmanager/api_update_status'); ?>',
                method: 'POST',
                data: {
                    asset_id: assetId,
                    status: newStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        // Update the status badge
                        var badge = $('.asset-status[data-asset-id="' + assetId + '"]');
                        badge.text(newStatus);
                        badge.removeClass('badge-success badge-danger badge-warning badge-secondary badge-info');
                        
                        switch(newStatus) {
                            case 'ใช้งาน':
                                badge.addClass('badge badge-success');
                                break;
                            case 'ชำรุด':
                                badge.addClass('badge badge-danger');
                                break;
                            case 'จำหน่ายแล้ว':
                                badge.addClass('badge badge-secondary');
                                break;
                            case 'ซ่อมแซม':
                                badge.addClass('badge badge-warning');
                                break;
                            default:
                                badge.addClass('badge badge-info');
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

