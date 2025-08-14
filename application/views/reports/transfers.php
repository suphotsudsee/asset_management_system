<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container my-3">
  <h2><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'รายงานการโอนย้ายครุภัณฑ์'; ?></h2>

  <form method="get" action="<?= site_url('reports/transfers'); ?>" class="row g-2 mb-3">
    <div class="col-md-2">
      <label class="form-label">ปี</label>
      <input type="number" name="year" value="<?= htmlspecialchars($selected_year ?? date('Y'), ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
    </div>
    <div class="col-md-2">
      <label class="form-label">เดือน</label>
      <select name="month" class="form-select">
        <option value="">ทั้งปี</option>
        <?php for ($m = 1; $m <= 12; $m++): $sel = (string)($selected_month ?? '') === (string)$m ? 'selected' : ''; ?>
          <option value="<?= $m; ?>" <?= $sel; ?>><?= $m; ?></option>
        <?php endfor; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">จากสถานที่</label>
      <select name="from_location" class="form-select">
        <option value="">ทั้งหมด</option>
        <?php if (!empty($locations)) foreach ($locations as $loc): ?>
          <option value="<?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_from_location) && $selected_from_location === $loc) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">ไปสถานที่</label>
      <select name="to_location" class="form-select">
        <option value="">ทั้งหมด</option>
        <?php if (!empty($locations)) foreach ($locations as $loc): ?>
          <option value="<?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_to_location) && $selected_to_location === $loc) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 d-flex justify-content-end mt-2">
      <button class="btn btn-primary" type="submit">กรอง</button>
    </div>
  </form>

  <?php if (!empty($transfer_stats)): ?>
    <div class="row mb-3">
      <div class="col-md-4">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">จำนวนการโอนย้ายทั้งหมด</h5>
            <p class="card-text display-6 mb-0"><?= (int)($transfer_stats['total'] ?? 0); ?></p>
          </div>
        </div>
      </div>
      <div class="col-md-8">
        <h5 class="mb-2">ปลายทางยอดนิยม</h5>
        <ul class="list-group">
          <?php if (!empty($transfer_stats['top_destinations'])) foreach ($transfer_stats['top_destinations'] as $d): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <?= htmlspecialchars($d['to_location'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
              <span class="badge bg-secondary"><?= (int)($d['count'] ?? 0); ?></span>
            </li>
          <?php endforeach; ?>
          <?php if (empty($transfer_stats['top_destinations'])): ?>
            <li class="list-group-item text-center">ไม่มีข้อมูล</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  <?php endif; ?>

  <h5>รายการการโอนย้าย</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>วันที่โอนย้าย</th>
          <th>ชื่อครุภัณฑ์</th>
          <th>Serial</th>
          <th>จากสถานที่</th>
          <th>ไปสถานที่</th>
          <th>ผู้ดำเนินการ</th>
          <th>เหตุผล</th>
          <th>สถานะ</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($transfers)) foreach ($transfers as $t): ?>
          <tr>
            <td><?= htmlspecialchars($t['transfer_date'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['asset_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['serial_number'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['from_location'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['to_location'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['transferred_by'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['reason'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($t['status'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($transfers)): ?>
          <tr><td colspan="8" class="text-center">ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


