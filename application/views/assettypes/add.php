<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tag"></i>
        เพิ่มประเภทครุภัณฑ์
    </h1>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open('assettypes/store'); ?>
            <div class="form-group">
                <label for="type_name" class="required">ชื่อประเภท</label>
                <input type="text" class="form-control <?php echo form_error('type_name') ? 'is-invalid' : ''; ?>" name="type_name" value="<?php echo set_value('type_name'); ?>" required>
                <div class="invalid-feedback">
                    <?php echo form_error('type_name'); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="description">คำอธิบาย</label>
                <textarea class="form-control" name="description" rows="3"><?php echo set_value('description'); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="<?php echo base_url('assettypes'); ?>" class="btn btn-secondary">ยกเลิก</a>
        <?php echo form_close(); ?>
    </div>
</div>
