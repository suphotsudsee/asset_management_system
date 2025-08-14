<?php
// application/views/guarantees/expiring.php
$days = isset($selected_days) ? (int)$selected_days : 30;
$rows = isset($expiring_guarantees) ? $expiring_guarantees : [];
?>
<div class="container-fluid mt-3">
  <h4 class="mb-3">ค้ำประกันที่ใกล้หมดอายุ (ภายใน <?= $days ?> วัน)</h4>

  <form class="form-inline mb-3" method="get" action="<?= site_url('guarantees/expiring'); ?>">
    <label class="mr-2">ภายใน</label>
    <input type="number" name="days" min="1" class="form-control mr-2" value="<?= $days ?>">
    <label class="mr-2">วัน</label>
    <button type="submit" class="btn btn-primary">กรอง</button>
    <a href="<?= site_url('guarantees'); ?>" class="btn btn-light ml-2">กลับหน้ารายการ</a>
  </form>

  <div class="card">
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover" id="expiringTable">
        <thead class="thead-light">
          <tr>
            <th>#</th>
            <th>ครุภัณฑ์</th>
            <th>Serial</th>
            <th>ผู้จำหน่าย/ผู้ให้บริการ</th>
            <th>เลขที่สัญญา</th>
            <th>เริ่ม</th>
            <th>สิ้นสุด</th>
            <th class="text-right">วันคงเหลือ</th>
            <th>สถานะ</th>
            <th width="120">จัดการ</th>
          </tr>
        </thead>
        <tbody>
        <?php
        $today = new DateTime('today');
        foreach ($rows as $r):
          $end  = !empty($r['end_date']) ? new DateTime($r['end_date']) : $today;
          $diff = (int)$today->diff($end)->format('%r%a');
          $left = max(0, $diff);
        ?>
          <tr>
            <td><?= (int)$r['guarantee_id'] ?></td>
            <td><?= html_escape($r['asset_name'] ?? '-') ?></td>
            <td><?= html_escape($r['serial_number'] ?? '-') ?></td>
            <td><?= html_escape($r['vendor_name'] ?? ($r['guarantee_provider'] ?? '-')) ?></td>
            <td><?= html_escape($r['contract_number'] ?? '-') ?></td>
            <td><?= html_escape($r['start_date'] ?? '-') ?></td>
            <td><?= html_escape($r['end_date'] ?? '-') ?></td>
            <td class="text-right"><?= number_format($left) ?></td>
            <td><span class="badge badge-<?= ($left > 0 ? 'warning' : 'secondary') ?>">
              <?= html_escape($r['status'] ?? '-') ?></span>
            </td>
            <td>
              <a href="<?= site_url('guarantees/view/'.(int)$r['guarantee_id']); ?>" class="btn btn-sm btn-info">
                <i class="fa fa-search"></i>
              </a>
              <a href="<?= site_url('guarantees/edit/'.(int)$r['guarantee_id']); ?>" class="btn btn-sm btn-warning">
                <i class="fa fa-edit"></i>
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
$(function () {
  if ($.fn.DataTable) {
    $('#expiringTable').DataTable({
      pageLength: 25,
      order: [[7, 'asc']], // วันคงเหลือจากน้อยไปมาก
      language: { url: "//cdn.datatables.net/plug-ins/1.13.1/i18n/th.json" }
    });
  }
});
</script>
