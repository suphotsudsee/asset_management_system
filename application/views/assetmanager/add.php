<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus"></i>
        เพิ่มครุภัณฑ์ใหม่
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('assetmanager'); ?>" class="btn btn-secondary">
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
                    ข้อมูลครุภัณฑ์
                </h6>
            </div>
            <div class="card-body">
                <?php echo form_open('assets/store', array('id' => 'assetForm')); ?>
                
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="asset_name" class="required">ชื่อครุภัณฑ์</label>
                        <input type="text" class="form-control <?php echo form_error('asset_name') ? 'is-invalid' : ''; ?>" 
                               id="asset_name" name="asset_name" 
                               value="<?php echo set_value('asset_name'); ?>" 
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
                            <?php if (!empty($asset_types)): ?>
                                <?php foreach ($asset_types as $type): ?>
                                    <option value="<?php echo htmlspecialchars($type); ?>" <?php echo set_select('asset_type', $type); ?>>
                                        <?php echo htmlspecialchars($type); ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
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
                               value="<?php echo set_value('serial_number'); ?>" 
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
                               value="<?php echo set_value('purchase_date'); ?>" required>
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
                                   value="<?php echo set_value('purchase_price'); ?>" 
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
                                   value="<?php echo set_value('depreciation_rate', '20'); ?>" 
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
                               value="<?php echo set_value('current_location'); ?>" 
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
                            <option value="ใช้งาน" <?php echo set_select('status', 'ใช้งาน', true); ?>>ใช้งาน</option>
                            <option value="ชำรุด" <?php echo set_select('status', 'ชำรุด'); ?>>ชำรุด</option>
                            <option value="ซ่อมแซม" <?php echo set_select('status', 'ซ่อมแซม'); ?>>ซ่อมแซม</option>
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
                            <option value="จัดซื้อ" <?php echo set_select('acquisition_method', 'จัดซื้อ', true); ?>>จัดซื้อ</option>
                            <option value="รับบริจาค" <?php echo set_select('acquisition_method', 'รับบริจาค'); ?>>รับบริจาค</option>
                            <option value="โอนจากหน่วยงานอื่น" <?php echo set_select('acquisition_method', 'โอนจากหน่วยงานอื่น'); ?>>โอนจากหน่วยงานอื่น</option>
                            <option value="เช่า" <?php echo set_select('acquisition_method', 'เช่า'); ?>>เช่า</option>
                            <option value="อื่นๆ" <?php echo set_select('acquisition_method', 'อื่นๆ'); ?>>อื่นๆ</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('acquisition_method'); ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="responsible_person" class="required">ผู้รับผิดชอบ</label>
                        <input type="text" class="form-control <?php echo form_error('responsible_person') ? 'is-invalid' : ''; ?>" 
                               id="responsible_person" name="responsible_person" 
                               value="<?php echo set_value('responsible_person'); ?>" 
                               placeholder="ชื่อ-นามสกุล ผู้รับผิดชอบ" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('responsible_person'); ?>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="warranty_info">ข้อมูลการรับประกัน</label>
                    <textarea class="form-control" id="warranty_info" name="warranty_info" rows="3" 
                              placeholder="ระยะเวลาการรับประกัน, เงื่อนไข, ข้อมูลติดต่อ..."><?php echo set_value('warranty_info'); ?></textarea>
                    <small class="form-text text-muted">ข้อมูลการรับประกันจากผู้ขาย/ผู้ผลิต</small>
                </div>
                
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> บันทึกข้อมูล
                            </button>
                        </div>
                        <div class="col-md-6">
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
                <h6>การกรอกข้อมูล:</h6>
                <ul class="small">
                    <li><strong>ชื่อครุภัณฑ์:</strong> ระบุชื่อเต็มและรุ่นของครุภัณฑ์</li>
                    <li><strong>หมายเลขซีเรียล:</strong> ตรวจสอบจากฉลากของผู้ผลิต</li>
                    <li><strong>อัตราค่าเสื่อมราคา:</strong> ตามหลักเกณฑ์ของหน่วยงาน</li>
                    <li><strong>สถานที่ตั้ง:</strong> ระบุให้ชัดเจนเพื่อง่ายต่อการค้นหา</li>
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
                
                <div class="alert alert-info mt-3">
                    <i class="fas fa-lightbulb"></i>
                    <strong>เคล็ดลับ:</strong> กรอกข้อมูลให้ครบถ้วนเพื่อความสะดวกในการจัดการและออกรายงาน
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
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Auto-calculate depreciation
    function updateDepreciationCalculation() {
        var price = parseFloat($('#purchase_price').val()) || 0;
        var rate = parseFloat($('#depreciation_rate').val()) || 0;
        var annual = price * (rate / 100);
        var monthly = annual / 12;
        
        $('#calcPrice').text(price.toLocaleString('th-TH') + ' บาท');
        $('#calcRate').text(rate + '% ต่อปี');
        $('#calcAnnual').text(annual.toLocaleString('th-TH', {maximumFractionDigits: 2}) + ' บาท');
        $('#calcMonthly').text(monthly.toLocaleString('th-TH', {maximumFractionDigits: 2}) + ' บาท');
    }
    
    $('#purchase_price, #depreciation_rate').on('input', updateDepreciationCalculation);
    
    // Auto-suggest depreciation rate based on asset type
    $('#asset_type').on('change', function() {
        var type = $(this).val();
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
        
        $('#depreciation_rate').val(suggestedRate);
        updateDepreciationCalculation();
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
    
    // Initialize calculation
    updateDepreciationCalculation();
});
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

#depreciationCalculator .form-group {
    margin-bottom: 1rem;
}

#depreciationCalculator label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}
</style>

