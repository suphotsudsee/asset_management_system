<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-exchange-alt"></i>
        รายการโอนย้ายครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url("transfers/add"); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> โอนย้ายครุภัณฑ์
            </a>
            <a href="<?php echo base_url("transfers/export"); ?>" class="btn btn-success">
                <i class="fas fa-download"></i> ส่งออก CSV
            </a>
            <a href="<?php echo base_url("transfers/report"); ?>" class="btn btn-info">
                <i class="fas fa-chart-bar"></i> รายงาน
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo base_url("transfers"); ?>">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="search">ค้นหา</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           placeholder="ชื่อครุภัณฑ์, ผู้ดำเนินการ, สถานที่..." 
                           value="<?php echo htmlspecialchars($search_keyword ?? ""); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="status">สถานะ</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">ทั้งหมด</option>
                        <option value="รอดำเนินการ" <?php echo ($selected_status == "รอดำเนินการ") ? "selected" : ""; ?>>รอดำเนินการ</option>
                        <option value="กำลังดำเนินการ" <?php echo ($selected_status == "กำลังดำเนินการ") ? "selected" : ""; ?>>กำลังดำเนินการ</option>
                        <option value="เสร็จสิ้น" <?php echo ($selected_status == "เสร็จสิ้น") ? "selected" : ""; ?>>เสร็จสิ้น</option>
                        <option value="ยกเลิก" <?php echo ($selected_status == "ยกเลิก") ? "selected" : ""; ?>>ยกเลิก</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_from">วันที่เริ่มต้น</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" 
                           value="<?php echo htmlspecialchars($date_from ?? ""); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="date_to">วันที่สิ้นสุด</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" 
                           value="<?php echo htmlspecialchars($date_to ?? ""); ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label>&nbsp;</label>
                    <div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-search"></i> ค้นหา
                        </button>
                        <a href="<?php echo base_url("transfers"); ?>" class="btn btn-secondary btn-block mt-1">
                            <i class="fas fa-times"></i> ล้าง
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Transfers Table -->
<div class="card">
    <div class="card-body">
        <?php if (!empty($transfers)): ?>
            <div class="table-responsive">
                <table class="table table-striped data-table" id="transfersTable">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ครุภัณฑ์</th>
                            <th>จากสถานที่</th>
                            <th>ไปสถานที่</th>
                            <th>วันที่โอนย้าย</th>
                            <th>ผู้ดำเนินการ</th>
                            <th>เหตุผล</th>
                            <th>สถานะ</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transfers as $transfer): ?>
                            <tr>
                                <td><?php echo $transfer["transfer_id"]; ?></td>
                                <td>
                                    <a href="<?php echo base_url("assets/view/" . $transfer["asset_id"]); ?>" 
                                       class="text-decoration-none font-weight-bold">
                                        <?php echo htmlspecialchars($transfer["asset_name"]); ?>
                                    </a>
                                    <?php if (!empty($transfer["serial_number"])): ?>
                                        <br><small class="text-muted">SN: <?php echo htmlspecialchars($transfer["serial_number"]); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo htmlspecialchars($transfer["from_location"]); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-primary">
                                        <?php echo htmlspecialchars($transfer["to_location"]); ?>
                                    </span>
                                </td>
                                <td><?php echo date("d/m/Y", strtotime($transfer["transfer_date"])); ?></td>
                                <td>
                                    <?php echo $transfer["from_location"]; ?>
                                    <small><?php echo htmlspecialchars($transfer["transfer_by"]); ?></small>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars(substr($transfer["reason"], 0, 30)) . (strlen($transfer["reason"]) > 30 ? "..." : ""); ?></small>
                                </td>
                                <td>
                                    <span class="transfer-status"><?php echo $transfer["asset_status"]; ?></span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo base_url("transfers/view/" . $transfer["transfer_id"]); ?>" 
                                           class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url("transfers/edit/" . $transfer["transfer_id"]); ?>" 
                                           class="btn btn-sm btn-warning" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url("transfers/delete/" . $transfer["transfer_id"]); ?>" 
                                           class="btn btn-sm btn-danger btn-delete" title="ลบ"
                                           data-item="การโอนย้าย #<?php echo $transfer["transfer_id"]; ?>">
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
                        แสดง <?php echo number_format(count($transfers)); ?> รายการ
                    </p>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-exchange-alt fa-5x text-muted mb-3"></i>
                <h5 class="text-muted">ไม่พบข้อมูลการโอนย้าย</h5>
                <p class="text-muted">
                    <?php if (!empty($search_keyword) || !empty($selected_status) || !empty($date_from) || !empty($date_to)): ?>
                        ลองปรับเปลี่ยนเงื่อนไขการค้นหา หรือ
                        <a href="<?php echo base_url("transfers"); ?>">ดูทั้งหมด</a>
                    <?php else: ?>
                        เริ่มต้นด้วยการโอนย้ายครุภัณฑ์
                    <?php endif; ?>
                </p>
                <a href="<?php echo base_url("transfers/add"); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> โอนย้ายครุภัณฑ์
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable with custom settings
    if ( ! $.fn.DataTable.isDataTable( "#transfersTable" ) ) {
        $("#transfersTable").DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
            },
            "responsive": true,
            "pageLength": 25,
            "order": [[0, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": [8] } // Disable sorting for action column
            ]
        });
    }
    
    // Transfer status badges
    $(".transfer-status").each(function() {
        var status = $(this).text().trim();
        $(this).removeClass("badge-secondary badge-success badge-danger badge-warning badge-info");
        
        switch(status) {
            case "เสร็จสิ้น":
                $(this).addClass("badge badge-success");
                break;
            case "รอดำเนินการ":
                $(this).addClass("badge badge-warning");
                break;
            case "กำลังดำเนินการ":
                $(this).addClass("badge badge-info");
                break;
            case "ยกเลิก":
                $(this).addClass("badge badge-danger");
                break;
            default:
                $(this).addClass("badge badge-secondary");
        }
    });
    
    // Status update via AJAX
    $(".status-select").on("change", function() {
        var transferId = $(this).data("transfer-id");
        var newStatus = $(this).val();
        var originalStatus = $(this).data("original-status");
        
        if (confirm("คุณแน่ใจหรือไม่ที่จะเปลี่ยนสถานะ?")) {
            $.ajax({
                url: "<?php echo base_url("transfers/api_update_status"); ?>",
                method: "POST",
                data: {
                    transfer_id: transferId,
                    status: newStatus
                },
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        showAlert("success", response.message);
                        // Update the status badge
                        var badge = $(".transfer-status[data-transfer-id=\"" + transferId + "\"]");
                        badge.text(newStatus);
                        badge.removeClass("badge-success badge-danger badge-warning badge-secondary badge-info");
                        
                        switch(newStatus) {
                            case "เสร็จสิ้น":
                                badge.addClass("badge badge-success");
                                break;
                            case "รอดำเนินการ":
                                badge.addClass("badge badge-warning");
                                break;
                            case "กำลังดำเนินการ":
                                badge.addClass("badge badge-info");
                                break;
                            case "ยกเลิก":
                                badge.addClass("badge badge-danger");
                                break;
                            default:
                                badge.addClass("badge badge-secondary");
                        }
                    } else {
                        showAlert("danger", response.message);
                        // Revert the select value
                        $(this).val(originalStatus);
                    }
                },
                error: function() {
                    showAlert("danger", "เกิดข้อผิดพลาดในการเชื่อมต่อ");
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



