<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tachometer-alt"></i>
        หน้าหลัก - ระบบจัดการครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="location.reload()">
                <i class="fas fa-sync-alt"></i> รีเฟรช
            </button>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            ครุภัณฑ์ทั้งหมด
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">
                            <?php echo number_format($asset_stats['total'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-boxes fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            ใช้งานได้
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">
                            <?php echo number_format($asset_stats['status']['ใช้งาน'] ?? 0); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            ชำรุด/ซ่อมแซม
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">
                            <?php echo number_format(($asset_stats['status']['ชำรุด'] ?? 0) + ($asset_stats['status']['ซ่อมแซม'] ?? 0)); ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stats-card bg-info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                            มูลค่ารวม
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-white">
                            <span class="currency"><?php echo number_format($asset_stats['total_value'] ?? 0, 2); ?></span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Assets -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-box"></i>
                    ครุภัณฑ์ล่าสุด
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($recent_assets)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ชื่อครุภัณฑ์</th>
                                    <th>ประเภท</th>
                                    <th>สถานะ</th>
                                    <th>วันที่เพิ่ม</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_assets as $asset): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo base_url('assets/view/' . $asset['asset_id']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($asset['asset_name']); ?>
                                            </a>
                                        </td>
                                        <td><?php echo htmlspecialchars($asset['asset_type']); ?></td>
                                        <td>
                                            <span class="asset-status"><?php echo $asset['status']; ?></span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($asset['created_at'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo base_url('assetmanager'); ?>" class="btn btn-primary btn-sm">
                            <i class="fas fa-eye"></i> ดูทั้งหมด
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>ยังไม่มีข้อมูลครุภัณฑ์</p>
                        <a href="<?php echo base_url('assetmanager/add'); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> เพิ่มครุภัณฑ์
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pending Repairs -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-tools"></i>
                    การซ่อมแซมที่รอดำเนินการ
                </h6>
            </div>
            <div class="card-body">
                <?php if (!empty($pending_repairs)): ?>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>ครุภัณฑ์</th>
                                    <th>ปัญหา</th>
                                    <th>วันที่แจ้ง</th>
                                    <th>ผู้แจ้ง</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pending_repairs as $repair): ?>
                                    <tr>
                                        <td>
                                            <a href="<?php echo base_url('assetmanager/view/' . $repair['asset_id']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($repair['asset_name']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <small><?php echo htmlspecialchars(substr($repair['description'], 0, 50)) . (strlen($repair['description']) > 50 ? '...' : ''); ?></small>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($repair['request_date'])); ?></td>
                                        <td><small><?php echo htmlspecialchars($repair['requested_by']); ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center">
                        <a href="<?php echo base_url('repairs'); ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-eye"></i> ดูทั้งหมด
                        </a>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted">
                        <i class="fas fa-check-circle fa-3x mb-3 text-success"></i>
                        <p>ไม่มีการซ่อมแซมที่รอดำเนินการ</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Asset Types Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-pie"></i>
                    ครุภัณฑ์ตามประเภท
                </h6>
            </div>
            <div class="card-body">
                <canvas id="assetTypesChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-history"></i>
                    กิจกรรมล่าสุด
                </h6>
            </div>
            <div class="card-body">
                <div class="timeline">
                    <?php if (!empty($recent_transfers)): ?>
                        <?php foreach (array_slice($recent_transfers, 0, 3) as $transfer): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">โอนย้ายครุภัณฑ์</h6>
                                    <p class="timeline-text">
                                        <?php echo htmlspecialchars($transfer['asset_name']); ?>
                                        <br>
                                        <small class="text-muted">
                                            จาก <?php echo htmlspecialchars($transfer['from_location']); ?> 
                                            ไป <?php echo htmlspecialchars($transfer['to_location']); ?>
                                        </small>
                                    </p>
                                    <span class="timeline-date"><?php echo date('d/m/Y', strtotime($transfer['transfer_date'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($recent_disposals)): ?>
                        <?php foreach (array_slice($recent_disposals, 0, 2) as $disposal): ?>
                            <div class="timeline-item">
                                <div class="timeline-marker bg-secondary"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">จำหน่ายครุภัณฑ์</h6>
                                    <p class="timeline-text">
                                        <?php echo htmlspecialchars($disposal['asset_name']); ?>
                                        <br>
                                        <small class="text-muted">
                                            วิธีการ: <?php echo htmlspecialchars($disposal['disposal_method']); ?>
                                        </small>
                                    </p>
                                    <span class="timeline-date"><?php echo date('d/m/Y', strtotime($disposal['disposal_date'])); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <?php if (empty($recent_transfers) && empty($recent_disposals)): ?>
                    <div class="text-center text-muted">
                        <i class="fas fa-clock fa-3x mb-3"></i>
                        <p>ยังไม่มีกิจกรรมล่าสุด</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-bolt"></i>
                    การดำเนินการด่วน
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo base_url('assetmanager/add'); ?>" class="btn btn-primary btn-block">
                            <i class="fas fa-plus"></i>
                            เพิ่มครุภัณฑ์ใหม่
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo base_url('transfers/add'); ?>" class="btn btn-info btn-block">
                            <i class="fas fa-exchange-alt"></i>
                            โอนย้ายครุภัณฑ์
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo base_url('repairs/add'); ?>" class="btn btn-warning btn-block">
                            <i class="fas fa-tools"></i>
                            แจ้งซ่อมแซม
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-3">
                        <a href="<?php echo base_url('reports'); ?>" class="btn btn-secondary btn-block">
                            <i class="fas fa-file-alt"></i>
                            ดูรายงาน
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Asset Types Chart
    <?php if (!empty($asset_stats['types'])): ?>
    var ctx = document.getElementById('assetTypesChart').getContext('2d');
    var assetTypesChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                <?php foreach ($asset_stats['types'] as $type): ?>
                    '<?php echo addslashes($type['asset_type']); ?>',
                <?php endforeach; ?>
            ],
            datasets: [{
                data: [
                    <?php foreach ($asset_stats['types'] as $type): ?>
                        <?php echo $type['count']; ?>,
                    <?php endforeach; ?>
                ],
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
    <?php endif; ?>
});
</script>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 20px;
}

.timeline-item:not(:last-child):before {
    content: '';
    position: absolute;
    left: -21px;
    top: 20px;
    height: calc(100% - 10px);
    width: 2px;
    background-color: #e3e6f0;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 10px;
    height: 10px;
    border-radius: 50%;
}

.timeline-content {
    background-color: #f8f9fc;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #5a5c69;
}

.timeline-title {
    font-size: 14px;
    font-weight: bold;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 13px;
    margin-bottom: 5px;
}

.timeline-date {
    font-size: 12px;
    color: #6c757d;
}
</style>

