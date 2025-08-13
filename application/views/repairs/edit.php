<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit"></i>
        แก้ไขการซ่อมแซมครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('repairs'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle"></i>
                    ข้อมูลการซ่อมแซม
                </h6>
            </div>
            <div class="card-body">
                <?php echo form_open('repairs/update/' . $repair['repair_id'], array('id' => 'repairForm')); ?>

                <div class="mb-3">
                    <label class="required">ครุภัณฑ์</label>
                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($repair['asset_name']); ?>" disabled>
                </div>

                <div class="mb-3">
                    <label for="problem_description" class="required">รายละเอียดปัญหา</label>
                    <textarea class="form-control <?php echo form_error('problem_description') ? 'is-invalid' : ''; ?>" id="problem_description" name="problem_description" rows="4" required><?php echo set_value('problem_description', $repair['problem_description']); ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo form_error('problem_description'); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="repair_type" class="required">ประเภทการซ่อม</label>
                        <select class="form-control <?php echo form_error('repair_type') ? 'is-invalid' : ''; ?>" id="repair_type" name="repair_type" required>
                            <option value="">เลือกประเภทการซ่อม</option>
                            <option value="ซ่อมแซมทั่วไป" <?php echo set_select('repair_type', 'ซ่อมแซมทั่วไป', $repair['repair_type']=='ซ่อมแซมทั่วไป'); ?>>ซ่อมแซมทั่วไป</option>
                            <option value="เปลี่ยนอะไหล่" <?php echo set_select('repair_type', 'เปลี่ยนอะไหล่', $repair['repair_type']=='เปลี่ยนอะไหล่'); ?>>เปลี่ยนอะไหล่</option>
                            <option value="บำรุงรักษา" <?php echo set_select('repair_type', 'บำรุงรักษา', $repair['repair_type']=='บำรุงรักษา'); ?>>บำรุงรักษา</option>
                            <option value="ปรับปรุงแก้ไข" <?php echo set_select('repair_type', 'ปรับปรุงแก้ไข', $repair['repair_type']=='ปรับปรุงแก้ไข'); ?>>ปรับปรุงแก้ไข</option>
                            <option value="ตรวจสอบ/วินิจฉัย" <?php echo set_select('repair_type', 'ตรวจสอบ/วินิจฉัย', $repair['repair_type']=='ตรวจสอบ/วินิจฉัย'); ?>>ตรวจสอบ/วินิจฉัย</option>
                            <option value="ซ่อมภายนอก" <?php echo set_select('repair_type', 'ซ่อมภายนอก', $repair['repair_type']=='ซ่อมภายนอก'); ?>>ซ่อมภายนอก</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('repair_type'); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="priority" class="required">ระดับความสำคัญ</label>
                        <select class="form-control <?php echo form_error('priority') ? 'is-invalid' : ''; ?>" id="priority" name="priority" required>
                            <option value="">เลือกระดับความสำคัญ</option>
                            <option value="สูง" <?php echo set_select('priority', 'สูง', $repair['priority']=='สูง'); ?>>สูง (ฉุกเฉิน)</option>
                            <option value="ปานกลาง" <?php echo set_select('priority', 'ปานกลาง', $repair['priority']=='ปานกลาง'); ?>>ปานกลาง (ปกติ)</option>
                            <option value="ต่ำ" <?php echo set_select('priority', 'ต่ำ', $repair['priority']=='ต่ำ'); ?>>ต่ำ (ไม่เร่งด่วน)</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('priority'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="requested_by" class="required">ผู้แจ้ง</label>
                        <input type="text" class="form-control <?php echo form_error('requested_by') ? 'is-invalid' : ''; ?>" id="requested_by" name="requested_by" value="<?php echo set_value('requested_by', $repair['requested_by']); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('requested_by'); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="contact_info" class="required">ข้อมูลติดต่อ</label>
                        <input type="text" class="form-control <?php echo form_error('contact_info') ? 'is-invalid' : ''; ?>" id="contact_info" name="contact_info" value="<?php echo set_value('contact_info', $repair['contact_info']); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('contact_info'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estimated_cost">ค่าใช้จ่ายประมาณการ (บาท)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('estimated_cost') ? 'is-invalid' : ''; ?>" id="estimated_cost" name="estimated_cost" value="<?php echo set_value('estimated_cost', isset($repair['estimated_cost']) ? $repair['estimated_cost'] : ''); ?>" step="0.01" min="0">
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('estimated_cost'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="actual_cost">ค่าใช้จ่ายจริง (บาท)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('actual_cost') ? 'is-invalid' : ''; ?>" id="actual_cost" name="actual_cost" value="<?php echo set_value('actual_cost', isset($repair['actual_cost']) ? $repair['actual_cost'] : ''); ?>" step="0.01" min="0">
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('actual_cost'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="expected_completion">วันที่คาดว่าจะเสร็จ</label>
                        <input type="date" class="form-control <?php echo form_error('expected_completion') ? 'is-invalid' : ''; ?>" id="expected_completion" name="expected_completion" value="<?php echo set_value('expected_completion', isset($repair['expected_completion']) ? $repair['expected_completion'] : ''); ?>">
                        <div class="invalid-feedback">
                            <?php echo form_error('expected_completion'); ?>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="actual_completion">วันที่เสร็จสิ้นจริง</label>
                        <input type="date" class="form-control <?php echo form_error('actual_completion') ? 'is-invalid' : ''; ?>" id="actual_completion" name="actual_completion" value="<?php echo set_value('actual_completion', isset($repair['actual_completion']) ? $repair['actual_completion'] : ''); ?>">
                        <div class="invalid-feedback">
                            <?php echo form_error('actual_completion'); ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="vendor_info">ข้อมูลผู้ให้บริการ/ร้านซ่อม</label>
                    <input type="text" class="form-control <?php echo form_error('vendor_info') ? 'is-invalid' : ''; ?>" id="vendor_info" name="vendor_info" value="<?php echo set_value('vendor_info', isset($repair['vendor_info']) ? $repair['vendor_info'] : ''); ?>">
                    <div class="invalid-feedback">
                        <?php echo form_error('vendor_info'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="repair_details">รายละเอียดการซ่อม</label>
                    <textarea class="form-control <?php echo form_error('repair_details') ? 'is-invalid' : ''; ?>" id="repair_details" name="repair_details" rows="3"><?php echo set_value('repair_details', isset($repair['repair_details']) ? $repair['repair_details'] : ''); ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo form_error('repair_details'); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes">หมายเหตุเพิ่มเติม</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"><?php echo set_value('notes', isset($repair['notes']) ? $repair['notes'] : ''); ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="status" class="required">สถานะ</label>
                    <select class="form-control <?php echo form_error('status') ? 'is-invalid' : ''; ?>" id="status" name="status" required>
                        <option value="">เลือกสถานะ</option>
                        <option value="รอพิจารณา" <?php echo set_select('status', 'รอพิจารณา', $repair['status']=='รอพิจารณา'); ?>>รอพิจารณา</option>
                        <option value="อนุมัติ" <?php echo set_select('status', 'อนุมัติ', $repair['status']=='อนุมัติ'); ?>>อนุมัติ</option>
                        <option value="ไม่อนุมัติ" <?php echo set_select('status', 'ไม่อนุมัติ', $repair['status']=='ไม่อนุมัติ'); ?>>ไม่อนุมัติ</option>
                        <option value="กำลังซ่อม" <?php echo set_select('status', 'กำลังซ่อม', $repair['status']=='กำลังซ่อม'); ?>>กำลังซ่อม</option>
                        <option value="เสร็จสิ้น" <?php echo set_select('status', 'เสร็จสิ้น', $repair['status']=='เสร็จสิ้น'); ?>>เสร็จสิ้น</option>
                        <option value="ยกเลิก" <?php echo set_select('status', 'ยกเลิก', $repair['status']=='ยกเลิก'); ?>>ยกเลิก</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php echo form_error('status'); ?>
                    </div>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> บันทึก
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo base_url('repairs'); ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle"></i>
                    ข้อมูลครุภัณฑ์
                </h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>ชื่อครุภัณฑ์:</label>
                    <div class="h6"><?php echo htmlspecialchars($repair['asset_name']); ?></div>
                </div>
                <div class="form-group">
                    <label>หมายเลขซีเรียล:</label>
                    <div class="h6"><?php echo htmlspecialchars($repair['serial_number'] ?? '-'); ?></div>
                </div>
                <div class="form-group">
                    <label>สถานที่ปัจจุบัน:</label>
                    <div class="h6"><?php echo htmlspecialchars($repair['current_location'] ?? '-'); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>
