<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container my-3">
  <h2><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'รายงานสถานะครุภัณฑ์'; ?></h2>

  <form method="get" action="<?= site_url('reports/asset_status'); ?>" class="row g-2 mb-3">
    <div class="col-md-2">
      <label class="form-label">ปี</label>
      <input type="number" name="year" value="<?= htmlspecialchars($selected_year ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">สถานที่</label>
      <select name="location" class="form-select">
        <option value="">ทั้งหมด</option>
        <?php if (!empty($locations)) foreach ($locations as $loc): ?>
          <option value="<?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_location) && $selected_location === $loc) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($loc, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">ประเภท</label>
      <select name="category" class="form-select">
        <option value="">ทั้งหมด</option>
        <?php if (!empty($categories)) foreach ($categories as $cat): ?>
          <option value="<?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_category) && $selected_category === $cat) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($cat, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-2">
      <label class="form-label">สถานะ</label>
      <input type="text" name="status" value="<?= htmlspecialchars($selected_status ?? '', ENT_QUOTES, 'UTF-8'); ?>" class="form-control">
    </div>
    <div class="col-md-2 d-flex align-items-end">
      <button class="btn btn-primary w-100" type="submit">กรอง</button>
    </div>
  </form>

  <div class="row mb-3">
    <div class="col-md-4">
      <h5>สถิติตามสถานะ</h5>
      <ul class="list-group">
        <?php if (!empty($status_stats)) foreach ($status_stats as $s): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($s['status'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
            <span class="badge bg-secondary"><?= (int)($s['count'] ?? 0); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="col-md-4">
      <h5>สถิติตามสถานที่</h5>
      <ul class="list-group">
        <?php if (!empty($location_stats)) foreach ($location_stats as $l): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($l['location'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
            <span class="badge bg-secondary"><?= (int)($l['count'] ?? 0); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="col-md-4">
      <h5>สถิติตามประเภท</h5>
      <ul class="list-group">
        <?php if (!empty($category_stats)) foreach ($category_stats as $c): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($c['category'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
            <span class="badge bg-secondary"><?= (int)($c['count'] ?? 0); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>

  <h5>รายการครุภัณฑ์</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>ชื่อครุภัณฑ์</th>
          <th>ประเภท</th>
          <th>สถานที่</th>
          <th>สถานะ</th>
          <th>Serial</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($assets)) foreach ($assets as $a): ?>
          <tr>
            <td><?= htmlspecialchars($a['asset_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($a['category'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($a['current_location'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($a['status'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($a['serial_number'] ?? ($a['asset_code'] ?? '-'), ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($assets)): ?>
          <tr><td colspan="5" class="text-center">ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


