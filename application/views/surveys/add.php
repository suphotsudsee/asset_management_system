<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-plus"></i> เพิ่มข้อมูลสำรวจครุภัณฑ์
                    </h4>
                </div>
                <div class="card-body">
                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    
                    <?php echo form_open('surveys/add', array('class' => 'needs-validation', 'novalidate' => '')); ?>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="survey_year">ปีที่สำรวจ <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="survey_year" name="survey_year" 
                                       value="<?php echo set_value('survey_year', date('Y')); ?>" 
                                       min="2020" max="<?php echo date('Y') + 1; ?>" required>
                                <div class="invalid-feedback">
                                    กรุณาระบุปีที่สำรวจ
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="survey_date">วันที่สำรวจ <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="survey_date" name="survey_date" 
                                       value="<?php echo set_value('survey_date', date('Y-m-d')); ?>" required>
                                <div class="invalid-feedback">
                                    กรุณาระบุวันที่สำรวจ
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="asset_id">ครุภัณฑ์ <span class="text-danger">*</span></label>
                        <select class="form-control" id="asset_id" name="asset_id" required>
                            <option value="">-- เลือกครุภัณฑ์ --</option>
                            <?php foreach($assets as $asset): ?>
                                <option value="<?php echo $asset['asset_id']; ?>" 
                                        <?php echo set_select('asset_id', $asset['asset_id']); ?>
                                        data-type="<?php echo $asset['asset_type']; ?>"
                                        data-location="<?php echo $asset['current_location']; ?>"
                                        data-serial="<?php echo $asset['serial_number']; ?>">
                                    <?php echo $asset['asset_name']; ?> (<?php echo $asset['serial_number']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="invalid-feedback">
                            กรุณาเลือกครุภัณฑ์
                        </div>
                    </div>

                    <!-- Asset Information Display -->
                    <div id="assetInfo" class="alert alert-info" style="display: none;">
                        <h6><i class="fas fa-info-circle"></i> ข้อมูลครุภัณฑ์</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <strong>ประเภท:</strong> <span id="assetType">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>หมายเลขซีเรียล:</strong> <span id="assetSerial">-</span>
                            </div>
                            <div class="col-md-4">
                                <strong>สถานที่:</strong> <span id="assetLocation">-</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="condition">สภาพครุภัณฑ์ <span class="text-danger">*</span></label>
                        <select class="form-control" id="condition" name="condition" required>
                            <option value="">-- เลือกสภาพ --</option>
                            <option value="ดี" <?php echo set_select('condition', 'ดี'); ?>>ดี</option>
                            <option value="พอใช้" <?php echo set_select('condition', 'พอใช้'); ?>>พอใช้</option>
                            <option value="ชำรุด" <?php echo set_select('condition', 'ชำรุด'); ?>>ชำรุด</option>
                            <option value="ไม่สามารถใช้งานได้" <?php echo set_select('condition', 'ไม่สามารถใช้งานได้'); ?>>ไม่สามารถใช้งานได้</option>
                        </select>
                        <div class="invalid-feedback">
                            กรุณาเลือกสภาพครุภัณฑ์
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="surveyed_by">ผู้สำรวจ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="surveyed_by" name="surveyed_by" 
                               value="<?php echo set_value('surveyed_by'); ?>" 
                               placeholder="ระบุชื่อผู้สำรวจ" required>
                        <div class="invalid-feedback">
                            กรุณาระบุชื่อผู้สำรวจ
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="notes">หมายเหตุ</label>
                        <textarea class="form-control" id="notes" name="notes" rows="4" 
                                  placeholder="บันทึกเพิ่มเติม (ถ้ามี)"><?php echo set_value('notes'); ?></textarea>
                        <small class="form-text text-muted">
                            ระบุรายละเอียดเพิ่มเติมเกี่ยวกับสภาพครุภัณฑ์ หรือข้อสังเกต
                        </small>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save"></i> บันทึกข้อมูล
                                </button>
                            </div>
                            <div class="col-md-6">
                                <a href="<?php echo site_url('surveys'); ?>" class="btn btn-secondary btn-block">
                                    <i class="fas fa-arrow-left"></i> ยกเลิก
                                </a>
                            </div>
                        </div>
                    </div>

                    <?php echo form_close(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Asset selection change handler
    $('#asset_id').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        
        if (selectedOption.val()) {
            $('#assetType').text(selectedOption.data('type'));
            $('#assetSerial').text(selectedOption.data('serial'));
            $('#assetLocation').text(selectedOption.data('location'));
            $('#assetInfo').show();
        } else {
            $('#assetInfo').hide();
        }
    });

    // Form validation
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            var forms = document.getElementsByClassName('needs-validation');
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();

    // Auto-suggest surveyor names
    var surveyors = [
        'คณะกรรมการสำรวจ',
        'หัวหน้าแผนกพัสดุ',
        'เจ้าหน้าที่พัสดุ',
        'ผู้อำนวยการ',
        'รองผู้อำนวยการ'
    ];

    $('#surveyed_by').autocomplete({
        source: surveyors,
        minLength: 0
    }).focus(function() {
        $(this).autocomplete('search', '');
    });
});
</script>

<style>
.was-validated .form-control:valid {
    border-color: #28a745;
}

.was-validated .form-control:invalid {
    border-color: #dc3545;
}

.alert-info {
    background-color: #d1ecf1;
    border-color: #bee5eb;
    color: #0c5460;
}

#assetInfo {
    margin-top: 10px;
    margin-bottom: 20px;
}

.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
}
</style>

