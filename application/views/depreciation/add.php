<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tools"></i>
        ขออนุญาตซ่อมแซมครุภัณฑ์
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
                    ข้อมูลการขออนุญาตซ่อมแซม
                </h6>
            </div>
            <div class="card-body">
                <?php echo form_open('repairs/store', array('id' => 'repairForm')); ?>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="asset_id" class="required">เลือกครุภัณฑ์</label>
                        <select class="form-control <?php echo form_error('asset_id') ? 'is-invalid' : ''; ?>" 
                                id="asset_id" name="asset_id" required>
                            <option value="">เลือกครุภัณฑ์ที่ต้องการซ่อมแซม</option>
                            <?php if (!empty($assets)): ?>
                                <?php foreach ($assets as $asset): ?>
                                    <option value="<?php echo $asset['asset_id']; ?>" 
                                            data-location="<?php echo htmlspecialchars($asset['current_location']); ?>"
                                            data-serial="<?php echo htmlspecialchars($asset['serial_number']); ?>"
                                            data-status="<?php echo htmlspecialchars($asset['status']); ?>"
                                            data-responsible="<?php echo htmlspecialchars($asset['responsible_person']); ?>"
                                            <?php echo set_select('asset_id', $asset['asset_id'], ($selected_asset_id == $asset['asset_id'])); ?>>
                                        <?php echo htmlspecialchars($asset['asset_name']); ?>
                                        <?php if (!empty($asset['serial_number'])): ?>
                                            (SN: <?php echo htmlspecialchars($asset['serial_number']); ?>)
                                        <?php endif; ?>
                                        - <?php echo htmlspecialchars($asset['current_location']); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('asset_id'); ?>
                        </div>
                        <small class="form-text text-muted">เลือกครุภัณฑ์ที่มีปัญหาและต้องการซ่อมแซม</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="problem_description" class="required">รายละเอียดปัญหา</label>
                    <textarea class="form-control <?php echo form_error('problem_description') ? 'is-invalid' : ''; ?>" 
                              id="problem_description" name="problem_description" rows="4" 
                              placeholder="อธิบายปัญหาที่พบให้ละเอียด เช่น อาการ, ความถี่ที่เกิดปัญหา, สาเหตุที่สงสัย..." required><?php echo set_value('problem_description'); ?></textarea>
                    <div class="invalid-feedback">
                        <?php echo form_error('problem_description'); ?>
                    </div>
                    <small class="form-text text-muted">อธิบายปัญหาให้ละเอียดเพื่อช่วยในการวินิจฉัยและซ่อมแซม</small>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="repair_type" class="required">ประเภทการซ่อม</label>
                        <select class="form-control <?php echo form_error('repair_type') ? 'is-invalid' : ''; ?>" 
                                id="repair_type" name="repair_type" required>
                            <option value="">เลือกประเภทการซ่อม</option>
                            <option value="ซ่อมแซมทั่วไป" <?php echo set_select('repair_type', 'ซ่อมแซมทั่วไป'); ?>>ซ่อมแซมทั่วไป</option>
                            <option value="เปลี่ยนอะไหล่" <?php echo set_select('repair_type', 'เปลี่ยนอะไหล่'); ?>>เปลี่ยนอะไหล่</option>
                            <option value="บำรุงรักษา" <?php echo set_select('repair_type', 'บำรุงรักษา'); ?>>บำรุงรักษา</option>
                            <option value="ปรับปรุงแก้ไข" <?php echo set_select('repair_type', 'ปรับปรุงแก้ไข'); ?>>ปรับปรุงแก้ไข</option>
                            <option value="ตรวจสอบ/วินิจฉัย" <?php echo set_select('repair_type', 'ตรวจสอบ/วินิจฉัย'); ?>>ตรวจสอบ/วินิจฉัย</option>
                            <option value="ซ่อมภายนอก" <?php echo set_select('repair_type', 'ซ่อมภายนอก'); ?>>ซ่อมภายนอก</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('repair_type'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="priority" class="required">ระดับความสำคัญ</label>
                        <select class="form-control <?php echo form_error('priority') ? 'is-invalid' : ''; ?>" 
                                id="priority" name="priority" required>
                            <option value="">เลือกระดับความสำคัญ</option>
                            <option value="สูง" <?php echo set_select('priority', 'สูง'); ?>>สูง (ฉุกเฉิน)</option>
                            <option value="ปานกลาง" <?php echo set_select('priority', 'ปานกลาง', true); ?>>ปานกลาง (ปกติ)</option>
                            <option value="ต่ำ" <?php echo set_select('priority', 'ต่ำ'); ?>>ต่ำ (ไม่เร่งด่วน)</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('priority'); ?>
                        </div>
                        <small class="form-text text-muted">
                            <strong>สูง:</strong> ส่งผลกระทบต่อการทำงาน<br>
                            <strong>ปานกลาง:</strong> ใช้งานได้แต่มีปัญหา<br>
                            <strong>ต่ำ:</strong> ไม่ส่งผลกระทบ
                        </small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="requested_by" class="required">ผู้แจ้ง</label>
                        <input type="text" class="form-control <?php echo form_error('requested_by') ? 'is-invalid' : ''; ?>" 
                               id="requested_by" name="requested_by" 
                               value="<?php echo set_value('requested_by'); ?>" 
                               placeholder="ชื่อ-นามสกุล ผู้แจ้งปัญหา" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('requested_by'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="contact_info" class="required">ข้อมูลติดต่อ</label>
                        <input type="text" class="form-control <?php echo form_error('contact_info') ? 'is-invalid' : ''; ?>" 
                               id="contact_info" name="contact_info" 
                               value="<?php echo set_value('contact_info'); ?>" 
                               placeholder="เบอร์โทร, อีเมล, หรือสถานที่ติดต่อ" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('contact_info'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="estimated_cost">ค่าใช้จ่ายประมาณการ (บาท)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('estimated_cost') ? 'is-invalid' : ''; ?>" 
                                   id="estimated_cost" name="estimated_cost" 
                                   value="<?php echo set_value('estimated_cost'); ?>" 
                                   step="0.01" min="0" placeholder="0.00">
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('estimated_cost'); ?>
                            </div>
                        </div>
                        <small class="form-text text-muted">ประมาณการค่าใช้จ่าย (ถ้าทราบ)</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="expected_completion">วันที่คาดว่าจะเสร็จ</label>
                        <input type="date" class="form-control <?php echo form_error('expected_completion') ? 'is-invalid' : ''; ?>" 
                               id="expected_completion" name="expected_completion" 
                               value="<?php echo set_value('expected_completion'); ?>">
                        <div class="invalid-feedback">
                            <?php echo form_error('expected_completion'); ?>
                        </div>
                        <small class="form-text text-muted">วันที่ต้องการให้เสร็จ (ถ้ามี)</small>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes">หมายเหตุเพิ่มเติม</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                              placeholder="ข้อมูลเพิ่มเติม, ข้อเสนอแนะ, หรือข้อกำหนดพิเศษ..."><?php echo set_value('notes'); ?></textarea>
                    <small class="form-text text-muted">ข้อมูลเพิ่มเติมที่อาจช่วยในการซ่อมแซม</small>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-paper-plane"></i> ส่งคำขอซ่อมแซม
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
                <div id="assetInfo" class="d-none">
                    <div class="form-group">
                        <label>ชื่อครุภัณฑ์:</label>
                        <div class="h6" id="assetName">-</div>
                    </div>
                    <div class="form-group">
                        <label>หมายเลขซีเรียล:</label>
                        <div class="h6" id="assetSerial">-</div>
                    </div>
                    <div class="form-group">
                        <label>สถานที่ตั้ง:</label>
                        <div class="h6" id="assetLocation">-</div>
                    </div>
                    <div class="form-group">
                        <label>สถานะ:</label>
                        <div class="h6" id="assetStatus">-</div>
                    </div>
                    <div class="form-group">
                        <label>ผู้รับผิดชอบ:</label>
                        <div class="h6" id="assetResponsible">-</div>
                    </div>
                </div>
                
                <div id="noAssetSelected">
                    <div class="text-center text-muted">
                        <i class="fas fa-box fa-3x mb-3"></i>
                        <p>เลือกครุภัณฑ์เพื่อดูข้อมูล</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle"></i>
                    คำแนะนำ
                </h6>
            </div>
            <div class="card-body">
                <h6>การขออนุญาตซ่อมแซม:</h6>
                <ul class="small">
                    <li><strong>อธิบายปัญหา:</strong> ให้รายละเอียดชัดเจน</li>
                    <li><strong>ระดับความสำคัญ:</strong> ประเมินผลกระทบ</li>
                    <li><strong>ข้อมูลติดต่อ:</strong> ระบุให้ครบถ้วน</li>
                    <li><strong>ประมาณการค่าใช้จ่าย:</strong> ถ้าทราบ</li>
                </ul>
                
                <hr>
                
                <h6>ขั้นตอนการดำเนินการ:</h6>
                <ol class="small">
                    <li>ส่งคำขอซ่อมแซม</li>
                    <li>รอการพิจารณาอนุมัติ</li>
                    <li>ดำเนินการซ่อมแซม</li>
                    <li>ตรวจสอบผลงาน</li>
                    <li>ปิดงานซ่อมแซม</li>
                </ol>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-lightbulb"></i>
                    <strong>เคล็ดลับ:</strong> ถ่ายรูปปัญหาแนบไปด้วยจะช่วยให้การวินิจฉัยแม่นยำขึ้น
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-phone"></i>
                    ติดต่อฝ่ายซ่อมบำรุง
                </h6>
            </div>
            <div class="card-body">
                <div class="small">
                    <p><strong>เบอร์โทร:</strong> 02-xxx-xxxx</p>
                    <p><strong>อีเมล:</strong> maintenance@company.com</p>
                    <p><strong>เวลาทำการ:</strong> จันทร์-ศุกร์ 8:00-17:00</p>
                    <p><strong>ฉุกเฉิน:</strong> 08x-xxx-xxxx (24 ชม.)</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Asset selection change
    $('#asset_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var assetId = $(this).val();
        
        if (assetId) {
            // Get asset info via AJAX
            $.ajax({
                url: '<?php echo base_url('repairs/api_get_asset_info'); ?>',
                method: 'POST',
                data: { asset_id: assetId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        var asset = response.asset;
                        
                        // Update asset info display
                        $('#assetName').text(asset.asset_name);
                        $('#assetSerial').text(asset.serial_number || '-');
                        $('#assetLocation').text(asset.current_location);
                        $('#assetStatus').text(asset.status);
                        $('#assetResponsible').text(asset.responsible_person);
                        
                        // Auto-fill requested_by if not filled
                        if (!$('#requested_by').val()) {
                            $('#requested_by').val(asset.responsible_person);
                        }
                        
                        // Show asset info
                        $('#assetInfo').removeClass('d-none');
                        $('#noAssetSelected').addClass('d-none');
                    } else {
                        showAlert('danger', response.message);
                        resetAssetInfo();
                    }
                },
                error: function() {
                    showAlert('danger', 'เกิดข้อผิดพลาดในการดึงข้อมูลครุภัณฑ์');
                    resetAssetInfo();
                }
            });
        } else {
            resetAssetInfo();
        }
    });
    
    // Reset asset info display
    function resetAssetInfo() {
        $('#assetInfo').addClass('d-none');
        $('#noAssetSelected').removeClass('d-none');
    }
    
    // Form validation
    $('#repairForm').on('submit', function(e) {
        var isValid = true;
        
        // Check required fields
        $(this).find('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Check problem description length
        var problemDesc = $('#problem_description').val();
        if (problemDesc.length < 10) {
            $('#problem_description').addClass('is-invalid');
            showAlert('danger', 'รายละเอียดปัญหาต้องมีอย่างน้อย 10 ตัวอักษร');
            isValid = false;
        }
        
        // Check expected completion date
        var expectedDate = new Date($('#expected_completion').val());
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if ($('#expected_completion').val() && expectedDate < today) {
            $('#expected_completion').addClass('is-invalid');
            showAlert('warning', 'วันที่คาดว่าจะเสร็จไม่ควรเป็นวันที่ในอดีต');
            // Don't prevent submission, just warn
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Remove validation class on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Auto-select asset if provided in URL
    <?php if (!empty($selected_asset_id)): ?>
        $('#asset_id').trigger('change');
    <?php endif; ?>
    
    // Priority change handler
    $('#priority').on('change', function() {
        var priority = $(this).val();
        var expectedDate = $('#expected_completion');
        var today = new Date();
        
        // Auto-suggest expected completion date based on priority
        switch(priority) {
            case 'สูง':
                today.setDate(today.getDate() + 1); // Tomorrow
                expectedDate.val(today.toISOString().split('T')[0]);
                break;
            case 'ปานกลาง':
                today.setDate(today.getDate() + 7); // Next week
                expectedDate.val(today.toISOString().split('T')[0]);
                break;
            case 'ต่ำ':
                today.setDate(today.getDate() + 30); // Next month
                expectedDate.val(today.toISOString().split('T')[0]);
                break;
        }
    });
    
    // Character counter for problem description
    $('#problem_description').on('input', function() {
        var length = $(this).val().length;
        var maxLength = 1000;
        var remaining = maxLength - length;
        
        if (!$('#char-counter').length) {
            $(this).after('<small id="char-counter" class="form-text text-muted"></small>');
        }
        
        $('#char-counter').text('เหลือ ' + remaining + ' ตัวอักษร');
        
        if (remaining < 0) {
            $('#char-counter').removeClass('text-muted').addClass('text-danger');
        } else {
            $('#char-counter').removeClass('text-danger').addClass('text-muted');
        }
    });
});
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

#assetInfo .form-group {
    margin-bottom: 1rem;
}

#assetInfo label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
</style>

