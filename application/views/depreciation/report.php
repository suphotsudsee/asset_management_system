<div class="container-fluid">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">
            <i class="fas fa-coins text-warning"></i> รายงานค่าเสื่อมราคาครุภัณฑ์
        </h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
                <a href="<?= base_url('depreciation/report?year=' . $year . '&month=' . $month . '&export=excel') ?>"
                   class="btn btn-success btn-sm">
                    <i class="fas fa-file-excel"></i> ส่งออก Excel
                </a>
            </div>
        </div>
    </div>
    <form class="form-row mb-3" method="get" action="<?= base_url('depreciation/report') ?>">
        <div class="col-md-2 mb-1">
            <select name="year" class="form-control">
                <?php foreach ($years as $y): ?>
                    <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>>ปี <?= $y+543 ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 mb-1">
            <select name="month" class="form-control">
                <option value="">ทุกเดือน</option>
                <?php foreach ($months as $m => $mn): ?>
                    <option value="<?= $m ?>" <?= $month == $m ? 'selected' : '' ?>><?= $mn ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2 mb-1">
            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i> ค้นหา</button>
        </div>
    </form>

    <?php if (isset($summary) && !empty($summary)): ?>
        <div class="alert alert-info mb-2 py-2" role="alert" style="font-size:1rem;">
            <b>สรุป:</b>
            รวมครุภัณฑ์ <?= number_format(array_sum(array_column($summary, 'asset_count'))) ?> รายการ,
            ค่าเสื่อมรอบนี้ <?= number_format(array_sum(array_column($summary, 'total_depreciation')),2) ?> บาท,
            มูลค่าคงเหลือรวม <?= number_format(array_sum(array_column($summary, 'total_book_value')),2) ?> บาท
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover" id="depreciationTable">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>ชื่อครุภัณฑ์</th>
                    <th>รหัส/หมายเลข</th>
                    <th>ประเภท</th>
                    <th>วันที่ซื้อ</th>
                    <th>ราคาซื้อ</th>
                    <th>อัตราเสื่อม (%)</th>
                    <th>วันที่บันทึก</th>
                    <th>ค่าเสื่อมรอบนี้</th>
                    <th>ค่าเสื่อมสะสม</th>
                    <th>มูลค่าคงเหลือ</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($records as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($row['asset_name']) ?></td>
                    <td><?= htmlspecialchars($row['serial_number']) ?></td>
                    <td><?= htmlspecialchars($row['asset_type']) ?></td>
                    <td><?= date('d/m/Y', strtotime($row['purchase_date'])) ?></td>
                    <td class="text-right"><?= number_format($row['purchase_price'], 2) ?></td>
                    <td class="text-center"><?= number_format($row['depreciation_rate'], 2) ?></td>
                    <td><?= date('d/m/Y', strtotime($row['record_date'])) ?></td>
                    <td class="text-right text-primary"><?= number_format($row['depreciation_amount'], 2) ?></td>
                    <td class="text-right"><?= number_format($row['accumulated_depreciation'], 2) ?></td>
                    <td class="text-right font-weight-bold"><?= number_format($row['book_value'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($records)): ?>
                <tr><td colspan="11" class="text-center text-muted">-- ไม่พบข้อมูล --</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables (ถ้าใช้งาน DataTables กับตารางอื่นๆ อยู่แล้ว) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
<script>
$(document).ready(function(){
    $('#depreciationTable').DataTable({
        "pageLength": 25,
        "ordering": true,
        "language": {
            "search": "ค้นหา:",
            "lengthMenu": "แสดง _MENU_ รายการ",
            "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
            "paginate": {
                "first":      "หน้าแรก",
                "last":       "หน้าสุดท้าย",
                "next":       "ถัดไป",
                "previous":   "ก่อนหน้า"
            },
            "zeroRecords": "ไม่พบข้อมูลที่ค้นหา"
        }
    });
});
</script>
