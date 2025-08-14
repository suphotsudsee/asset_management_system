<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-plus"></i>
        เพิ่มข้อมูลค้ำประกันสัญญา
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('guarantees'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> กลับ
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Validation Errors -->
<?php if (validation_errors()): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <strong>กรุณาตรวจสอบข้อมูล:</strong>
        <?php echo validation_errors(); ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<form action="<?php echo base_url('guarantees/store'); ?>" method="post" id="guaranteeForm">
    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i>
                        ข้อมูลค้ำประกันสัญญา
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="asset_id" class="required">เลือกครุภัณฑ์</label>
                            <select name="asset_id" id="asset_id" class="form-control" required>
    <option value="">-- เลือกครุภัณฑ์ --</option>
    <?php foreach (($assets ?? []) as $a): 
        $id    = (int)($a['asset_id'] ?? 0);
        $name  = $a['asset_name'] ?? '-';
        // ใช้ asset_code ถ้ามี, ถ้าไม่มีก็ fallback เป็น serial_number, ถ้าไม่มีอีกใส่ค่าว่าง
        $code  = $a['asset_code'] ?? ($a['serial_number'] ?? '');
        $label = $code ? "[$code] $name" : $name;
    ?>
      <option value="<?= $id ?>"
        <?= set_select('asset_id', $id, (isset($selected_asset_id) && (int)$selected_asset_id === $id)); ?>>
        <?= html_escape($label) ?>
      </option>
    <?php endforeach; ?>
</select>

                            <small class="form-text text-muted">
                                แสดงเฉพาะครุภัณฑ์ที่ยังไม่มีค้ำประกันที่ใช้งานอยู่
                            </small>
                        </div>
                    </div>

                    <!-- Asset Information Display -->
                    <div id="assetInfo" class="row mb-3" style="display: none;">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-box"></i>
                                        ข้อมูลครุภัณฑ์
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>รหัส:</strong> <span id="displayAssetCode">-</span></p>
                                            <p><strong>ชื่อ:</strong> <span id="displayAssetName">-</span></p>
                                            <p><strong>ประเภท:</strong> <span id="displayCategory">-</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>สถานที่:</strong> <span id="displayLocation">-</span></p>
                                            <p><strong>วันที่จัดซื้อ:</strong> <span id="displayPurchaseDate">-</span></p>
                                            <p><strong>ราคาทุน:</strong> <span id="displayPurchasePrice">-</span> บาท</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="guarantee_type" class="required">ประเภทค้ำประกัน</label>
                            <select class="form-control" id="guarantee_type" name="guarantee_type" required>
                                <option value="">-- เลือกประเภท --</option>
                                <option value="ค้ำประกันสินค้า" <?php echo set_select('guarantee_type', 'ค้ำประกันสินค้า'); ?>>ค้ำประกันสินค้า</option>
                                <option value="ค้ำประกันการบริการ" <?php echo set_select('guarantee_type', 'ค้ำประกันการบริการ'); ?>>ค้ำประกันการบริการ</option>
                                <option value="ค้ำประกันชิ้นส่วน" <?php echo set_select('guarantee_type', 'ค้ำประกันชิ้นส่วน'); ?>>ค้ำประกันชิ้นส่วน</option>
                                <option value="ค้ำประกันแรงงาน" <?php echo set_select('guarantee_type', 'ค้ำประกันแรงงาน'); ?>>ค้ำประกันแรงงาน</option>
                                <option value="ค้ำประกันครบวงจร" <?php echo set_select('guarantee_type', 'ค้ำประกันครบวงจร'); ?>>ค้ำประกันครบวงจร</option>
                                <option value="อื่นๆ" <?php echo set_select('guarantee_type', 'อื่นๆ'); ?>>อื่นๆ</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="contract_number">เลขที่สัญญา</label>
                            <input type="text" class="form-control" id="contract_number" name="contract_number" 
                                   value="<?php echo set_value('contract_number'); ?>"
                                   placeholder="เลขที่สัญญาค้ำประกัน (ถ้ามี)">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_date" class="required">วันที่เริ่มต้น</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" 
                                   value="<?php echo set_value('start_date', date('Y-m-d')); ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_date" class="required">วันที่สิ้นสุด</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" 
                                   value="<?php echo set_value('end_date'); ?>" required>
                            <small class="form-text text-muted">
                                จำนวนวัน: <span id="guaranteeDays">0</span> วัน
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="vendor_name" class="required">ชื่อผู้จำหน่าย/ผู้ให้บริการ</label>
                            <input type="text" class="form-control" id="vendor_name" name="vendor_name" 
                                   value="<?php echo set_value('vendor_name'); ?>" required
                                   placeholder="ชื่อบริษัทหรือร้านค้า">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="vendor_contact" class="required">ข้อมูลติดต่อ</label>
                            <input type="text" class="form-control" id="vendor_contact" name="vendor_contact" 
                                   value="<?php echo set_value('vendor_contact'); ?>" required
                                   placeholder="เบอร์โทร, อีเมล, หรือที่อยู่">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="coverage_details">รายละเอียดความคุ้มครอง</label>
                        <textarea class="form-control" id="coverage_details" name="coverage_details" rows="4"
                                  placeholder="ระบุรายละเอียดสิ่งที่ได้รับความคุ้มครอง เช่น ชิ้นส่วน, การซ่อมแซม, การเปลี่ยน"><?php echo set_value('coverage_details'); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="terms_conditions">เงื่อนไขและข้อกำหนด</label>
                        <textarea class="form-control" id="terms_conditions" name="terms_conditions" rows="4"
                                  placeholder="เงื่อนไขการใช้ค้ำประกัน, ข้อจำกัด, ข้อยกเว้น"><?php echo set_value('terms_conditions'); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="claim_procedure">ขั้นตอนการเคลม</label>
                        <textarea class="form-control" id="claim_procedure" name="claim_procedure" rows="4"
                                  placeholder="ขั้นตอนและวิธีการเคลมประกัน, เอกสารที่ต้องใช้"><?php echo set_value('claim_procedure'); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="notes">หมายเหตุ</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"
                                  placeholder="ข้อมูลเพิ่มเติมหรือหมายเหตุอื่นๆ"><?php echo set_value('notes'); ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cog"></i>
                        การดำเนินการ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-save"></i> บันทึกข้อมูล
                        </button>
                    </div>
                    <div class="mb-3">
                        <a href="<?php echo base_url('guarantees'); ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle"></i>
                        คำแนะนำ
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-lightbulb"></i> เคล็ดลับ:</h6>
                        <ul class="mb-0 small">
                            <li>ตรวจสอบวันที่เริ่มต้นและสิ้นสุดให้ถูกต้อง</li>
                            <li>บันทึกข้อมูลติดต่อผู้จำหน่ายให้ครบถ้วน</li>
                            <li>ระบุรายละเอียดความคุ้มครองให้ชัดเจน</li>
                            <li>เก็บเอกสารสัญญาไว้เป็นหลักฐาน</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        ข้อควรระวัง
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <ul class="mb-0 small">
                            <li>ครุภัณฑ์หนึ่งชิ้นสามารถมีค้ำประกันได้หลายประเภท</li>
                            <li>ไม่สามารถมีค้ำประกันซ้อนทับกันในช่วงเวลาเดียวกัน</li>
                            <li>ตรวจสอบเงื่อนไขการเคลมก่อนบันทึก</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function() {
    // Asset selection change
    $('#asset_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            // Show asset information
            $('#displayAssetCode').text(selectedOption.data('asset-code'));
            $('#displayAssetName').text(selectedOption.data('asset-name'));
            $('#displayCategory').text(selectedOption.data('category'));
            $('#displayLocation').text(selectedOption.data('location'));
            $('#displayPurchaseDate').text(formatDate(selectedOption.data('purchase-date')));
            $('#displayPurchasePrice').text(selectedOption.data('purchase-price'));
            
            $('#assetInfo').slideDown();
            
            // Auto-suggest start date based on purchase date
            var purchaseDate = selectedOption.data('purchase-date');
            if (purchaseDate && !$('#start_date').val()) {
                $('#start_date').val(purchaseDate);
                calculateGuaranteeDays();
            }
        } else {
            $('#assetInfo').slideUp();
        }
    });
    
    // Date change events
    $('#start_date, #end_date').on('change', function() {
        calculateGuaranteeDays();
    });
    
    // Form validation
    $('#guaranteeForm').on('submit', function(e) {
        var startDate = new Date($('#start_date').val());
        var endDate = new Date($('#end_date').val());
        
        if (endDate <= startDate) {
            e.preventDefault();
            alert('วันที่สิ้นสุดต้องมากกว่าวันที่เริ่มต้น');
            return false;
        }
        
        if (!$('#asset_id').val()) {
            e.preventDefault();
            alert('กรุณาเลือกครุภัณฑ์');
            return false;
        }
    });
    
    // Initialize if asset is pre-selected
    if ($('#asset_id').val()) {
        $('#asset_id').trigger('change');
    }
    
    // Calculate guarantee days on page load
    calculateGuaranteeDays();
});

function calculateGuaranteeDays() {
    var startDate = $('#start_date').val();
    var endDate = $('#end_date').val();
    
    if (startDate && endDate) {
        var start = new Date(startDate);
        var end = new Date(endDate);
        var timeDiff = end.getTime() - start.getTime();
        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff > 0) {
            $('#guaranteeDays').text(daysDiff.toLocaleString());
        } else {
            $('#guaranteeDays').text('0');
        }
    } else {
        $('#guaranteeDays').text('0');
    }
}

function formatDate(dateString) {
    if (!dateString) return '-';
    
    var date = new Date(dateString);
    var day = String(date.getDate()).padStart(2, '0');
    var month = String(date.getMonth() + 1).padStart(2, '0');
    var year = date.getFullYear();
    
    return day + '/' + month + '/' + year;
}
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.card-header h6 {
    color: #5a5c69 !important;
}

.alert ul {
    padding-left: 1.2rem;
}

#assetInfo {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 200px;
    }
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-primary {
    background-color: #4e73df;
    border-color: #4e73df;
}

.btn-primary:hover {
    background-color: #2e59d9;
    border-color: #2653d4;
}
</style>

