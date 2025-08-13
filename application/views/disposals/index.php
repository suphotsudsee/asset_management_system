<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">การแทงจำหน่ายครุภัณฑ์</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">รายการครุภัณฑ์ที่จำหน่ายแล้ว</h6>
            <a href="<?php echo base_url("disposals/add"); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> แทงจำหน่ายครุภัณฑ์ใหม่
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="disposalsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>รหัสครุภัณฑ์</th>
                            <th>ชื่อครุภัณฑ์</th>
                            <th>วันที่จำหน่าย</th>
                            <th>วิธีการจำหน่าย</th>
                            <th>ราคาที่จำหน่ายได้</th>
                            <th>ผู้ดำเนินการ</th>
                            <th>เหตุผล</th>
                            <th>สถานะ</th>
                            <th>ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($disposals)): ?>
                            <?php foreach ($disposals as $disposal): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($disposal["asset_id"]); ?></td>
                                    <td><?php echo htmlspecialchars($disposal["asset_name"]); ?></td>
                                    <td><?php echo htmlspecialchars($disposal["disposal_date"]); ?></td>
                                    <td><?php echo htmlspecialchars($disposal["disposal_method"]); ?></td>
                                    <td><?php echo number_format($disposal["disposal_price"], 2); ?></td>
                                    <td><?php echo htmlspecialchars($disposal["disposal_by"]); ?></td>
                                    <td><?php echo htmlspecialchars($disposal["reason"]); ?></td>
                                    <td>
                                        <?php
                                            $status_class = "badge-secondary";
                                            switch ($disposal["status"]) {
                                                case "จำหน่ายแล้ว":
                                                    $status_class = "badge-success";
                                                    break;
                                                case "รอดำเนินการ":
                                                    $status_class = "badge-warning";
                                                    break;
                                                case "ยกเลิก":
                                                    $status_class = "badge-danger";
                                                    break;
                                            }
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>">
                                            <?php echo htmlspecialchars($disposal["status"]); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?php echo base_url("disposals/view/" . $disposal["disposal_id"]); ?>" class="btn btn-info btn-sm" title="ดูรายละเอียด">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo base_url("disposals/edit/" . $disposal["disposal_id"]); ?>" class="btn btn-warning btn-sm" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo base_url("disposals/delete/" . $disposal["disposal_id"]); ?>" class="btn btn-danger btn-sm" title="ลบ" onclick="return confirm(\'คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?\');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center">ไม่พบข้อมูลการจำหน่ายครุภัณฑ์</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Page level plugins -->
<script src="<?php echo base_url("assets/vendor/datatables/jquery.dataTables.min.js"); ?>"></script>
<script src="<?php echo base_url("assets/vendor/datatables/dataTables.bootstrap4.min.js"); ?>"></script>

<!-- Page level custom scripts -->
<script>
    $(document).ready(function() {
        // Check if DataTable is already initialized
        if ($.fn.DataTable.isDataTable("#disposalsTable")) {
            $("#disposalsTable").DataTable().destroy();
        }
        $("#disposalsTable").DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.21/i18n/Thai.json"
            },
            "order": [[ 2, "desc" ]]
        });
    });
</script>