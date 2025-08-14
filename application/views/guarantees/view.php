<?php 
$days_remaining = ceil((strtotime($guarantee['end_date']) - time()) / (60 * 60 * 24));
$is_expired = $days_remaining < 0;
$is_expiring_soon = $days_remaining <= 30 && $days_remaining >= 0;
?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-shield-alt"></i>
        รายละเอียดค้ำประกันสัญญา #<?php echo $guarantee['guarantee_id']; ?>
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('guarantees/edit/' . $guarantee['guarantee_id']); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> แก้ไข
            </a>
            <?php if ($guarantee['status'] == 'ใช้งาน' && !$is_expired): ?>
                <a href="<?php echo base_url('guarantees/renew/' . $guarantee['guarantee_id']); ?>" class="btn btn-info">
                    <i class="fas fa-redo"></i> ต่ออายุ
                </a>
                <a href="<?php echo base_url('guarantees/claim/' . $guarantee['guarantee_id']); ?>" class="btn btn-success">
                    <i class="fas fa-hand-holding-usd"></i> เคลมประกัน
                </a>
            <?php endif; ?>
            <button type="button" class="btn btn-danger" onclick="confirmDelete(<?php echo $guarantee['guarantee_id']; ?>)">
                <i class="fas fa-trash"></i> ลบ
            </button>
        </div>
        <a href="<?php echo base_url('guarantees'); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> กลับ
        </a>
    </div>
</div>

<!-- Alert Messages -->
<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i>
        <?php echo $this->session->flashdata('success'); ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i>
        <?php echo $this->session->flashdata('error'); ?>
        <button type="button" class="close" data-dismiss="alert">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<!-- Status Alert -->
<?php if ($is_expired): ?>
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i>
        <strong>ค้ำประกันหมดอายุแล้ว</strong> - หมดอายุเมื่อ <?php echo abs($days_remaining); ?> วันที่แล้ว
    </div>
<?php elseif ($is_expiring_soon): ?>
    <div class="alert alert-warning">
        <i class="fas fa-clock"></i>
        <strong>ค้ำประกันใกล้หมดอายุ</strong> - เหลืออีก <?php echo $days_remaining; ?> วัน
    </div>
