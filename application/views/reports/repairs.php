<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container my-3">
  <h2><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'รายงานการซ่อมแซมครุภัณฑ์'; ?></h2>

  <form method="get" action="<?= site_url('reports/repairs'); ?>" class="row g-2 mb-3">
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
      <label class="form-label">สถานะ</label>
      <select name="status" class="form-select">
        <?php
          $statusOptions = array(
            '' => 'ทั้งหมด',
            'รอดำเนินการ' => 'รอดำเนินการ',
            'กำลังซ่อม' => 'กำลังซ่อม',
            'ซ่อมเสร็จแล้ว' => 'ซ่อมเสร็จแล้ว',
            'ไม่สามารถซ่อมได้' => 'ไม่สามารถซ่อมได้',
          );
        ?>
        <?php foreach ($statusOptions as $value => $label): ?>
          <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_status) && (string)$selected_status === (string)$value) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label">ความสำคัญ</label>
      <select name="priority" class="form-select">
        <?php $priorityOptions = array('' => 'ทั้งหมด', 'ต่ำ' => 'ต่ำ', 'กลาง' => 'กลาง', 'สูง' => 'สูง'); ?>
        <?php foreach ($priorityOptions as $value => $label): ?>
          <option value="<?= htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?>" <?= (isset($selected_priority) && (string)$selected_priority === (string)$value) ? 'selected' : ''; ?>>
            <?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8'); ?>
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
          <h5 class="card-title">จำนวนการซ่อมแซมทั้งหมด</h5>
          <p class="card-text display-6 mb-0"><?= (int)($repair_stats['total'] ?? 0); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">ค่าใช้จ่ายรวม</h5>
          <p class="card-text h4 mb-0"><?= number_format((float)($repair_cost_summary['total_cost'] ?? 0), 2); ?></p>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">ค่าใช้จ่ายเฉลี่ยต่อครั้ง</h5>
          <p class="card-text h4 mb-0"><?= number_format((float)($repair_cost_summary['average_cost'] ?? 0), 2); ?></p>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-3">
    <div class="col-md-6">
      <h5>จำนวนตามสถานะ</h5>
      <ul class="list-group">
        <?php if (!empty($repair_stats['status'])) foreach ($repair_stats['status'] as $row): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <?= htmlspecialchars($row['status'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>
            <span class="badge bg-secondary"><?= (int)($row['count'] ?? 0); ?></span>
          </li>
        <?php endforeach; ?>
        <?php if (empty($repair_stats['status'])): ?>
          <li class="list-group-item text-center">ไม่มีข้อมูล</li>
        <?php endif; ?>
      </ul>
    </div>
    <div class="col-md-6">
      <h5>จำนวนต่อเดือน</h5>
      <ul class="list-group">
        <?php if (!empty($repair_stats['monthly'])) foreach ($repair_stats['monthly'] as $row): ?>
          <li class="list-group-item d-flex justify-content-between align-items-center">
            เดือน <?= (int)($row['month'] ?? 0); ?>
            <span>
              <span class="badge bg-secondary me-2">จำนวน <?= (int)($row['count'] ?? 0); ?></span>
              <span class="badge bg-info text-dark">ค่าใช้จ่าย <?= isset($row['cost']) ? number_format((float)$row['cost'], 2) : '0.00'; ?></span>
            </span>
          </li>
        <?php endforeach; ?>
        <?php if (empty($repair_stats['monthly'])): ?>
          <li class="list-group-item text-center">ไม่มีข้อมูล</li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <h5>รายการการซ่อมแซม</h5>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>วันที่ร้องขอ</th>
          <th>ชื่อครุภัณฑ์</th>
          <th>Serial</th>
          <th>สถานะ</th>
          <th>ผู้ซ่อม</th>
          <th>ค่าใช้จ่าย</th>
          <th>รายละเอียด</th>
        </tr>
      </thead>
      <tbody>
        <?php if (!empty($repairs)) foreach ($repairs as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['request_date'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($r['asset_name'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($r['serial_number'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($r['status'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= htmlspecialchars($r['repaired_by'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?= isset($r['cost']) ? number_format((float)$r['cost'], 2) : '0.00'; ?></td>
            <td><?= htmlspecialchars($r['description'] ?? '-', ENT_QUOTES, 'UTF-8'); ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($repairs)): ?>
          <tr><td colspan="7" class="text-center">ไม่พบข้อมูล</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>


