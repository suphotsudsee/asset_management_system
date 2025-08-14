<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit"></i>
        แก้ไขการโอนย้ายครุภัณฑ์ #<?php echo isset($transfer['transfer_id']) ? $transfer['transfer_id'] : ''; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('transfers'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
            <?php if (isset($transfer['transfer_id'])): ?>
            <a href="<?php echo base_url('transfers/view/' . $transfer['transfer_id']); ?>" class="btn btn-info">
                <i class="fas fa-eye"></i> ดูรายละเอียด
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-edit"></i>
                    แก้ไขข้อมูลการโอนย้าย
                </h6>
            </div>
            <div class="card-body">
                <?php if (isset($transfer['transfer_id'])): ?>
                <?php echo form_open('transfers/update/' . $transfer['transfer_id'], array('id' => 'transferEditForm')); ?>
                <?php else: ?>
                <?php echo form_open('transfers', array('id' => 'transferEditForm')); ?>
                <?php endif; ?>
                
                <!-- ข้อมูลครุภัณฑ์ (แสดงอย่างเดียว) -->
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label>ครุภัณฑ์ที่โอนย้าย</label>
                        <div class="card bg-light">
                            <div class="card-body p-3">
                                <h6 class="mb-2"><?php echo htmlspecialchars(isset($transfer['asset_name']) ? $transfer['asset_name'] : 'ไม่ระบุ'); ?></h6>
                                <?php if (isset($transfer['serial_number']) && !empty($transfer['serial_number'])): ?>
                                    <small class="text-muted">SN: <?php echo htmlspecialchars($transfer['serial_number']); ?></small>
                                <?php endif; ?>
                            </div>
                        </div>
                        <small class="form-text text-muted">ไม่สามารถเปลี่ยนแปลงครุภัณฑ์ได้หลังจากบันทึกแล้ว</small>
                    </div>
                </div>
                
                <!-- ข้อมูลสถานที่ (แสดงอย่างเดียว) -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>จากสถานที่</label>
                        <input type="text" class="form-control" 
                               value="<?php echo htmlspecialchars(isset($transfer['from_location']) ? $transfer['from_location'] : ''); ?>" 
                               readonly>
                        <small class="form-text text-muted">สถานที่เดิมไม่สามารถเปลี่ยนแปลงได้</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>ไปสถานที่</label>
                        <input type="text" class="form-control" 
                               value="<?php echo htmlspecialchars(isset($transfer['to_location']) ? $transfer['to_location'] : ''); ?>" 
                               readonly>
                        <small class="form-text text-muted">สถานที่ใหม่ไม่สามารถเปลี่ยนแปลงได้</small>
                    </div>
                </div>

                <!-- วันที่โอนย้าย (แสดงอย่างเดียว) -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>วันที่โอนย้าย</label>
                        <input type="text" class="form-control" 
                               value="<?php 
                                   if (isset($transfer['transfer_date']) && !empty($transfer['transfer_date'])) {
                                       echo date('d/m/Y', strtotime($transfer['transfer_date']));
                                   } else {
                                       echo 'ไม่ระบุ';
                                   }
                               ?>" 
                               readonly>
                        <small class="form-text text-muted">วันที่โอนย้ายไม่สามารถเปลี่ยนแปลงได้</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="transferred_by" class="required">ผู้ดำเนินการ</label>
                        <input type="text" class="form-control <?php echo form_error('transferred_by') ? 'is-invalid' : ''; ?>" 
                               id="transferred_by" name="transferred_by" 
                               value="<?php echo set_value('transferred_by', isset($transfer['transferred_by']) ? $transfer['transferred_by'] : ''); ?>" 
                               placeholder="ชื่อ-นามสกุล ผู้ดำเนินการ" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('transferred_by'); ?>
                        </div>
                    </div>
                </div>
                
                <!-- เหตุผลการโอนย้าย -->
                <div class="mb-3">
                    <label for="reason" class="required">เหตุผลการโอนย้าย</label>
                    <select class="form-control <?php echo form_error('reason') ? 'is-invalid' : ''; ?>" 
                            id="reason" name="reason" required>
                        <option value="">เลือกเหตุผล</option>
                        <option value="ปรับปรุงการใช้งาน" <?php echo set_select('reason', 'ปรับปรุงการใช้งาน', (isset($transfer['reason']) && $transfer['reason'] == 'ปรับปรุงการใช้งาน')); ?>>ปรับปรุงการใช้งาน</option>
                        <option value="เปลี่ยนแปลงโครงสร้างองค์กร" <?php echo set_select('reason', 'เปลี่ยนแปลงโครงสร้างองค์กร', (isset($transfer['reason']) && $transfer['reason'] == 'เปลี่ยนแปลงโครงสร้างองค์กร')); ?>>เปลี่ยนแปลงโครงสร้างองค์กร</option>
                        <option value="ย้ายหน่วยงาน" <?php echo set_select('reason', 'ย้ายหน่วยงาน', (isset($transfer['reason']) && $transfer['reason'] == 'ย้ายหน่วยงาน')); ?>>ย้ายหน่วยงาน</option>
                        <option value="ซ่อมแซม/บำรุงรักษา" <?php echo set_select('reason', 'ซ่อมแซม/บำรุงรักษา', (isset($transfer['reason']) && $transfer['reason'] == 'ซ่อมแซม/บำรุงรักษา')); ?>>ซ่อมแซม/บำรุงรักษา</option>
                        <option value="เพิ่มประสิทธิภาพการทำงาน" <?php echo set_select('reason', 'เพิ่มประสิทธิภาพการทำงาน', (isset($transfer['reason']) && $transfer['reason'] == 'เพิ่มประสิทธิภาพการทำงาน')); ?>>เพิ่มประสิทธิภาพการทำงาน</option>
                        <option value="ปรับปรุงสภาพแวดล้อม" <?php echo set_select('reason', 'ปรับปรุงสภาพแวดล้อม', (isset($transfer['reason']) && $transfer['reason'] == 'ปรับปรุงสภาพแวดล้อม')); ?>>ปรับปรุงสภาพแวดล้อม</option>
                        <option value="อื่นๆ" <?php echo set_select('reason', 'อื่นๆ', (isset($transfer['reason']) && $transfer['reason'] == 'อื่นๆ')); ?>>อื่นๆ</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php echo form_error('reason'); ?>
                    </div>
                </div>

                <!-- สถานะ -->
                <div class="mb-3">
                    <label for="status" class="required">สถานะ</label>
                    <select class="form-control <?php echo form_error('status') ? 'is-invalid' : ''; ?>" 
                            id="status" name="status" required>
                        <option value="">เลือกสถานะ</option>
                        <option value="รอดำเนินการ" <?php echo set_select('status', 'รอดำเนินการ', (isset($transfer['status']) && $transfer['status'] == 'รอดำเนินการ')); ?>>รอดำเนินการ</option>
                        <option value="กำลังดำเนินการ" <?php echo set_select('status', 'กำลังดำเนินการ', (isset($transfer['status']) && $transfer['status'] == 'กำลังดำเนินการ')); ?>>กำลังดำเนินการ</option>
                        <option value="เสร็จสิ้น" <?php echo set_select('status', 'เสร็จสิ้น', (isset($transfer['status']) && $transfer['status'] == 'เสร็จสิ้น')); ?>>เสร็จสิ้น</option>
                        <option value="ยกเลิก" <?php echo set_select('status', 'ยกเลิก', (isset($transfer['status']) && $transfer['status'] == 'ยกเลิก')); ?>>ยกเลิก</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php echo form_error('status'); ?>
                    </div>
                </div>
                
                <!-- หมายเหตุ -->
                <div class="mb-3">
                    <label for="notes">หมายเหตุเพิ่มเติม</label>
                    <textarea class="form-control" id="notes" name="notes" rows="4" 
                              placeholder="รายละเอียดเพิ่มเติม, เงื่อนไขพิเศษ..."><?php echo set_value('notes', isset($transfer['notes']) ? $transfer['notes'] : ''); ?></textarea>
                    <small class="form-text text-muted">ข้อมูลเพิ่มเติมเกี่ยวกับการโอนย้าย</small>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> บันทึกการแก้ไข
                            </button>
                        </div>
                        <div class="col-md-6">
                            <?php if (isset($transfer['transfer_id'])): ?>
                            <a href="<?php echo base_url('transfers/view/' . $transfer['transfer_id']); ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                            <?php else: ?>
                            <a href="<?php echo base_url('transfers'); ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                
                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- ข้อมูลครุภัณฑ์ -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-box"></i>
                    ข้อมูลครุภัณฑ์
                </h6>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>ชื่อครุภัณฑ์:</label>
                    <div class="h6"><?php echo htmlspecialchars(isset($transfer['asset_name']) ? $transfer['asset_name'] : 'ไม่ระบุ'); ?></div>
                </div>
                <?php if (isset($transfer['serial_number']) && !empty($transfer['serial_number'])): ?>
                <div class="form-group">
                    <label>หมายเลขซีเรียล:</label>
                    <div class="h6"><?php echo htmlspecialchars($transfer['serial_number']); ?></div>
                </div>
                <?php endif; ?>
                <div class="form-group">
                    <label>สถานะครุภัณฑ์:</label>
                    <div class="h6">
                        <?php 
                        $asset_status = isset($transfer['asset_status']) ? $transfer['asset_status'] : 'ไม่ระบุ';
                        echo htmlspecialchars($asset_status); 
                        ?>
                    </div>
                </div>
                <?php if (isset($transfer['responsible_person']) && !empty($transfer['responsible_person'])): ?>
                <div class="form-group">
                    <label>ผู้รับผิดชอบ:</label>
                    <div class="h6"><?php echo htmlspecialchars($transfer['responsible_person']); ?></div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ประวัติการโอนย้าย -->
        <?php if (isset($transfer['created_at']) || isset($transfer['updated_at'])): ?>
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-history"></i>
                    ประวัติการโอนย้าย
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if (isset($transfer['created_at'])): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-primary"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">สร้างการโอนย้าย</h6>
                            <p class="timeline-text">
                                <?php echo date('d/m/Y H:i', strtotime($transfer['created_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if (isset($transfer['updated_at']) && isset($transfer['created_at']) && $transfer['updated_at'] != $transfer['created_at']): ?>
                    <div class="timeline-item">
                        <div class="timeline-marker bg-warning"></div>
                        <div class="timeline-content">
                            <h6 class="timeline-title">แก้ไขล่าสุด</h6>
                            <p class="timeline-text">
                                <?php echo date('d/m/Y H:i', strtotime($transfer['updated_at'])); ?>
                            </p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- คำแนะนำ -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle"></i>
                    คำแนะนำการแก้ไข
                </h6>
            </div>
            <div class="card-body">
                <h6>สิ่งที่สามารถแก้ไขได้:</h6>
                <ul class="small">
                    <li><strong>ผู้ดำเนินการ:</strong> ชื่อผู้รับผิดชอบ</li>
                    <li><strong>เหตุผล:</strong> เหตุผลการโอนย้าย</li>
                    <li><strong>สถานะ:</strong> สถานะปัจจุบัน</li>
                    <li><strong>หมายเหตุ:</strong> ข้อมูลเพิ่มเติม</li>
                </ul>
                
                <hr>
                
                <h6>สิ่งที่ไม่สามารถแก้ไขได้:</h6>
                <ul class="small">
                    <li>ครุภัณฑ์ที่เลือก</li>
                    <li>สถานที่เดิมและสถานที่ใหม่</li>
                    <li>วันที่โอนย้าย</li>
                </ul>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>หมายเหตุ:</strong> หากต้องการเปลี่ยนแปลงข้อมูลที่ไม่สามารถแก้ไขได้ ต้องสร้างการโอนย้ายใหม่
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Form validation
    $('#transferEditForm').on('submit', function(e) {
        var isValid = true;
        
        // Check required fields
        $(this).find('input[required], select[required]').each(function() {
            if (!$(this).val().trim()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        if (!isValid) {
            e.preventDefault();
            showAlert('danger', 'กรุณากรอกข้อมูลให้ครบถ้วน');
        }
    });
    
    // Remove validation class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });

    // Status change handler
    $('#status').on('change', function() {
        var status = $(this).val();
        var alertClass = '';
        var message = '';
        
        switch(status) {
            case 'รอดำเนินการ':
                alertClass = 'info';
                message = 'การโอนย้ายอยู่ในสถานะรอดำเนินการ';
                break;
            case 'กำลังดำเนินการ':
                alertClass = 'warning';
                message = 'การโอนย้ายกำลังดำเนินการอยู่';
                break;
            case 'เสร็จสิ้น':
                alertClass = 'success';
                message = 'การโอนย้ายได้เสร็จสิ้นแล้ว';
                break;
            case 'ยกเลิก':
                alertClass = 'danger';
                message = 'การโอนย้ายถูกยกเลิก';
                break;
        }
        
        if (message) {
            // Remove existing status alerts
            $('.status-alert').remove();
            
            // Add new status alert
            var alertHtml = '<div class="alert alert-' + alertClass + ' status-alert mt-2">' +
                           '<i class="fas fa-info-circle"></i> ' + message +
                           '</div>';
            $(this).parent().append(alertHtml);
        }
    });

    // Auto-trigger status change on page load
    if ($('#status').val()) {
        $('#status').trigger('change');
    }
});

// Show alert function
function showAlert(type, message) {
    var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="close" data-dismiss="alert">' +
                    '<span>&times;</span>' +
                    '</button>' +
                    '</div>';
    
    $('.card-body').first().prepend(alertHtml);
}
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.timeline {
    position: relative;
    padding: 0;
    list-style: none;
}

.timeline-item {
    position: relative;
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 0.375rem;
    top: 1rem;
    width: 1px;
    height: calc(100% + 0.5rem);
    background-color: #dee2e6;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.timeline-text {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0;
}

.card-body .form-group {
    margin-bottom: 1rem;
}

.card-body .form-group:last-child {
    margin-bottom: 0;
}

.card-body label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.status-alert {
    font-size: 0.875rem;
}
</style>