<?php endif; ?>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Guarantee Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-info-circle"></i>
                    ข้อมูลค้ำประกันสัญญา
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">รหัสค้ำประกัน:</td>
                                <td>#<?php echo $guarantee['guarantee_id']; ?></td>
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
                                <td class="font-weight-bold">เลขที่สัญญา:</td>
                                <td>
                                    <?php if (!empty($guarantee['contract_number'])): ?>
                                        <span class="badge badge-secondary">
                                            <?php echo htmlspecialchars($guarantee['contract_number']); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันที่เริ่มต้น:</td>
                                <td><?php echo date('d/m/Y', strtotime($guarantee['start_date'])); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันที่สิ้นสุด:</td>
                                <td><?php echo date('d/m/Y', strtotime($guarantee['end_date'])); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">สถานะ:</td>
                                <td>
                                    <span class="guarantee-status"><?php echo $guarantee['status']; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันคงเหลือ:</td>
                                <td>
                                    <?php if ($is_expired): ?>
                                        <span class="badge badge-danger">
                                            <i class="fas fa-times"></i>
                                            หมดอายุ <?php echo abs($days_remaining); ?> วัน
                                        </span>
                                    <?php elseif ($is_expiring_soon): ?>
                                        <span class="badge badge-warning">
                                            <i class="fas fa-clock"></i>
                                            <?php echo $days_remaining; ?> วัน
                                        </span>
                                    <?php else: ?>
                                        <span class="badge badge-success">
                                            <i class="fas fa-check"></i>
                                            <?php echo $days_remaining; ?> วัน
                                        </span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ระยะเวลาค้ำประกัน:</td>
                                <td>
                                    <?php 
                                    $total_days = ceil((strtotime($guarantee['end_date']) - strtotime($guarantee['start_date'])) / (60 * 60 * 24));
                                    echo number_format($total_days) . ' วัน';
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันที่บันทึก:</td>
                                <td>
                                    <?php if (!empty($guarantee['created_date'])): ?>
                                        <?php echo date('d/m/Y H:i', strtotime($guarantee['created_date'])); ?>
                                    <?php else: ?>
                                        N/A
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if (!empty($guarantee['updated_date'])): ?>
                            <tr>
                                <td class="font-weight-bold">วันที่แก้ไขล่าสุด:</td>
                                <td><?php echo date('d/m/Y H:i', strtotime($guarantee['updated_date'])); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Asset Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-box"></i>
                    ข้อมูลครุภัณฑ์
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">รหัสครุภัณฑ์:</td>
                                <td>
                                    <?php if (!empty($guarantee['asset_id'])): ?>
                                        <a href="<?php echo base_url('assets/view/' . $guarantee['asset_id']); ?>"
                                           class="text-decoration-none font-weight-bold">
                                            <?php echo htmlspecialchars($guarantee['asset_code']); ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ชื่อครุภัณฑ์:</td>
                                <td><?php echo htmlspecialchars($guarantee['asset_name']); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ประเภท:</td>
                                <td>
                                    <span class="badge badge-secondary">
                                        <?php echo htmlspecialchars($guarantee['category']); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">สถานที่:</td>
                                <td><?php echo htmlspecialchars($guarantee['current_location']); ?></td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">วันที่จัดซื้อ:</td>
                                <td>
                                    <?php if (!empty($guarantee['purchase_date'])): ?>
                                        <?php echo date('d/m/Y', strtotime($guarantee['purchase_date'])); ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-weight-bold">ราคาทุน:</td>
                                <td>
                                    <?php if (isset($guarantee['purchase_price'])): ?>
                                        <?php echo number_format($guarantee['purchase_price'], 2); ?> บาท
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vendor Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-store"></i>
                    ข้อมูลผู้จำหน่าย/ผู้ให้บริการ
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">ชื่อผู้จำหน่าย:</td>
                                <td><?php echo htmlspecialchars($guarantee['vendor_name']); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="font-weight-bold">ข้อมูลติดต่อ:</td>
                                <td><?php echo htmlspecialchars(isset($guarantee['vendor_contact']) ? $guarantee['vendor_contact'] : ''); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-file-alt"></i>
                    รายละเอียดค้ำประกัน
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($guarantee['coverage_details'])): ?>
                    <div class="mb-3">
                        <h6 class="font-weight-bold">รายละเอียดความคุ้มครอง:</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($guarantee['coverage_details'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($guarantee['terms_conditions'])): ?>
                    <div class="mb-3">
                        <h6 class="font-weight-bold">เงื่อนไขและข้อกำหนด:</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($guarantee['terms_conditions'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($guarantee['claim_procedure'])): ?>
                    <div class="mb-3">
                        <h6 class="font-weight-bold">ขั้นตอนการเคลม:</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($guarantee['claim_procedure'])); ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($guarantee['notes'])): ?>
                    <div class="mb-3">
                        <h6 class="font-weight-bold">หมายเหตุ:</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($guarantee['notes'])); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-cog"></i>
                    การดำเนินการ
                </h6>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="<?php echo base_url('guarantees/edit/' . $guarantee['guarantee_id']); ?>" 
                       class="list-group-item list-group-item-action">
                        <i class="fas fa-edit text-warning"></i>
                        แก้ไขข้อมูลค้ำประกัน
                    </a>
                    <?php if ($guarantee['status'] == 'ใช้งาน' && !$is_expired): ?>
                        <a href="<?php echo base_url('guarantees/renew/' . $guarantee['guarantee_id']); ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-redo text-info"></i>
                            ต่ออายุค้ำประกัน
                        </a>
                        <a href="<?php echo base_url('guarantees/claim/' . $guarantee['guarantee_id']); ?>" 
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-hand-holding-usd text-success"></i>
                            เคลมประกัน
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($guarantee['asset_id'])): ?>
                        <a href="<?php echo base_url('assets/view/' . $guarantee['asset_id']); ?>"
                           class="list-group-item list-group-item-action">
                            <i class="fas fa-box text-primary"></i>
                            ดูข้อมูลครุภัณฑ์
                        </a>
                    <?php endif; ?>
                    <button type="button" class="list-group-item list-group-item-action text-danger" 
                            onclick="confirmDelete(<?php echo $guarantee['guarantee_id']; ?>)">
                        <i class="fas fa-trash"></i>
                        ลบค้ำประกัน
                    </button>
                </div>
            </div>
        </div>

        <!-- Renewal History -->
        <?php if (!empty($renewal_history)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-history"></i>
                    ประวัติการต่ออายุ
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php foreach ($renewal_history as $renewal): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">ต่ออายุเมื่อ <?php echo date('d/m/Y', strtotime($renewal['renewal_date'])); ?></h6>
                                <p class="text-muted mb-1">
                                    จาก <?php echo date('d/m/Y', strtotime($renewal['old_end_date'])); ?> 
                                    เป็น <?php echo date('d/m/Y', strtotime($renewal['new_end_date'])); ?>
                                </p>
                                <?php if ($renewal['renewal_cost'] > 0): ?>
                                    <p class="text-muted mb-1">
                                        ค่าใช้จ่าย: <?php echo number_format($renewal['renewal_cost'], 2); ?> บาท
                                    </p>
                                <?php endif; ?>
                                <small class="text-muted">โดย <?php echo htmlspecialchars($renewal['renewed_by']); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Claims History -->
        <?php if (!empty($claims)): ?>
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-hand-holding-usd"></i>
                    ประวัติการเคลม
                </h6>
            </div>
            <div class="card-body">
                <?php foreach ($claims as $claim): ?>
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <h6 class="card-title mb-1">
                                เคลมเมื่อ <?php echo date('d/m/Y', strtotime($claim['claim_date'])); ?>
                            </h6>
                            <p class="card-text mb-1">
                                <strong>เหตุผล:</strong> <?php echo htmlspecialchars($claim['claim_reason']); ?>
                            </p>
                            <?php if ($claim['claim_amount'] > 0): ?>
                                <p class="card-text mb-1">
                                    <strong>จำนวนเงิน:</strong> <?php echo number_format($claim['claim_amount'], 2); ?> บาท
                                </p>
                            <?php endif; ?>
                            <p class="card-text mb-1">
                                <strong>สถานะ:</strong> 
                                <span class="claim-status"><?php echo $claim['claim_status']; ?></span>
                            </p>
                            <small class="text-muted">โดย <?php echo htmlspecialchars($claim['claimed_by']); ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    ยืนยันการลบ
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจหรือไม่ว่าต้องการลบข้อมูลค้ำประกันสัญญานี้?</p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    การดำเนินการนี้ไม่สามารถยกเลิกได้
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> ลบ
                </a>
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
    
    // Claim status badges
    $('.claim-status').each(function() {
        var status = $(this).text().trim();
        $(this).removeClass('badge-secondary badge-success badge-danger badge-warning badge-info');
        
        switch(status) {
            case 'รอดำเนินการ':
                $(this).addClass('badge badge-warning');
                break;
            case 'อนุมัติ':
                $(this).addClass('badge badge-info');
                break;
            case 'เสร็จสิ้น':
                $(this).addClass('badge badge-success');
                break;
            case 'ปฏิเสธ':
                $(this).addClass('badge badge-danger');
                break;
            default:
                $(this).addClass('badge badge-secondary');
        }
    });
});

function confirmDelete(guaranteeId) {
    $('#confirmDeleteBtn').attr('href', '<?php echo base_url('guarantees/delete/'); ?>' + guaranteeId);
    $('#deleteModal').modal('show');
}
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 17px;
    width: 2px;
    height: calc(100% + 5px);
    background-color: #e3e6f0;
}

.timeline-content {
    background-color: #f8f9fc;
    padding: 10px 15px;
    border-radius: 5px;
    border-left: 3px solid #4e73df;
}

.list-group-item-action:hover {
    background-color: #f8f9fc;
}

.table-borderless td {
    border: none;
    padding: 0.5rem 0.75rem;
}

.table-borderless td:first-child {
    width: 40%;
}
</style>

