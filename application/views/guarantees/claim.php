<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-hand-holding-usd"></i>
        เคลมประกันค้ำประกันสัญญา #<?php echo $guarantee["guarantee_id"]; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url("guarantees/view/" . $guarantee["guarantee_id"]); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> กลับ
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if ($this->session->flashdata("error")): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $this->session->flashdata("error"); ?>
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
                                <td><?php echo htmlspecialchars($guarantee["asset_code"] . " - " . $guarantee["asset_name"]); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ประเภทค้ำประกัน:</td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($guarantee["guarantee_type"]); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ผู้จำหน่าย:</td>
                                <td><?php echo htmlspecialchars($guarantee["vendor_name"]); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">วันที่เริ่มต้น:</td>
                                <td><?php echo date("d/m/Y", strtotime($guarantee["start_date"])); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันที่สิ้นสุด:</td>
                                <td><?php echo date("d/m/Y", strtotime($guarantee["end_date"])); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">สถานะ:</td>
                                <td>
                                    <span class="guarantee-status"><?php echo $guarantee["status"]; ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Claim Form -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-hand-holding-usd"></i>
                    ข้อมูลการเคลมประกัน
                </h6>
            </div>
            <div class="card-body">
                <form action="<?php echo base_url("guarantees/process_claim/" . $guarantee["guarantee_id"]); ?>" method="post" id="claimForm">
                    <div class="mb-3">
                        <label for="claim_date" class="required">วันที่เคลม</label>
                        <input type="date" class="form-control" id="claim_date" name="claim_date" 
                               value="<?php echo set_value("claim_date", date("Y-m-d")); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="claim_reason" class="required">เหตุผลในการเคลม</label>
                        <textarea class="form-control" id="claim_reason" name="claim_reason" rows="4"
                                  placeholder="ระบุเหตุผลที่ต้องการเคลมประกัน เช่น ชำรุด, เสียหาย, ไม่ทำงานตามปกติ" required><?php echo set_value("claim_reason"); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="claim_amount">จำนวนเงินที่เคลม (บาท)</label>
                        <input type="number" class="form-control" id="claim_amount" name="claim_amount" 
                               value="<?php echo set_value("claim_amount", "0"); ?>" min="0" step="0.01"
                               placeholder="0.00">
                        <small class="form-text text-muted">ระบุ 0 หากไม่มีการเคลมเป็นตัวเงิน</small>
                    </div>

                    <div class="mb-3">
                        <label for="claim_status" class="required">สถานะการเคลม</label>
                        <select class="form-control" id="claim_status" name="claim_status" required>
                            <option value="รอดำเนินการ" <?php echo set_select("claim_status", "รอดำเนินการ"); ?>>รอดำเนินการ</option>
                            <option value="อนุมัติ" <?php echo set_select("claim_status", "อนุมัติ"); ?>>อนุมัติ</option>
                            <option value="เสร็จสิ้น" <?php echo set_select("claim_status", "เสร็จสิ้น"); ?>>เสร็จสิ้น</option>
                            <option value="ปฏิเสธ" <?php echo set_select("claim_status", "ปฏิเสธ"); ?>>ปฏิเสธ</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="claim_notes">หมายเหตุการเคลม</label>
                        <textarea class="form-control" id="claim_notes" name="claim_notes" rows="3"
                                  placeholder="ข้อมูลเพิ่มเติมเกี่ยวกับการเคลม เช่น ผลการตรวจสอบ, การดำเนินการที่ผ่านมา"><?php echo set_value("claim_notes"); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <h6><i class="fas fa-info-circle"></i> ข้อมูลการเคลม:</h6>
                                <ul class="mb-0">
                                    <li>ระบบจะบันทึกประวัติการเคลมไว้เป็นหลักฐาน</li>
                                    <li>ตรวจสอบเงื่อนไขการเคลมก่อนดำเนินการ</li>
                                    <li>ระบุเหตุผลและสถานะการเคลมให้ชัดเจน</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="text-right">
                        <a href="<?php echo base_url("guarantees/view/" . $guarantee["guarantee_id"]); ?>" 
                           class="btn btn-secondary">
                            <i class="fas fa-times"></i> ยกเลิก
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-hand-holding-usd"></i> บันทึกการเคลม
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
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
                        <li>เตรียมเอกสารที่เกี่ยวข้องกับการเคลมให้พร้อม</li>
                        <li>ติดต่อผู้จำหน่าย/ผู้ให้บริการก่อนดำเนินการเคลม</li>
                        <li>บันทึกรายละเอียดการเคลมให้ครบถ้วน</li>
                        <li>ติดตามสถานะการเคลมอย่างสม่ำเสมอ</li>
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
                        <li>การเคลมอาจมีผลต่อสถานะค้ำประกัน</li>
                        <li>ตรวจสอบเงื่อนไขการเคลมในสัญญา</li>
                        <li>บางกรณีอาจมีค่าใช้จ่ายส่วนเกิน</li>
                        <li>การเคลมที่ไม่ถูกต้องอาจทำให้ค้ำประกันเป็นโมฆะ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Guarantee status badges (for current guarantee info)
    $(".guarantee-status").each(function() {
        var status = $(this).text().trim();
        $(this).removeClass("badge-secondary badge-success badge-danger badge-warning");
        
        switch(status) {
            case "ใช้งาน":
                $(this).addClass("badge badge-success");
                break;
            case "หมดอายุ":
                $(this).addClass("badge badge-danger");
                break;
            case "ยกเลิก":
                $(this).addClass("badge badge-secondary");
                break;
            default:
                $(this).addClass("badge badge-secondary");
        }
    });
    
    // Form validation
    $("#claimForm").on("submit", function(e) {
        if (!confirm("คุณแน่ใจหรือไม่ว่าต้องการบันทึกการเคลมนี้?")) {
            e.preventDefault();
            return false;
        }
    });
});
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

