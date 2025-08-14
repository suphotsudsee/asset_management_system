<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container my-3">
  <div class="row">
    <div class="col-md-8">
      <h2><?= isset($page_title) ? htmlspecialchars($page_title, ENT_QUOTES, 'UTF-8') : 'รายละเอียดการโอนย้าย'; ?></h2>
    </div>
    <div class="col-md-4 text-end">
      <a href="<?= site_url('transfers'); ?>" class="btn btn-secondary">← กลับ</a>
      <a href="<?= site_url('transfers/edit/' . $transfer['transfer_id']); ?>" class="btn btn-primary">แก้ไข</a>
    </div>
  </div>

  <?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('success'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <?= $this->session->flashdata('error'); ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  <?php endif; ?>

  <div class="card">
    <div class="card-header">
      <h5 class="mb-0">ข้อมูลการโอนย้าย #<?= htmlspecialchars($transfer['transfer_id'], ENT_QUOTES, 'UTF-8'); ?></h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th width="30%">ครุภัณฑ์:</th>
              <td><?= htmlspecialchars($transfer['asset_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
              <th>หมายเลขซีเรียล:</th>
              <td><?= htmlspecialchars($transfer['serial_number'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
              <th>ประเภท:</th>
              <td><?= htmlspecialchars($transfer['asset_type'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
              <th>สถานะครุภัณฑ์:</th>
              <td>
                <span class="badge bg-<?= $transfer['asset_status'] === 'ใช้งาน' ? 'success' : ($transfer['asset_status'] === 'ซ่อมแซม' ? 'warning' : 'danger'); ?>">
                  <?= htmlspecialchars($transfer['asset_status'], ENT_QUOTES, 'UTF-8'); ?>
                </span>
              </td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th width="30%">จากสถานที่:</th>
              <td><?= htmlspecialchars($transfer['from_location'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
              <th>ไปสถานที่:</th>
              <td><?= htmlspecialchars($transfer['to_location'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <tr>
              <th>วันที่โอนย้าย:</th>
              <td><?= htmlspecialchars($transfer['transfer_date'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
                         <tr>
               <th>ผู้ดำเนินการ:</th>
               <td><?= htmlspecialchars($transfer['transfer_by'], ENT_QUOTES, 'UTF-8'); ?></td>
             </tr>
          </table>
        </div>
      </div>

      <div class="row mt-3">
        <div class="col-12">
          <table class="table table-borderless">
            <tr>
              <th width="15%">เหตุผล:</th>
              <td><?= htmlspecialchars($transfer['reason'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php if (!empty($transfer['notes'])): ?>
              <tr>
                <th>หมายเหตุ:</th>
                <td><?= htmlspecialchars($transfer['notes'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
            <?php endif; ?>
                         <tr>
               <th>สถานะ:</th>
               <td>
                 <span class="badge bg-success">เสร็จสิ้น</span>
               </td>
             </tr>
            <tr>
              <th>วันที่สร้าง:</th>
              <td><?= htmlspecialchars($transfer['created_at'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
            <?php if (!empty($transfer['updated_at'])): ?>
              <tr>
                <th>วันที่อัปเดต:</th>
                <td><?= htmlspecialchars($transfer['updated_at'], ENT_QUOTES, 'UTF-8'); ?></td>
              </tr>
            <?php endif; ?>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="mt-3">
    <a href="<?= site_url('transfers'); ?>" class="btn btn-secondary">← กลับไปรายการ</a>
    <a href="<?= site_url('transfers/edit/' . $transfer['transfer_id']); ?>" class="btn btn-primary">แก้ไข</a>
    <a href="<?= site_url('transfers/delete/' . $transfer['transfer_id']); ?>" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบการโอนย้ายนี้?')">ลบ</a>
  </div>
</div>
