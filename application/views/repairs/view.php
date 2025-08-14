<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye"></i>
        รายละเอียดการซ่อมแซม
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('repairs'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
            <a href="<?php echo base_url('repairs/edit/' . $repair['repair_id']); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> แก้ไข
            </a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">ข้อมูลการซ่อมแซม #<?php echo $repair['repair_id']; ?></h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">ครุภัณฑ์:</th>
                        <td>
                            <a href="<?php echo base_url('assets/view/' . $repair['asset_id']); ?>" class="font-weight-bold">
                                <?php echo htmlspecialchars($repair['asset_name']); ?>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <th>หมายเลขซีเรียล:</th>
                        <td><?php echo htmlspecialchars($repair['serial_number']); ?></td>
                    </tr>
                    <tr>
                        <th>ประเภท:</th>
                        <td><?php echo htmlspecialchars($repair['asset_type']); ?></td>
                    </tr>
                    <tr>
                        <th>สถานที่ตั้ง:</th>
                        <td><?php echo htmlspecialchars($repair['current_location']); ?></td>
                    </tr>
                    <tr>
                        <th>ผู้แจ้ง:</th>
                        <td><?php echo htmlspecialchars($repair['requested_by']); ?></td>
                    </tr>
                    <tr>
                        <th>ข้อมูลติดต่อ:</th>
                        <td><?php echo htmlspecialchars(isset($repair['contact_info']) ? $repair['contact_info'] : ''); ?></td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="35%">ประเภทการซ่อม:</th>
                        <td><?php echo htmlspecialchars(isset($repair['repair_type']) ? $repair['repair_type'] : ''); ?></td>
                    </tr>
                    <tr>
                        <th>ความสำคัญ:</th>
                        <td>
                            <span class="priority-badge">
                                <?php echo isset($repair['priority'])
                                    ? htmlspecialchars($repair['priority'])
                                    : '<span style="color: gray;">-</span>'; ?>
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>สถานะ:</th>
                        <td><span class="repair-status"><?php echo htmlspecialchars($repair['status']); ?></span></td>
                    </tr>
                    <tr>
                        <th>วันที่แจ้ง:</th>
                        <td><?php echo date('d/m/Y', strtotime($repair['request_date'])); ?></td>
                    </tr>
                    <?php if (!empty($repair['approved_date'])): ?>
                    <tr>
                        <th>วันที่อนุมัติ:</th>
                        <td><?php echo date('d/m/Y', strtotime($repair['approved_date'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($repair['completion_date'])): ?>
                    <tr>
                        <th>วันที่เสร็จสิ้น:</th>
                        <td><?php echo date('d/m/Y', strtotime($repair['completion_date'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <h6>รายละเอียดปัญหา</h6>
                <div class="card bg-light">
                    <div class="card-body">
                        <?php echo isset($repair['problem_description']) ? nl2br(htmlspecialchars($repair['problem_description'])) : ''; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (!empty($repair['repair_details']) || !empty($repair['vendor_info']) || !empty($repair['notes']) || !empty($repair['estimated_cost']) || !empty($repair['actual_cost']) || !empty($repair['expected_completion']) || !empty($repair['actual_completion'])): ?>
        <div class="row mt-3">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <?php if (!empty($repair['estimated_cost'])): ?>
                    <tr>
                        <th width="45%">ค่าใช้จ่ายประมาณการ:</th>
                        <td><?php echo number_format($repair['estimated_cost'], 2); ?> บาท</td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($repair['actual_cost'])): ?>
                    <tr>
                        <th>ค่าใช้จ่ายจริง:</th>
                        <td><strong><?php echo number_format($repair['actual_cost'], 2); ?></strong> บาท</td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($repair['vendor_info'])): ?>
                    <tr>
                        <th>ผู้ให้บริการ:</th>
                        <td><?php echo nl2br(htmlspecialchars($repair['vendor_info'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <?php if (!empty($repair['expected_completion'])): ?>
                    <tr>
                        <th width="45%">วันที่คาดว่าจะเสร็จ:</th>
                        <td><?php echo date('d/m/Y', strtotime($repair['expected_completion'])); ?></td>
                    </tr>
                    <?php endif; ?>
                    <?php if (!empty($repair['actual_completion'])): ?>
                    <tr>
                        <th>วันที่เสร็จจริง:</th>
                        <td><?php echo date('d/m/Y', strtotime($repair['actual_completion'])); ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($repair['repair_details']) || !empty($repair['notes'])): ?>
        <div class="row mt-3">
            <?php if (!empty($repair['repair_details'])): ?>
            <div class="col-md-6 mb-3">
                <h6>รายละเอียดการซ่อม</h6>
                <div class="card bg-light">
                    <div class="card-body">
                        <?php echo nl2br(htmlspecialchars($repair['repair_details'])); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            <?php if (!empty($repair['notes'])): ?>
            <div class="col-md-6 mb-3">
                <h6>หมายเหตุ</h6>
                <div class="card bg-light">
                    <div class="card-body">
                        <?php echo nl2br(htmlspecialchars($repair['notes'])); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
