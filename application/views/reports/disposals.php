<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container my-3">
  <h2><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'รายงานการจำหน่ายครุภัณฑ์'; ?></h2>

  <form method="get" action="<?= site_url('reports/disposals'); ?>" class="row g-2 mb-3">
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
      <label class="form-label">วิธีการจำหน่าย</label>
      <?php $methods = isset($disposal_stats['methods']) ? array_map(function($r){return $r['disposal_method'];}, $disposal_stats['methods']) : []; $methods = array_unique(array_filter($methods)); ?>
      <select name="disposal_method" class="form-select">
        <option value="">ทั้งหมด</option>
        <?php foreach ($methods as $method): ?>
          <option value="<?= htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_disposal_method) && $selected_disposal_method === $method) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($method, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-12 d-flex justify-content-end mt-2">
      <button class="btn btn-primary" type="submit">กรอง</button>
    </div>
  </form>

  <div class="row mb-3">
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">จำนวนการจำหน่ายทั้งหมด</h5>
          <p class="card-text display-6 mb-0"><?= (int)($disposal_stats['total'] ?? 0); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">มูลค่าการจำหน่ายรวม</h5>
          <p class="card-text h4 mb-0"><?= number_format((float)($disposal_value_summary['total_disposal_value'] ?? 0), 2); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">มูลค่าซื้อรวมของทรัพย์สินที่จำหน่าย</h5>
          <p class="card-text h4 mb-0"><?= number_format((float)($disposal_value_summary['total_purchase_value'] ?? 0), 2); ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <h5>วิธีการจำหน่ายยอดนิยม</h5>
      <ul class="list-group">
        <?php if (!empty($disposal_stats['methods'])) foreach ($disposal_stats['methods'] as $row): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($row['disposal_method'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
            <span class="badge bg-secondary"><?= (int)($row['count'] ?? 0); ?></span>
          </li>
        <?php endforeach; ?>
        <?php if (empty($disposal_stats['methods'])): ?>
          <li class="list-group-item text-center">ไม่มีข้อมูล</li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="col-md-6">
      <h5>จำนวนต่อเดือน</h5>
      <ul class="list-group">
        <?php if (!empty($disposal_stats['monthly'])) foreach ($disposal_stats['monthly'] as $row): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            เดือน <?= (int)($row['month'] ?? 0); ?>
            <span class="badge bg-secondary"><?= (int)($row['count'] ?? 0); ?></span>
          </li>
        <?php endforeach; ?>
        <?php if (empty($disposal_stats['monthly'])): ?>
          <li class="list-group-item text-center">ไม่มีข้อมูล</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <h5>รายการการจำหน่าย</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>วันที่จำหน่าย</th>
          <th>ชื่อครุภัณฑ์</th>
          <th>Serial</th>
          <th>วิธีการจำหน่าย</th>
          <th>มูลค่าจำหน่าย</th>
          <th>เหตุผล</th>
          <th>สถานะ</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($disposals)) foreach ($disposals as $d): ?>
          <tr>
            <td><?= htmlspecialchars($d['disposal_date'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($d['asset_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($d['serial_number'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($d['disposal_method'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= isset($d['disposal_price']) ? number_format((float)$d['disposal_price'], 2) : '0.00'; ?></td>
            <td><?= htmlspecialchars($d['reason'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($d['status'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($disposals)): ?>
          <tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


