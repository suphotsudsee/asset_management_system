<?php 
$is_edit = isset($record);
$action  = $is_edit ? 'depreciation/edit/'.$record->depreciation_record_id : 'depreciation/create';
?>
<div class="container-fluid mt-3">
  <h4 class="mb-3"><?= $is_edit ? 'แก้ไข' : 'เพิ่ม'; ?> บันทึกค่าเสื่อมราคา</h4>

  <?= validation_errors('<div class="alert alert-danger">','</div>'); ?>

  <?= form_open($action); ?>
    <div class="card">
      <div class="card-body">
        <div class="form-row">
          <div class="form-group col-md-6">
            <label for="asset_id">ครุภัณฑ์</label>
            <select name="asset_id" id="asset_id" class="form-control" required>
              <option value="">-- เลือกครุภัณฑ์ --</option>
              <?php foreach ($assets as $a): ?>
                <option value="<?= $a->asset_id; ?>"
                  <?= set_select('asset_id', $a->asset_id, $is_edit && $a->asset_id==$record->asset_id); ?>>
                  <?= $a->asset_name.' ('.$a->serial_number.')'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group col-md-3">
            <label for="record_date">วันที่บันทึก</label>
            <input type="date" name="record_date" id="record_date" class="form-control"
              value="<?= set_value('record_date', $is_edit ? $record->record_date : date('Y-m-d')); ?>" required>
          </div>
        </div>

        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="depreciation_amount">ค่าเสื่อมราคา (บาท)</label>
            <input type="number" step="0.01" min="0" name="depreciation_amount" id="depreciation_amount"
              class="form-control"
              value="<?= set_value('depreciation_amount', $is_edit ? $record->depreciation_amount : ''); ?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="accumulated_depreciation">ค่าเสื่อมสะสม (บาท)</label>
            <input type="number" step="0.01" min="0" name="accumulated_depreciation" id="accumulated_depreciation"
              class="form-control"
              value="<?= set_value('accumulated_depreciation', $is_edit ? $record->accumulated_depreciation : ''); ?>" required>
          </div>
          <div class="form-group col-md-4">
            <label for="book_value">มูลค่าตามบัญชี (บาท)</label>
            <input type="number" step="0.01" min="0" name="book_value" id="book_value"
              class="form-control"
              value="<?= set_value('book_value', $is_edit ? $record->book_value : ''); ?>" required>
          </div>
        </div>
      </div>
      <div class="card-footer text-right">
        <a href="<?= site_url('depreciation'); ?>" class="btn btn-secondary">ยกเลิก</a>
        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> บันทึก</button>
      </div>
    </div>
  <?= form_close(); ?>
</div>
