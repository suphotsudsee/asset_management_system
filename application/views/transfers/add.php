<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-exchange-alt"></i>
        โอนย้ายครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('transfers'); ?>" class="btn btn-secondary">
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
                    ข้อมูลการโอนย้าย
                </h6>
            </div>
            <div class="card-body">
                <?php echo form_open('transfers/store', array('id' => 'transferForm')); ?>
                
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="asset_id" class="required">เลือกครุภัณฑ์</label>
                        <select class="form-control <?php echo form_error('asset_id') ? 'is-invalid' : ''; ?>" 
                                id="asset_id" name="asset_id" required>
                            <option value="">เลือกครุภัณฑ์ที่ต้องการโอนย้าย</option>
                            <?php if (!empty($assets)): ?>
                                <?php foreach ($assets as $asset): ?>
                                    <option value="<?php echo $asset['asset_id']; ?>" 
                                            data-location="<?php echo htmlspecialchars($asset['current_location']); ?>"
                                            data-serial="<?php echo htmlspecialchars($asset['serial_number']); ?>"
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
                        <small class="form-text text-muted">เลือกครุภัณฑ์ที่ต้องการโอนย้าย</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="from_location" class="required">จากสถานที่</label>
                        <input type="text" class="form-control <?php echo form_error('from_location') ? 'is-invalid' : ''; ?>" 
                               id="from_location" name="from_location" 
                               value="<?php echo set_value('from_location'); ?>" 
                               placeholder="สถานที่ปัจจุบัน" readonly required>
                        <div class="invalid-feedback">
                            <?php echo form_error('from_location'); ?>
                        </div>
                        <small class="form-text text-muted">สถานที่ปัจจุบันของครุภัณฑ์</small>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="to_location" class="required">ไปสถานที่</label>
                        <input type="text" class="form-control <?php echo form_error('to_location') ? 'is-invalid' : ''; ?>" 
                               id="to_location" name="to_location" 
                               value="<?php echo set_value('to_location'); ?>" 
                               placeholder="เช่น ห้อง 201 อาคาร B" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('to_location'); ?>
                        </div>
                        <small class="form-text text-muted">สถานที่ใหม่ที่จะโอนย้ายไป</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="transfer_date" class="required">วันที่โอนย้าย</label>
                        <input type="date" class="form-control <?php echo form_error('transfer_date') ? 'is-invalid' : ''; ?>" 
                               id="transfer_date" name="transfer_date" 
                               value="<?php echo set_value('transfer_date', date('Y-m-d')); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('transfer_date'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="transferred_by" class="required">ผู้ดำเนินการ</label>
                        <input type="text" class="form-control <?php echo form_error('transferred_by') ? 'is-invalid' : ''; ?>" 
                               id="transferred_by" name="transferred_by" 
                               value="<?php echo set_value('transferred_by'); ?>" 
                               placeholder="ชื่อ-นามสกุล ผู้ดำเนินการ" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('transferred_by'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="reason" class="required">เหตุผลการโอนย้าย</label>
                    <select class="form-control <?php echo form_error('reason') ? 'is-invalid' : ''; ?>" 
                            id="reason" name="reason" required>
                        <option value="">เลือกเหตุผล</option>
                        <option value="ปรับปรุงการใช้งาน" <?php echo set_select('reason', 'ปรับปรุงการใช้งาน'); ?>>ปรับปรุงการใช้งาน</option>
                        <option value="เปลี่ยนแปลงโครงสร้างองค์กร" <?php echo set_select('reason', 'เปลี่ยนแปลงโครงสร้างองค์กร'); ?>>เปลี่ยนแปลงโครงสร้างองค์กร</option>
                        <option value="ย้ายหน่วยงาน" <?php echo set_select('reason', 'ย้ายหน่วยงาน'); ?>>ย้ายหน่วยงาน</option>
                        <option value="ซ่อมแซม/บำรุงรักษา" <?php echo set_select('reason', 'ซ่อมแซม/บำรุงรักษา'); ?>>ซ่อมแซม/บำรุงรักษา</option>
                        <option value="เพิ่มประสิทธิภาพการทำงาน" <?php echo set_select('reason', 'เพิ่มประสิทธิภาพการทำงาน'); ?>>เพิ่มประสิทธิภาพการทำงาน</option>
                        <option value="ปรับปรุงสภาพแวดล้อม" <?php echo set_select('reason', 'ปรับปรุงสภาพแวดล้อม'); ?>>ปรับปรุงสภาพแวดล้อม</option>
                        <option value="อื่นๆ" <?php echo set_select('reason', 'อื่นๆ'); ?>>อื่นๆ</option>
                    </select>
                    <div class="invalid-feedback">
                        <?php echo form_error('reason'); ?>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="notes">หมายเหตุเพิ่มเติม</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3" 
                              placeholder="รายละเอียดเพิ่มเติม, เงื่อนไขพิเศษ..."><?php echo set_value('notes'); ?></textarea>
                    <small class="form-text text-muted">ข้อมูลเพิ่มเติมเกี่ยวกับการโอนย้าย</small>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> บันทึกการโอนย้าย
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo base_url('transfers'); ?>" class="btn btn-secondary btn-block">
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
                        <label>สถานที่ปัจจุบัน:</label>
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
                <h6>การโอนย้ายครุภัณฑ์:</h6>
                <ul class="small">
                    <li><strong>ตรวจสอบ:</strong> สถานที่ปัจจุบันของครุภัณฑ์</li>
                    <li><strong>ระบุ:</strong> สถานที่ใหม่ให้ชัดเจน</li>
                    <li><strong>เหตุผล:</strong> ระบุเหตุผลการโอนย้าย</li>
                    <li><strong>ผู้ดำเนินการ:</strong> ระบุผู้รับผิดชอบ</li>
                </ul>
                
                <hr>
                
                <h6>ข้อควรระวัง:</h6>
                <ul class="small">
                    <li>ตรวจสอบสภาพครุภัณฑ์ก่อนโอนย้าย</li>
                    <li>แจ้งผู้เกี่ยวข้องทราบล่วงหน้า</li>
                    <li>จัดเตรียมอุปกรณ์ขนย้าย</li>
                    <li>ตรวจสอบการติดตั้งในสถานที่ใหม่</li>
                </ul>
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-lightbulb"></i>
                    <strong>เคล็ดลับ:</strong> ควรถ่ายรูปครุภัณฑ์ก่อนและหลังการโอนย้าย
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
                url: '<?php echo base_url('transfers/api_get_asset_info'); ?>',
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
                        
                        // Update from_location field
                        $('#from_location').val(asset.current_location);
                        
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
        $('#from_location').val('');
    }
    
    // Form validation
    $('#transferForm').on('submit', function(e) {
        var isValid = true;
        
        // Check required fields
        $(this).find('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Check if from_location and to_location are different
        var fromLocation = $('#from_location').val();
        var toLocation = $('#to_location').val();
        
        if (fromLocation && toLocation && fromLocation === toLocation) {
            $('#to_location').addClass('is-invalid');
            showAlert('danger', 'สถานที่ใหม่ต้องไม่เหมือนกับสถานที่เดิม');
            isValid = false;
        }
        
        // Check transfer date
        var transferDate = new Date($('#transfer_date').val());
        var today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (transferDate < today) {
            $('#transfer_date').addClass('is-invalid');
            showAlert('warning', 'วันที่โอนย้ายไม่ควรเป็นวันที่ในอดีต');
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
    
    // Location suggestions
    var locationSuggestions = [
        'ห้อง 101 อาคาร A',
        'ห้อง 201 อาคาร B',
        'ห้องประชุม 1',
        'ห้องประชุม 2',
        'ห้องคอมพิวเตอร์',
        'ห้องสมุด',
        'ห้องพักครู',
        'ห้องผู้อำนวยการ',
        'ห้องธุรการ',
        'โรงยิม',
        'โรงอาหาร'
    ];
    
    // Add autocomplete to location fields
    $('#to_location').autocomplete({
        source: locationSuggestions,
        minLength: 0
    }).focus(function() {
        $(this).autocomplete('search', '');
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

.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
}
</style>

