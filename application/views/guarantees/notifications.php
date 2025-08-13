<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-bell"></i>
        การแจ้งเตือนค้ำประกันใกล้หมดอายุ
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url("guarantees"); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> กลับ
        </a>
    </div>
</div>

<?php if (empty($expiring_guarantees)): ?>
    <div class="alert alert-info text-center" role="alert">
        <i class="fas fa-check-circle"></i>
        ไม่มีค้ำประกันใดๆ ที่ใกล้หมดอายุในขณะนี้
    </div>
<?php else: ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list"></i>
                รายการค้ำประกันที่ใกล้หมดอายุ
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>รหัส</th>
                            <th>ครุภัณฑ์</th>
                            <th>ประเภท</th>
                            <th>ผู้จำหน่าย</th>
                            <th>วันที่สิ้นสุด</th>
                            <th>วันคงเหลือ</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($expiring_guarantees as $guarantee): ?>
                            <?php 
                            $days_remaining = ceil((strtotime($guarantee["end_date"]) - time()) / (60 * 60 * 24));
                            $row_class = "";
                            if ($days_remaining <= 7) {
                                $row_class = "table-danger"; // หมดอายุใน 7 วัน
                            } elseif ($days_remaining <= 30) {
                                $row_class = "table-warning"; // หมดอายุใน 30 วัน
                            }
                            ?>
                            <tr class="<?php echo $row_class; ?>">
                                <td><?php echo htmlspecialchars($guarantee["guarantee_id"]); ?></td>
                                <td>
                                    <a href="<?php echo base_url("assets/view/" . $guarantee["asset_id"]); ?>">
                                        <?php echo htmlspecialchars($guarantee["asset_code"] . " - " . $guarantee["asset_name"]); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($guarantee["guarantee_type"]); ?></td>
                                <td><?php echo htmlspecialchars($guarantee["vendor_name"]); ?></td>
                                <td><?php echo date("d/m/Y", strtotime($guarantee["end_date"])); ?></td>
                                <td>
                                    <?php if ($days_remaining <= 0): ?>
                                        <span class="badge badge-danger">หมดอายุแล้ว</span>
                                    <?php else: ?>
                                        <span class="badge badge-<?php echo ($days_remaining <= 30) ? "warning" : "success"; ?>">
                                            <?php echo $days_remaining; ?> วัน
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="guarantee-status"><?php echo htmlspecialchars($guarantee["status"]); ?></span>
                                </td>
                                <td>
                                    <a href="<?php echo base_url("guarantees/view/" . $guarantee["guarantee_id"]); ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> ดู
                                    </a>
                                    <a href="<?php echo base_url("guarantees/renew/" . $guarantee["guarantee_id"]); ?>" class="btn btn-success btn-sm">
                                        <i class="fas fa-redo"></i> ต่ออายุ
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
$(document).ready(function() {
    $("#dataTable").DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
        },
        "order": [[5, "asc"]] // Order by days remaining
    });

    // Guarantee status badges
    $(".guarantee-status").each(function() {
        var status = $(this).text().trim();
        $(this).removeClass("badge-secondary badge-success badge-danger badge-warning");
        
        switch(status) {
            case "ใช้งาน":
                $(this).addClass("badge badge-success");
                break;
            case "หมดอายุ":
                $(this).addClass("badge badge-danger");
                break;
            case "ยกเลิก":
                $(this).addClass("badge badge-secondary");
                break;
            default:
                $(this).addClass("badge badge-secondary");
        }
    });
});
</script>

<style>
.table-danger td {
    background-color: #f8d7da !important;
}
.table-warning td {
    background-color: #fff3cd !important;
}
</style>

