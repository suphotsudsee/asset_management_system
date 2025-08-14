<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-eye"></i>
        รายละเอียดครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('assetmanager'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
            <a href="<?php echo base_url('assetmanager/edit/' . $asset['asset_id']); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> แก้ไข
            </a>
            <button type="button" class="btn btn-info" onclick="window.print()">
                <i class="fas fa-print"></i> พิมพ์
            </button>
            <div class="btn-group" role="group">
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo base_url('assetmanager/maintenance/' . $asset['asset_id']); ?>">
                        <i class="fas fa-wrench"></i> บันทึกการซ่อมบำรุง
                    </a>
                    <a class="dropdown-item" href="<?php echo base_url('assetmanager/transfer/' . $asset['asset_id']); ?>">
                        <i class="fas fa-exchange-alt"></i> โอนย้าย
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-danger" href="#" onclick="confirmDelete(<?php echo $asset['asset_id']; ?>)">
                        <i class="fas fa-trash"></i> ลบครุภัณฑ์
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <!-- ข้อมูลหลักครุภัณฑ์ -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="m-0">
                    <i class="fas fa-info-circle text-primary"></i>
                    ข้อมูลพื้นฐาน
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">รหัสครุภัณฑ์:</td>
                                <td><strong class="text-primary"><?php echo $asset['asset_id']; ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">ชื่อครุภัณฑ์:</td>
                                <td><strong><?php echo $asset['asset_name']; ?></strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">ประเภท:</td>
                                <td>
                                    <span class="badge badge-info"><?php echo $asset['asset_type']; ?></span>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">หมายเลขซีเรียล:</td>
                                <td><?php echo $asset['serial_number'] ?: '<span class="text-muted">-</span>'; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">สถานะ:</td>
                                <td>
                                    <?php
                                    $status_color = '';
                                    switch($asset['status']) {
                                        case 'ใช้งาน': $status_color = 'success'; break;
                                        case 'ชำรุด': $status_color = 'danger'; break;
                                        case 'ซ่อมแซม': $status_color = 'warning'; break;
                                        case 'เลิกใช้งาน': $status_color = 'secondary'; break;
                                        default: $status_color = 'info';
                                    }
                                    ?>
                                    <span class="badge badge-<?php echo $status_color; ?>"><?php echo $asset['status']; ?></span>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless">
                            <tr>
                                <td class="text-muted" width="40%">วันที่จัดซื้อ:</td>
                                <td><?php echo date('d/m/Y', strtotime($asset['purchase_date'])); ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">ราคาจัดซื้อ:</td>
                                <td><strong class="text-success"><?php echo number_format($asset['purchase_price'], 2); ?> บาท</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">วิธีการได้มา:</td>
                                <td><?php echo $asset['acquisition_method']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">สถานที่ตั้ง:</td>
                                <td><i class="fas fa-map-marker-alt text-danger"></i> <?php echo $asset['current_location']; ?></td>
                            </tr>
                            <tr>
                                <td class="text-muted">ผู้รับผิดชอบ:</td>
                                <td><i class="fas fa-user text-info"></i> <?php echo $asset['responsible_person']; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <?php if ($asset['warranty_info']): ?>
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-muted">ข้อมูลการรับประกัน:</h6>
                        <div class="card bg-light">
                            <div class="card-body">
                                <?php echo nl2br(htmlspecialchars($asset['warranty_info'])); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ข้อมูลการเสื่อมราคา -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="m-0">
                    <i class="fas fa-chart-line text-warning"></i>
                    ข้อมูลการเสื่อมราคา
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body text-center">
                                <h6 class="card-title text-primary">อัตราค่าเสื่อมราคา</h6>
                                <h3 class="text-primary"><?php echo $asset['depreciation_rate']; ?>%</h3>
                                <small class="text-muted">ต่อปี</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card border-warning">
                            <div class="card-body text-center">
                                <h6 class="card-title text-warning">ค่าเสื่อมราคาต่อปี</h6>
                                <?php 
                                $annual_depreciation = $asset['purchase_price'] * ($asset['depreciation_rate'] / 100);
                                ?>
                                <h3 class="text-warning"><?php echo number_format($annual_depreciation, 2); ?></h3>
                                <small class="text-muted">บาท</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-body text-center">
                                <h6 class="card-title text-info">อายุการใช้งาน</h6>
                                <?php
                                $purchase_date = new DateTime($asset['purchase_date']);
                                $current_date = new DateTime();
                                $age = $purchase_date->diff($current_date);
                                ?>
                                <h4 class="text-info"><?php echo $age->y; ?> ปี <?php echo $age->m; ?> เดือน</h4>
                                <small class="text-muted"><?php echo $age->days; ?> วัน</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-success">
                            <div class="card-body text-center">
                                <h6 class="card-title text-success">มูลค่าปัจจุบัน</h6>
                                <?php
                                $age_in_years = $age->y + ($age->m / 12) + ($age->days / 365);
                                $total_depreciation = $annual_depreciation * $age_in_years;
                                $current_value = max(0, $asset['purchase_price'] - $total_depreciation);
                                ?>
                                <h4 class="text-success"><?php echo number_format($current_value, 2); ?></h4>
                                <small class="text-muted">บาท</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-danger">
                            <div class="card-body text-center">
                                <h6 class="card-title text-danger">ค่าเสื่อมราคาสะสม</h6>
                                <h4 class="text-danger"><?php echo number_format($total_depreciation, 2); ?></h4>
                                <small class="text-muted">บาท</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- กราฟแสดงการเสื่อมราคา -->
                <div class="mt-4">
                    <h6>กราฟการเสื่อมราคา</h6>
                    <canvas id="depreciationChart" height="100"></canvas>
                </div>
            </div>
        </div>

        <!-- ประวัติการซ่อมบำรุง -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <h5 class="m-0">
                    <i class="fas fa-wrench text-info"></i>
                    ประวัติการซ่อมบำรุง
                </h5>
                <a href="<?php echo base_url('assets/maintenance/' . $asset['asset_id']); ?>" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> เพิ่มรายการ
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($maintenance_history) && !empty($maintenance_history)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="thead-light">
                                <tr>
                                    <th>วันที่</th>
                                    <th>ประเภท</th>
                                    <th>รายละเอียด</th>
                                    <th>ค่าใช้จ่าย</th>
                                    <th>สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($maintenance_history as $maintenance): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y', strtotime($maintenance['maintenance_date'])); ?></td>
                                    <td>
                                        <span class="badge badge-secondary"><?php echo $maintenance['maintenance_type']; ?></span>
                                    </td>
                                    <td><?php echo $maintenance['description']; ?></td>
                                    <td class="text-right"><?php echo number_format($maintenance['cost'], 2); ?> บาท</td>
                                    <td>
                                        <span class="badge badge-<?php echo $maintenance['status'] == 'เสร็จสิ้น' ? 'success' : 'warning'; ?>">
                                            <?php echo $maintenance['status']; ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-tools fa-3x mb-3"></i>
                        <p>ยังไม่มีประวัติการซ่อมบำรุง</p>
                        <a href="<?php echo base_url('assets/maintenance/' . $asset['asset_id']); ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> เพิ่มรายการแรก
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ประวัติการโอนย้าย -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
                <h5 class="m-0">
                    <i class="fas fa-exchange-alt text-warning"></i>
                    ประวัติการโอนย้าย
                </h5>
                <a href="<?php echo base_url('assets/transfer/' . $asset['asset_id']); ?>" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-plus"></i> โอนย้าย
                </a>
            </div>
            <div class="card-body">
                <?php if (isset($transfer_history) && !empty($transfer_history)): ?>
                    <div class="timeline">
                        <?php foreach ($transfer_history as $transfer): ?>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title"><?php echo date('d/m/Y', strtotime($transfer['transfer_date'])); ?></h6>
                                <p class="timeline-text">
                                    โอนจาก: <strong><?php echo $transfer['from_location']; ?></strong><br>
                                    โอนไป: <strong><?php echo $transfer['to_location']; ?></strong><br>
                                    ผู้โอน: <?php echo $transfer['transferred_by']; ?><br>
                                    เหตุผล: <?php echo $transfer['reason']; ?>
                                </p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-exchange-alt fa-3x mb-3"></i>
                        <p>ยังไม่มีประวัติการโอนย้าย</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- สถิติสรุป -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-pie"></i>
                    สถิติสรุป
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <div class="h4 font-weight-bold text-primary">
                            <?php echo number_format(($current_value / $asset['purchase_price']) * 100, 1); ?>%
                        </div>
                        <div class="text-xs text-muted">มูลค่าคงเหลือ</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 font-weight-bold text-success">
                            <?php echo $age->y; ?> ปี
                        </div>
                        <div class="text-xs text-muted">อายุการใช้งาน</div>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6 border-right">
                        <div class="h4 font-weight-bold text-warning">
                            <?php echo isset($maintenance_history) ? count($maintenance_history) : 0; ?>
                        </div>
                        <div class="text-xs text-muted">ครั้งซ่อมบำรุง</div>
                    </div>
                    <div class="col-6">
                        <div class="h4 font-weight-bold text-info">
                            <?php echo isset($transfer_history) ? count($transfer_history) : 0; ?>
                        </div>
                        <div class="text-xs text-muted">ครั้งโอนย้าย</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-qrcode"></i>
                    QR Code
                </h6>
            </div>
            <div class="card-body text-center">
                <div id="qrcode" class="mb-3"></div>
                <small class="text-muted">สแกนเพื่อดูรายละเอียดครุภัณฑ์</small>
                <br>
                <button class="btn btn-sm btn-outline-primary mt-2" onclick="downloadQR()">
                    <i class="fas fa-download"></i> ดาวน์โหลด QR
                </button>
            </div>
        </div>

        <!-- ข้อมูลระบบ -->
        <div class="card mb-4">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-database"></i>
                    ข้อมูลระบบ
                </h6>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">สร้างเมื่อ:</td>
                        <td class="text-right"><?php echo date('d/m/Y H:i', strtotime($asset['created_at'])); ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">แก้ไขล่าสุด:</td>
                        <td class="text-right">
                            <?php echo $asset['updated_at'] ? date('d/m/Y H:i', strtotime($asset['updated_at'])) : 'ไม่เคยแก้ไข'; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">สร้างโดย:</td>
                        <td class="text-right"><?php echo $asset['created_by'] ?? 'ระบบ'; ?></td>
                    </tr>
                    <?php if (isset($asset['updated_by']) && $asset['updated_by']): ?>
                    <tr>
                        <td class="text-muted">แก้ไขโดย:</td>
                        <td class="text-right"><?php echo $asset['updated_by']; ?></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>

        <!-- การดำเนินการ -->
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-cogs"></i>
                    การดำเนินการ
                </h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo base_url('assets/edit/' . $asset['asset_id']); ?>" class="btn btn-warning btn-block">
                        <i class="fas fa-edit"></i> แก้ไขข้อมูล
                    </a>
                    <a href="<?php echo base_url('assets/maintenance/' . $asset['asset_id']); ?>" class="btn btn-info btn-block">
                        <i class="fas fa-wrench"></i> บันทึกการซ่อมบำรุง
                    </a>
                    <a href="<?php echo base_url('assets/transfer/' . $asset['asset_id']); ?>" class="btn btn-secondary btn-block">
                        <i class="fas fa-exchange-alt"></i> โอนย้าย
                    </a>
                    <button class="btn btn-primary btn-block" onclick="window.print()">
                        <i class="fas fa-print"></i> พิมพ์รายงาน
                    </button>
                    <hr>
                    <button class="btn btn-danger btn-block" onclick="confirmDelete(<?php echo $asset['asset_id']; ?>)">
                        <i class="fas fa-trash"></i> ลบครุภัณฑ์
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับยืนยันการลบ -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle"></i>
                    ยืนยันการลบ
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>คุณแน่ใจหรือไม่ที่จะลบครุภัณฑ์นี้?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>คำเตือน:</strong> การลบจะไม่สามารถย้อนกลับได้ และจะลบข้อมูลประวัติทั้งหมดด้วย
                </div>
                <p><strong>ครุภัณฑ์:</strong> <?php echo $asset['asset_name']; ?></p>
                <p><strong>รหัส:</strong> <?php echo $asset['asset_code']; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash"></i> ลบครุภัณฑ์
                </button>
            </div>
        </div>
    </div>
</div>

<!-- รวม JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode/1.5.3/qrcode.min.js"></script>

<script>
$(document).ready(function() {
    // สร้าง QR Code
    createQRCode();
    
    // สร้างกราฟการเสื่อมราคา
    createDepreciationChart();
});

// สร้าง QR Code
function createQRCode() {
    var qr = new QRCode(document.getElementById("qrcode"), {
        text: "<?php echo base_url('assets/view/' . $asset['asset_id']); ?>",
        width: 150,
        height: 150,
        colorDark: "#000000",
        colorLight: "#ffffff",
        correctLevel: QRCode.CorrectLevel.H
    });
}

// ดาวน์โหลด QR Code
function downloadQR() {
    var canvas = document.querySelector('#qrcode canvas');
    if (canvas) {
        var link = document.createElement('a');
        link.download = 'QR_<?php echo $asset['asset_code']; ?>.png';
        link.href = canvas.toDataURL();
        link.click();
    }
}

// สร้างกราฟการเสื่อมราคา
function createDepreciationChart() {
    var ctx = document.getElementById('depreciationChart').getContext('2d');
    
    // คำนวณข้อมูลสำหรับกราฟ
    var purchasePrice = <?php echo $asset['purchase_price']; ?>;
    var depreciationRate = <?php echo $asset['depreciation_rate']; ?>;
    var annualDepreciation = purchasePrice * (depreciationRate / 100);
    
    // สร้างข้อมูล 10 ปี
    var labels = [];
    var values = [];
    
    for (var i = 0; i <= 10; i++) {
        labels.push('ปีที่ ' + i);
        var value = Math.max(0, purchasePrice - (annualDepreciation * i));
        values.push(value);
    }
    
    // ตำแหน่งปัจจุบัน
    var currentAge = <?php echo $age_in_years; ?>;
    var currentValue = <?php echo $current_value; ?>;
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'มูลค่าครุภัณฑ์ (บาท)',
                data: values,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }, {
                label: 'ปัจจุบัน',
                data: labels.map((_, index) => index <= currentAge ? null : null).concat([currentValue]),
                pointBackgroundColor: 'red',
                pointBorderColor: 'red',
                pointRadius: 8,
                showLine: false,
                type: 'scatter'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'กราฟการเสื่อมราคา'
                },
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString() + ' บาท';
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// ฟังก์ชันยืนยันการลบ
function confirmDelete(assetId) {
    $('#deleteModal').modal('show');
    
    $('#confirmDeleteBtn').off('click').on('click', function() {
        // ส่งคำขอลบ
        $.ajax({
            url: '<?php echo base_url("assetmanager/delete/"); ?>' + assetId,
            type: 'POST',
            dataType: 'json',
            success: function(response) {
                $('#deleteModal').modal('hide');
                if (response.success) {
                    showAlert('success', 'ลบครุภัณฑ์เรียบร้อยแล้ว');
                    setTimeout(function() {
                        window.location.href = '<?php echo base_url("assetmanager"); ?>';
                    }, 1500);
                } else {
                    showAlert('danger', response.message || 'เกิดข้อผิดพลาดในการลบ');
                }
            },
            error: function() {
                $('#deleteModal').modal('hide');
                showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        });
    });
}

function downloadQR() {
    // 1. ลองหา canvas ก่อน
    var canvas = document.querySelector('#qrcode canvas');
    if (canvas) {
        var link = document.createElement('a');
        link.download = 'QR_<?php echo $asset['asset_id']; ?>.png';
        link.href = canvas.toDataURL();
        link.click();
        return;
    }

    // 2. ถ้าไม่มี canvas ให้ลองหา <img>
    var img = document.querySelector('#qrcode img');
    if (img) {
        var link = document.createElement('a');
        link.download = 'QR_<?php echo $asset['asset_id']; ?>.png';
        link.href = img.src;
        link.click();
        return;
    }

    alert("ไม่พบ QR Code ในหน้านี้");
}
