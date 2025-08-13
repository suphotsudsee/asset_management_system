<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-redo"></i>
        ต่ออายุค้ำประกันสัญญา #<?php echo $guarantee['guarantee_id']; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('guarantees/view/' . $guarantee['guarantee_id']); ?>" class="btn btn-outline-secondary">
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

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Current Guarantee Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle"></i>
                    ข้อมูลค้ำประกันปัจจุบัน
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">ครุภัณฑ์:</td>
                                <td><?php echo htmlspecialchars($guarantee['asset_code'] . ' - ' . $guarantee['asset_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ประเภทค้ำประกัน:</td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($guarantee['guarantee_type']); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ผู้จำหน่าย:</td>
                                <td><?php echo htmlspecialchars($guarantee['vendor_name']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">วันที่เริ่มต้น:</td>
                                <td><?php echo date('d/m/Y', strtotime($guarantee['start_date'])); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันที่สิ้นสุดปัจจุบัน:</td>
                                <td class="text-danger font-weight-bold">
                                    <?php echo date('d/m/Y', strtotime($guarantee['end_date'])); ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">สถานะ:</td>
                                <td>
                                    <span class="guarantee-status"><?php echo $guarantee['status']; ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Renewal Form -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-redo"></i>
                    ข้อมูลการต่ออายุ
                </h6>
            </div>
            <div class="card-body">
                <form action="<?php echo base_url('guarantees/process_renewal/' . $guarantee['guarantee_id']); ?>" method="post" id="renewalForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="current_end_date">วันที่สิ้นสุดปัจจุบัน</label>
                            <input type="date" class="form-control" id="current_end_date" 
                                   value="<?php echo $guarantee['end_date']; ?>" readonly>
                            <small class="form-text text-muted">ไม่สามารถแก้ไขได้</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="new_end_date" class="required">วันที่สิ้นสุดใหม่</label>
                            <input type="date" class="form-control" id="new_end_date" name="new_end_date" 
                                   value="<?php echo set_value('new_end_date'); ?>" required>
                            <small class="form-text text-muted">
                                ระยะเวลาเพิ่มเติม: <span id="additionalDays">0</span> วัน
                            </small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="renewal_cost">ค่าใช้จ่ายในการต่ออายุ (บาท)</label>
                            <input type="number" class="form-control" id="renewal_cost" name="renewal_cost" 
                                   value="<?php echo set_value('renewal_cost', '0'); ?>" min="0" step="0.01"
                                   placeholder="0.00">
                            <small class="form-text text-muted">ระบุ 0 หากไม่มีค่าใช้จ่าย</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="renewal_date">วันที่ดำเนินการต่ออายุ</label>
                            <input type="date" class="form-control" id="renewal_date" name="renewal_date" 
                                   value="<?php echo date('Y-m-d'); ?>" readonly>
                            <small class="form-text text-muted">วันที่ปัจจุบัน</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="renewal_notes">หมายเหตุการต่ออายุ</label>
                        <textarea class="form-control" id="renewal_notes" name="renewal_notes" rows="4"
                                  placeholder="ระบุรายละเอียดเพิ่มเติมเกี่ยวกับการต่ออายุ เช่น เหตุผล, เงื่อนไขใหม่, การเปลี่ยนแปลง"><?php echo set_value('renewal_notes'); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> ข้อมูลการต่ออายุ:</h6>
                                <ul class="mb-0">
                                    <li>ระบบจะบันทึกประวัติการต่ออายุไว้เป็นหลักฐาน</li>
                                    <li>วันที่สิ้นสุดใหม่ต้องมากกว่าวันที่สิ้นสุดปัจจุบัน</li>
                                    <li>สถานะค้ำประกันจะถูกเปลี่ยนเป็น "ใช้งาน" อัตโนมัติ</li>
                                    <li>สามารถต่ออายุได้หลายครั้งตามต้องการ</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="<?php echo base_url('guarantees/view/' . $guarantee['guarantee_id']); ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-redo"></i> ต่ออายุค้ำประกัน
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Quick Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-calculator"></i>
                    สรุปข้อมูล
                </h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <div class="mb-3">
                        <h5 class="text-primary">วันคงเหลือปัจจุบัน</h5>
                        <h2 class="text-danger" id="currentDaysRemaining">
                            <?php 
                            $days_remaining = ceil((strtotime($guarantee['end_date']) - time()) / (60 * 60 * 24));
                            echo max(0, $days_remaining);
                            ?>
                        </h2>
                        <small class="text-muted">วัน</small>
                    </div>
                    
                    <div class="mb-3">
                        <h5 class="text-success">วันคงเหลือหลังต่ออายุ</h5>
                        <h2 class="text-success" id="newDaysRemaining">0</h2>
                        <small class="text-muted">วัน</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guidelines -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-info">
                    <i class="fas fa-lightbulb"></i>
                    คำแนะนำ
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h6><i class="fas fa-lightbulb"></i> เคล็ดลับ:</h6>
                    <ul class="mb-0 small">
                        <li>ควรต่ออายุก่อนค้ำประกันหมดอายุ</li>
                        <li>ตรวจสอบเงื่อนไขใหม่กับผู้จำหน่าย</li>
                        <li>บันทึกค่าใช้จ่ายให้ถูกต้อง</li>
                        <li>เก็บเอกสารการต่ออายุไว้เป็นหลักฐาน</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Warning -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    ข้อควรระวัง
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <ul class="mb-0 small">
                        <li>ตรวจสอบกับผู้จำหน่ายก่อนต่ออายุ</li>
                        <li>อาจมีเงื่อนไขใหม่หรือค่าใช้จ่ายเพิ่มเติม</li>
                        <li>การต่ออายุไม่สามารถยกเลิกได้</li>
                        <li>ควรมีเอกสารยืนยันจากผู้จำหน่าย</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Guarantee status badges
    $('.guarantee-status').each(function() {
        var status = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-danger badge-warning');
        
        switch(status) {
            case 'ใช้งาน':
                $(this).addClass('badge badge-success');
                break;
            case 'หมดอายุ':
                $(this).addClass('badge badge-danger');
                break;
            case 'ยกเลิก':
                $(this).addClass('badge badge-secondary');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
    
    // Date change event
    $('#new_end_date').on('change', function() {
        calculateAdditionalDays();
        calculateNewDaysRemaining();
    });
    
    // Form validation
    $('#renewalForm').on('submit', function(e) {
        var currentEndDate = new Date($('#current_end_date').val());
        var newEndDate = new Date($('#new_end_date').val());
        
        if (newEndDate <= currentEndDate) {
            e.preventDefault();
            alert('วันที่สิ้นสุดใหม่ต้องมากกว่าวันที่สิ้นสุดปัจจุบัน');
            return false;
        }
        
        if (!confirm('คุณแน่ใจหรือไม่ว่าต้องการต่ออายุค้ำประกันนี้?')) {
            e.preventDefault();
            return false;
        }
    });
    
    // Initialize calculations
    calculateAdditionalDays();
    calculateNewDaysRemaining();
});

function calculateAdditionalDays() {
    var currentEndDate = $('#current_end_date').val();
    var newEndDate = $('#new_end_date').val();
    
    if (currentEndDate && newEndDate) {
        var current = new Date(currentEndDate);
        var newDate = new Date(newEndDate);
        var timeDiff = newDate.getTime() - current.getTime();
        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff > 0) {
            $('#additionalDays').text(daysDiff.toLocaleString());
        } else {
            $('#additionalDays').text('0');
        }
    } else {
        $('#additionalDays').text('0');
    }
}

function calculateNewDaysRemaining() {
    var newEndDate = $('#new_end_date').val();
    
    if (newEndDate) {
        var today = new Date();
        var endDate = new Date(newEndDate);
        var timeDiff = endDate.getTime() - today.getTime();
        var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
        
        if (daysDiff > 0) {
            $('#newDaysRemaining').text(daysDiff.toLocaleString());
        } else {
            $('#newDaysRemaining').text('0');
        }
    } else {
        $('#newDaysRemaining').text('0');
    }
}
</script>

<style>
.required::after {
    content: " *";
    color: red;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0.75rem;
}

.table-borderless td:first-child {
    width: 40%;
}

.alert ul {
    padding-left: 1.2rem;
}

.form-control:focus {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
}

.btn-success {
    background-color: #1cc88a;
    border-color: #1cc88a;
}

.btn-success:hover {
    background-color: #17a673;
    border-color: #169b6b;
}
</style>

