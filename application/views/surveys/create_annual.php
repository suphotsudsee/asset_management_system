<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-calendar-check"></i> สร้างการสำรวจครุภัณฑ์ประจำปี
                    </h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> การสร้างการสำรวจประจำปีจะสร้างรายการสำรวจสำหรับครุภัณฑ์ทั้งหมดที่ยังไม่ได้รับการสำรวจในปีที่ระบุ
                    </div>
                    
                    <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                    
                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php echo form_open('surveys/store_annual_survey', array('class' => 'needs-validation', 'novalidate' => '')); ?>
                    
                    <div class="form-group">
                        <label for="survey_year">ปีที่สำรวจ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="survey_year" name="survey_year" 
                               value="<?php echo set_value('survey_year', $current_year); ?>" 
                               min="2020" max="<?php echo date('Y') + 1; ?>" required>
                        <div class="invalid-feedback">
                            กรุณาระบุปีที่สำรวจ
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="survey_date">วันที่สำรวจ <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" id="survey_date" name="survey_date" 
                               value="<?php echo set_value('survey_date', date('Y-m-d')); ?>" required>
                        <div class="invalid-feedback">
                            กรุณาระบุวันที่สำรวจ
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="surveyed_by">ผู้สำรวจ <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="surveyed_by" name="surveyed_by" 
                               value="<?php echo set_value('surveyed_by', 'คณะกรรมการสำรวจครุภัณฑ์ประจำปี'); ?>" 
                               placeholder="ระบุชื่อผู้สำรวจหรือคณะกรรมการ" required>
                        <div class="invalid-feedback">
                            กรุณาระบุชื่อผู้สำรวจ
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-save"></i> สร้างการสำรวจประจำปี
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
        'คณะกรรมการสำรวจครุภัณฑ์ประจำปี',
        'หัวหน้าแผนกพัสดุ',
        'เจ้าหน้าที่พัสดุ',
        'ผู้อำนวยการ',
        'รองผู้อำนวยการ'
    ];
    
    if ($.ui && $.ui.autocomplete) {
        $('#surveyed_by').autocomplete({
            source: surveyors,
            minLength: 0
        }).focus(function() {
            $(this).autocomplete('search', '');
        });
    }
});
</script>

<style>
.was-validated .form-control:valid {
    border-color: #28a745;
}

.was-validated .form-control:invalid {
    border-color: #dc3545;
}

.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    overflow-x: hidden;
    z-index: 1000;
}
</style>

