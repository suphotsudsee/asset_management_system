<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit"></i>
        แก้ไขครุภัณฑ์: <?php echo $asset['asset_name']; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('assetmanager'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
            <a href="<?php echo base_url('assetmanager/view/' . $asset['asset_id']); ?>" class="btn btn-info">
                <i class="fas fa-eye"></i> ดูรายละเอียด
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
                    ข้อมูลครุภัณฑ์
                </h6>
            </div>
            <div class="card-body">
                <?php echo form_open('assetmanager/update/' . $asset['asset_id'], array('id' => 'assetForm')); ?>
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="asset_name" class="required">ชื่อครุภัณฑ์</label>
                        <input type="text" class="form-control <?php echo form_error('asset_name') ? 'is-invalid' : ''; ?>" 
                               id="asset_name" name="asset_name" 
                               value="<?php echo set_value('asset_name', $asset['asset_name']); ?>" 
                               placeholder="เช่น เครื่องคอมพิวเตอร์ Dell OptiPlex 7090" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('asset_name'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label for="asset_type" class="required">ประเภทครุภัณฑ์</label>
                        <select class="form-control <?php echo form_error('asset_type') ? 'is-invalid' : ''; ?>" 
                                id="asset_type" name="asset_type" required>
                            <option value="">เลือกประเภท</option>
                            <option value="คอมพิวเตอร์" <?php echo set_select('asset_type', 'คอมพิวเตอร์', $asset['asset_type'] == 'คอมพิวเตอร์'); ?>>คอมพิวเตอร์</option>
                            <option value="เครื่องพิมพ์" <?php echo set_select('asset_type', 'เครื่องพิมพ์', $asset['asset_type'] == 'เครื่องพิมพ์'); ?>>เครื่องพิมพ์</option>
                            <option value="เครื่องถ่ายเอกสาร" <?php echo set_select('asset_type', 'เครื่องถ่ายเอกสาร', $asset['asset_type'] == 'เครื่องถ่ายเอกสาร'); ?>>เครื่องถ่ายเอกสาร</option>
                            <option value="โปรเจคเตอร์" <?php echo set_select('asset_type', 'โปรเจคเตอร์', $asset['asset_type'] == 'โปรเจคเตอร์'); ?>>โปรเจคเตอร์</option>
                            <option value="เครื่องปรับอากาศ" <?php echo set_select('asset_type', 'เครื่องปรับอากาศ', $asset['asset_type'] == 'เครื่องปรับอากาศ'); ?>>เครื่องปรับอากาศ</option>
                            <option value="รถยนต์" <?php echo set_select('asset_type', 'รถยนต์', $asset['asset_type'] == 'รถยนต์'); ?>>รถยนต์</option>
                            <option value="เฟอร์นิเจอร์" <?php echo set_select('asset_type', 'เฟอร์นิเจอร์', $asset['asset_type'] == 'เฟอร์นิเจอร์'); ?>>เฟอร์นิเจอร์</option>
                            <option value="อุปกรณ์เครือข่าย" <?php echo set_select('asset_type', 'อุปกรณ์เครือข่าย', $asset['asset_type'] == 'อุปกรณ์เครือข่าย'); ?>>อุปกรณ์เครือข่าย</option>
                            <option value="อื่นๆ" <?php echo set_select('asset_type', 'อื่นๆ', $asset['asset_type'] == 'อื่นๆ'); ?>>อื่นๆ</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('asset_type'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="serial_number">หมายเลขซีเรียล</label>
                        <input type="text" class="form-control <?php echo form_error('serial_number') ? 'is-invalid' : ''; ?>" 
                               id="serial_number" name="serial_number" 
                               value="<?php echo set_value('serial_number', $asset['serial_number']); ?>" 
                               placeholder="เช่น ABC123456789">
                        <small class="form-text text-muted">หมายเลขซีเรียลของผู้ผลิต (ถ้ามี)</small>
                        <div class="invalid-feedback">
                            <?php echo form_error('serial_number'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="purchase_date" class="required">วันที่จัดซื้อ</label>
                        <input type="date" class="form-control <?php echo form_error('purchase_date') ? 'is-invalid' : ''; ?>" 
                               id="purchase_date" name="purchase_date" 
                               value="<?php echo set_value('purchase_date', $asset['purchase_date']); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('purchase_date'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="purchase_price" class="required">ราคาจัดซื้อ (บาท)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('purchase_price') ? 'is-invalid' : ''; ?>" 
                                   id="purchase_price" name="purchase_price" 
                                   value="<?php echo set_value('purchase_price', $asset['purchase_price']); ?>" 
                                   step="0.01" min="0" placeholder="0.00" required>
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('purchase_price'); ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="depreciation_rate" class="required">อัตราค่าเสื่อมราคา (%/ปี)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('depreciation_rate') ? 'is-invalid' : ''; ?>" 
                                   id="depreciation_rate" name="depreciation_rate" 
                                   value="<?php echo set_value('depreciation_rate', $asset['depreciation_rate']); ?>" 
                                   step="0.01" min="0" max="100" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('depreciation_rate'); ?>
                            </div>
                        </div>
                        <small class="form-text text-muted">อัตราค่าเสื่อมราคาต่อปี (ปกติ 20%)</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="current_location" class="required">สถานที่ตั้งปัจจุบัน</label>
                        <input type="text" class="form-control <?php echo form_error('current_location') ? 'is-invalid' : ''; ?>" 
                               id="current_location" name="current_location" 
                               value="<?php echo set_value('current_location', $asset['current_location']); ?>" 
                               placeholder="เช่น ห้อง 101 อาคาร A" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('current_location'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="status" class="required">สถานะ</label>
                        <select class="form-control <?php echo form_error('status') ? 'is-invalid' : ''; ?>" 
                                id="status" name="status" required>
                            <option value="">เลือกสถานะ</option>
                            <option value="ใช้งาน" <?php echo set_select('status', 'ใช้งาน', $asset['status'] == 'ใช้งาน'); ?>>ใช้งาน</option>
                            <option value="ชำรุด" <?php echo set_select('status', 'ชำรุด', $asset['status'] == 'ชำรุด'); ?>>ชำรุด</option>
                            <option value="ซ่อมแซม" <?php echo set_select('status', 'ซ่อมแซม', $asset['status'] == 'ซ่อมแซม'); ?>>ซ่อมแซม</option>
                            <option value="เลิกใช้งาน" <?php echo set_select('status', 'เลิกใช้งาน', $asset['status'] == 'เลิกใช้งาน'); ?>>เลิกใช้งาน</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('status'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="acquisition_method" class="required">วิธีการได้มา</label>
                        <select class="form-control <?php echo form_error('acquisition_method') ? 'is-invalid' : ''; ?>" 
                                id="acquisition_method" name="acquisition_method" required>
                            <option value="">เลือกวิธีการ</option>
                            <option value="จัดซื้อ" <?php echo set_select('acquisition_method', 'จัดซื้อ', $asset['acquisition_method'] == 'จัดซื้อ'); ?>>จัดซื้อ</option>
                            <option value="รับบริจาค" <?php echo set_select('acquisition_method', 'รับบริจาค', $asset['acquisition_method'] == 'รับบริจาค'); ?>>รับบริจาค</option>
                            <option value="โอนจากหน่วยงานอื่น" <?php echo set_select('acquisition_method', 'โอนจากหน่วยงานอื่น', $asset['acquisition_method'] == 'โอนจากหน่วยงานอื่น'); ?>>โอนจากหน่วยงานอื่น</option>
                            <option value="เช่า" <?php echo set_select('acquisition_method', 'เช่า', $asset['acquisition_method'] == 'เช่า'); ?>>เช่า</option>
                            <option value="อื่นๆ" <?php echo set_select('acquisition_method', 'อื่นๆ', $asset['acquisition_method'] == 'อื่นๆ'); ?>>อื่นๆ</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('acquisition_method'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="responsible_person" class="required">ผู้รับผิดชอบ</label>
                        <input type="text" class="form-control <?php echo form_error('responsible_person') ? 'is-invalid' : ''; ?>" 
                               id="responsible_person" name="responsible_person" 
                               value="<?php echo set_value('responsible_person', $asset['responsible_person']); ?>" 
                               placeholder="ชื่อ-นามสกุล ผู้รับผิดชอบ" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('responsible_person'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="warranty_info">ข้อมูลการรับประกัน</label>
                    <textarea class="form-control" id="warranty_info" name="warranty_info" rows="3" 
                              placeholder="ระยะเวลาการรับประกัน, เงื่อนไข, ข้อมูลติดต่อ..."><?php echo set_value('warranty_info', $asset['warranty_info']); ?></textarea>
                    <small class="form-text text-muted">ข้อมูลการรับประกันจากผู้ขาย/ผู้ผลิต</small>
                </div>

                <!-- แสดงข้อมูลระบบ -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">ข้อมูลระบบ</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <small class="text-muted">รหัสครุภัณฑ์:</small><br>
                                        <strong><?php echo $asset['asset_id']; ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">วันที่สร้าง:</small><br>
                                        <strong><?php echo date('d/m/Y H:i', strtotime($asset['created_at'])); ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">แก้ไขล่าสุด:</small><br>
                                        <strong><?php echo $asset['updated_at'] ? date('d/m/Y H:i', strtotime($asset['updated_at'])) : 'ไม่เคยแก้ไข'; ?></strong>
                                    </div>
                                    <div class="col-md-3">
                                        <small class="text-muted">สร้างโดย:</small><br>
                                        <strong><?php echo $asset['created_by'] ?? 'ระบบ'; ?></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> บันทึกการแก้ไข
                            </button>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo base_url('assetmanager/view/' . $asset['asset_id']); ?>" class="btn btn-info btn-block">
                                <i class="fas fa-eye"></i> ดูรายละเอียด
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="<?php echo base_url('assetmanager'); ?>" class="btn btn-secondary btn-block">
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
                    คำแนะนำ
                </h6>
            </div>
            <div class="card-body">
                <h6>การแก้ไขข้อมูล:</h6>
                <ul class="small">
                    <li><strong>ระวัง:</strong> การเปลี่ยนแปลงข้อมูลสำคัญ เช่น ราคาจัดซื้อจะส่งผลต่อการคำนวณค่าเสื่อมราคา</li>
                    <li><strong>รหัสครุภัณฑ์:</strong> ไม่สามารถเปลี่ยนแปลงได้</li>
                    <li><strong>การเปลี่ยนสถานะ:</strong> อาจส่งผลต่อรายงานและการติดตาม</li>
                </ul>
                
                <hr>
                
                <h6>อัตราค่าเสื่อมราคาแนะนำ:</h6>
                <ul class="small">
                    <li>คอมพิวเตอร์: 25-33%</li>
                    <li>เครื่องพิมพ์: 20%</li>
                    <li>เฟอร์นิเจอร์: 10%</li>
                    <li>รถยนต์: 20%</li>
                    <li>เครื่องปรับอากาศ: 10%</li>
                </ul>
                
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>คำเตือน:</strong> การแก้ไขข้อมูลจะมีผลทันที โปรดตรวจสอบความถูกต้องก่อนบันทึก
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-calculator"></i>
                    คำนวณค่าเสื่อมราคา
                </h6>
            </div>
            <div class="card-body">
                <div id="depreciationCalculator">
                    <div class="form-group">
                        <label>ราคาซื้อ:</label>
                        <div class="h5" id="calcPrice">0 บาท</div>
                    </div>
                    <div class="form-group">
                        <label>อัตราค่าเสื่อมราคา:</label>
                        <div class="h6" id="calcRate">0% ต่อปี</div>
                    </div>
                    <div class="form-group">
                        <label>ค่าเสื่อมราคาต่อปี:</label>
                        <div class="h6 text-danger" id="calcAnnual">0 บาท</div>
                    </div>
                    <div class="form-group">
                        <label>ค่าเสื่อมราคาต่อเดือน:</label>
                        <div class="h6 text-warning" id="calcMonthly">0 บาท</div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>อายุการใช้งาน:</label>
                        <div class="h6 text-info" id="calcAge">0 ปี 0 เดือน</div>
                    </div>
                    <div class="form-group">
                        <label>มูลค่าปัจจุบัน (โดยประมาณ):</label>
                        <div class="h6 text-success" id="calcCurrentValue">0 บาท</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-history"></i>
                    ข้อมูลประวัติ
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline-item">
                    <small class="text-muted">สร้างข้อมูล:</small><br>
                    <strong><?php echo date('d/m/Y H:i', strtotime($asset['created_at'])); ?></strong>
                </div>
                <?php if ($asset['updated_at']): ?>
                <div class="timeline-item mt-2">
                    <small class="text-muted">แก้ไขล่าสุด:</small><br>
                    <strong><?php echo date('d/m/Y H:i', strtotime($asset['updated_at'])); ?></strong>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-calculate depreciation and current value
    function updateDepreciationCalculation() {
        var price = parseFloat($('#purchase_price').val()) || 0;
        var rate = parseFloat($('#depreciation_rate').val()) || 0;
        var purchaseDate = new Date($('#purchase_date').val());
        var currentDate = new Date();
        
        var annual = price * (rate / 100);
        var monthly = annual / 12;
        
        // Calculate age
        var ageInMonths = (currentDate.getFullYear() - purchaseDate.getFullYear()) * 12;
        ageInMonths -= purchaseDate.getMonth();
        ageInMonths += currentDate.getMonth();
        
        var ageYears = Math.floor(ageInMonths / 12);
        var ageMonthsRemaining = ageInMonths % 12;
        
        // Calculate current value (simple straight-line depreciation)
        var totalDepreciation = annual * (ageInMonths / 12);
        var currentValue = Math.max(0, price - totalDepreciation);
        
        $('#calcPrice').text(price.toLocaleString('th-TH') + ' บาท');
        $('#calcRate').text(rate + '% ต่อปี');
        $('#calcAnnual').text(annual.toLocaleString('th-TH', {maximumFractionDigits: 2}) + ' บาท');
        $('#calcMonthly').text(monthly.toLocaleString('th-TH', {maximumFractionDigits: 2}) + ' บาท');
        $('#calcAge').text(ageYears + ' ปี ' + ageMonthsRemaining + ' เดือน');
        $('#calcCurrentValue').text(currentValue.toLocaleString('th-TH', {maximumFractionDigits: 2}) + ' บาท');
    }
    
    $('#purchase_price, #depreciation_rate, #purchase_date').on('input change', updateDepreciationCalculation);
    
    // Auto-suggest depreciation rate based on asset type (only if different from current)
    $('#asset_type').on('change', function() {
        var type = $(this).val();
        var currentRate = parseFloat($('#depreciation_rate').val());
        var suggestedRate = 20; // Default
        
        switch(type) {
            case 'คอมพิวเตอร์':
                suggestedRate = 25;
                break;
            case 'เครื่องพิมพ์':
                suggestedRate = 20;
                break;
            case 'เฟอร์นิเจอร์':
                suggestedRate = 10;
                break;
            case 'รถยนต์':
                suggestedRate = 20;
                break;
            case 'เครื่องปรับอากาศ':
                suggestedRate = 10;
                break;
            case 'โปรเจคเตอร์':
                suggestedRate = 20;
                break;
            case 'อุปกรณ์เครือข่าย':
                suggestedRate = 25;
                break;
        }
        
        // Only suggest if significantly different
        if (Math.abs(currentRate - suggestedRate) > 5) {
            if (confirm('อัตราค่าเสื่อมราคาแนะนำสำหรับ ' + type + ' คือ ' + suggestedRate + '% ต้องการเปลี่ยนแปลงหรือไม่?')) {
                $('#depreciation_rate').val(suggestedRate);
                updateDepreciationCalculation();
            }
        }
    });
    
    // Form validation
    $('#assetForm').on('submit', function(e) {
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
        
        // Check price
        var price = parseFloat($('#purchase_price').val());
        if (price <= 0) {
            $('#purchase_price').addClass('is-invalid');
            showAlert('danger', 'ราคาจัดซื้อต้องมากกว่า 0');
            isValid = false;
        }
        
        // Check depreciation rate
        var rate = parseFloat($('#depreciation_rate').val());
        if (rate < 0 || rate > 100) {
            $('#depreciation_rate').addClass('is-invalid');
            showAlert('danger', 'อัตราค่าเสื่อมราคาต้องอยู่ระหว่าง 0-100%');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
        }
    });
    
    // Remove validation class on input
    $('input, select').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Confirmation dialog for sensitive changes
    $('#purchase_price, #depreciation_rate').on('change', function() {
        var field = $(this);
        var fieldName = field.attr('id') === 'purchase_price' ? 'ราคาจัดซื้อ' : 'อัตราค่าเสื่อมราคา';
        
        // Show warning for significant changes
        setTimeout(function() {
            if (field.val() !== field.data('original-value')) {
                showAlert('warning', 'การเปลี่ยนแปลง' + fieldName + 'จะส่งผลต่อการคำนวณค่าเสื่อมราคา');
            }
        }, 500);
    });
    
    // Store original values
    $('#purchase_price, #depreciation_rate').each(function() {
        $(this).data('original-value', $(this).val());
    });
    
    // Initialize calculation
    updateDepreciationCalculation();
});

// Helper function for alerts
function showAlert(type, message) {
    var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                    message +
                    '<button type="button" class="close" data-dismiss="