<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-clipboard-list"></i> รายละเอียดการสำรวจครุภัณฑ์
                    </h4>
                    <div>
                        <a href="<?php echo site_url('surveys/edit/'.$survey['survey_id']); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> แก้ไข
                        </a>
                        <a href="<?php echo site_url('surveys'); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> กลับ
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> ข้อมูลการสำรวจ</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">รหัสการสำรวจ</th>
                                            <td><?php echo $survey['survey_id']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>ปีที่สำรวจ</th>
                                            <td><?php echo $survey['survey_year']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>วันที่สำรวจ</th>
                                            <td><?php echo date("d/m/Y", strtotime($survey['survey_date'])); ?></td>
                                        </tr>
                                        <tr>
                                            <th>ผู้สำรวจ</th>
                                            <td><?php echo htmlspecialchars($survey['surveyed_by']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>สภาพครุภัณฑ์</th>
                                            <td>
                                                <?php
                                                $condition_class = '';
                                                switch($survey['condition']) {
                                                    case 'ดี':
                                                        $condition_class = 'badge-success';
                                                        break;
                                                    case 'พอใช้':
                                                        $condition_class = 'badge-warning';
                                                        break;
                                                    case 'ชำรุด':
                                                        $condition_class = 'badge-danger';
                                                        break;
                                                    case 'ไม่สามารถใช้งานได้':
                                                        $condition_class = 'badge-dark';
                                                        break;
                                                    default:
                                                        $condition_class = 'badge-secondary';
                                                }
                                                ?>
                                                <span class="badge <?php echo $condition_class; ?>">
                                                    <?php echo $survey['condition']; ?>
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3">
                                <div class="card-header bg-info text-white">
                                    <h5 class="mb-0"><i class="fas fa-box"></i> ข้อมูลครุภัณฑ์</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 40%">รหัสครุภัณฑ์</th>
                                            <td><?php echo $survey['asset_id']; ?></td>
                                        </tr>
                                        <tr>
                                            <th>ชื่อครุภัณฑ์</th>
                                            <td><?php echo htmlspecialchars($survey['asset_name']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>ประเภท</th>
                                            <td><?php echo htmlspecialchars($survey['asset_type']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>หมายเลขซีเรียล</th>
                                            <td><?php echo htmlspecialchars($survey['serial_number']); ?></td>
                                        </tr>
                                        <tr>
                                            <th>สถานที่ตั้ง</th>
                                            <td><?php echo htmlspecialchars($survey['current_location']); ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header bg-secondary text-white">
                            <h5 class="mb-0"><i class="fas fa-clipboard"></i> หมายเหตุ</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($survey['notes'])): ?>
                                <div class="p-3 bg-light rounded">
                                    <?php echo nl2br(htmlspecialchars($survey['notes'])); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-muted text-center p-3">
                                    <i class="fas fa-info-circle"></i> ไม่มีบันทึกเพิ่มเติม
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-dark text-white">
                            <h5 class="mb-0"><i class="fas fa-history"></i> ประวัติการสำรวจ</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ปีที่สำรวจ</th>
                                            <th>วันที่สำรวจ</th>
                                            <th>สภาพ</th>
                                            <th>ผู้สำรวจ</th>
                                            <th>หมายเหตุ</th>
                                        </tr>
                                    </thead>
                                    <tbody id="surveyHistory">
                                        <!-- จะถูกเติมด้วย AJAX -->
                                        <tr>
                                            <td colspan="5" class="text-center">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="sr-only">กำลังโหลด...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // โหลดประวัติการสำรวจของครุภัณฑ์นี้
    $.ajax({
        url: '<?php echo site_url("api/surveys/history/" . $survey["asset_id"]); ?>',
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var html = '';
            
            if (data.length > 0) {
                $.each(data, function(index, item) {
                    var conditionClass = '';
                    switch(item.condition) {
                        case 'ดี': conditionClass = 'badge-success'; break;
                        case 'พอใช้': conditionClass = 'badge-warning'; break;
                        case 'ชำรุด': conditionClass = 'badge-danger'; break;
                        case 'ไม่สามารถใช้งานได้': conditionClass = 'badge-dark'; break;
                        default: conditionClass = 'badge-secondary';
                    }
                    
                    html += '<tr>';
                    html += '<td>' + item.survey_year + '</td>';
                    html += '<td>' + formatDate(item.survey_date) + '</td>';
                    html += '<td><span class="badge ' + conditionClass + '">' + item.condition + '</span></td>';
                    html += '<td>' + item.surveyed_by + '</td>';
                    html += '<td>' + (item.notes ? item.notes.substring(0, 30) + (item.notes.length > 30 ? '...' : '') : '-') + '</td>';
                    html += '</tr>';
                });
            } else {
                html = '<tr><td colspan="5" class="text-center">ไม่พบประวัติการสำรวจ</td></tr>';
            }
            
            $('#surveyHistory').html(html);
        },
        error: function() {
            $('#surveyHistory').html('<tr><td colspan="5" class="text-center text-danger">ไม่สามารถโหลดข้อมูลได้</td></tr>');
        }
    });
    
    // ฟังก์ชันแปลงรูปแบบวันที่
    function formatDate(dateString) {
        var date = new Date(dateString);
        return ('0' + date.getDate()).slice(-2) + '/' + 
               ('0' + (date.getMonth() + 1)).slice(-2) + '/' + 
               date.getFullYear();
    }
});
</script>

<style>
.badge {
    font-size: 90%;
    padding: 0.4em 0.6em;
}

.table th {
    background-color: #f8f9fa;
}

@media print {
    .btn {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        color: #000 !important;
    }
    
    .badge {
        color: #000 !important;
        background-color: transparent !important;
        border: 1px solid #000 !important;
    }
}
</style>

