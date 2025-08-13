<!-- templates/header.php ถูกโหลดแล้ว -->
<div class="container-fluid mt-3">
    <h4 class="mb-3">บันทึกค่าเสื่อมราคา</h4>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= $this->session->flashdata('success'); ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php endif; ?>

    <a href="<?= site_url('depreciations/create'); ?>" class="btn btn-primary mb-3">
        <i class="fa fa-plus"></i> เพิ่มข้อมูล
    </a>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="depreciationTable">
                <thead class="thead-light">
                    <tr>
                        <th>#</th>
                        <th>ครุภัณฑ์</th>
                        <th>Serial</th>
                        <th>วันที่บันทึก</th>
                        <th>ค่าเสื่อมราคา</th>
                        <th>ค่าเสื่อมสะสม</th>
                        <th>มูลค่าตามบัญชี</th>
                        <th width="120">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($records as $row): ?>
                        <tr>
                            <td><?= $row->depreciation_record_id; ?></td>
                            <td><?= $row->asset_name; ?></td>
                            <td><?= $row->serial_number; ?></td>
                            <td><?= $row->record_date; ?></td>
                            <td class="text-right"><?= number_format($row->depreciation_amount,2); ?></td>
                            <td class="text-right"><?= number_format($row->accumulated_depreciation,2); ?></td>
                            <td class="text-right"><?= number_format($row->book_value,2); ?></td>
                            <td>
                                <a href="<?= site_url('depreciations/edit/'.$row->depreciation_record_id); ?>" 
                                   class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="<?= site_url('depreciations/delete/'.$row->depreciation_record_id); ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('ยืนยันการลบ?')">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- DataTables init (เหมือน transfers) -->
<script>
$(document).ready(function () {
    $('#depreciationTable').DataTable({
        "ordering": true,
        "pageLength": 25,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.1/i18n/th.json"
        }
    });
});
</script>
<!-- templates/footer.php ถูกโหลดแล้ว -->
