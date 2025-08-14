<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">
                        <i class="fas fa-search"></i> รายงานสำรวจครุภัณฑ์ประจำปี
                    </h4>
                    <a href="<?php echo site_url('surveys/add'); ?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> เพิ่มข้อมูลสำรวจ
                    </a>
                </div>
                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <input type="text" id="searchInput" class="form-control" placeholder="ค้นหา...">
                        </div>
                        <div class="col-md-2">
                            <select id="yearFilter" class="form-control">
                                <option value="">ทุกปี</option>
                                <?php for($year = date('Y'); $year >= 2020; $year--): ?>
                                    <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select id="conditionFilter" class="form-control">
                                <option value="">ทุกสภาพ</option>
                                <option value="ดี">ดี</option>
                                <option value="พอใช้">พอใช้</option>
                                <option value="ชำรุด">ชำรุด</option>
                                <option value="ไม่สามารถใช้งานได้">ไม่สามารถใช้งานได้</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button id="exportBtn" class="btn btn-success">
                                <i class="fas fa-file-excel"></i> ส่งออก CSV
                            </button>
                            <button id="printBtn" class="btn btn-info">
                                <i class="fas fa-print"></i> พิมพ์
                            </button>
                        </div>
                    </div>

                    <!-- Statistics Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>ทั้งหมด</h5>
                                            <h3 id="totalSurveys"><?php echo count($surveys); ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-clipboard-list fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>สภาพดี</h5>
                                            <h3 id="goodCondition">
                                                <?php echo count(array_filter($surveys, function($s) { return $s['condition'] == 'ดี'; })); ?>
                                            </h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-check-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>พอใช้</h5>
                                            <h3 id="fairCondition">
                                                <?php echo count(array_filter($surveys, function($s) { return $s['condition'] == 'พอใช้'; })); ?>
                                            </h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-exclamation-triangle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>ชำรุด</h5>
                                            <h3 id="damagedCondition">
                                                <?php echo count(array_filter($surveys, function($s) { return in_array($s['condition'], ['ชำรุด', 'ไม่สามารถใช้งานได้']); })); ?>
                                            </h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="fas fa-times-circle fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div class="table-responsive">
                        <table id="surveysTable" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>ปีที่สำรวจ</th>
                                    <th>ครุภัณฑ์</th>
                                    <th>ประเภท</th>
                                    <th>สภาพ</th>
                                    <th>ผู้สำรวจ</th>
                                    <th>วันที่สำรวจ</th>
                                    <th>หมายเหตุ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1; foreach($surveys as $survey): ?>
                                <tr>
                                    <td><?php echo $i++; ?></td>
                                    <td><?php echo $survey['survey_year']; ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($survey['asset_name']); ?></strong><br>
                                        <small class="text-muted"><?php echo htmlspecialchars($survey['serial_number']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($survey['asset_type']); ?></td>
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
                                    <td><?php echo htmlspecialchars($survey['surveyed_by']); ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($survey['survey_date'])); ?></td>
                                    <td>
                                        <small><?php echo htmlspecialchars(substr($survey['notes'], 0, 30)) . (strlen($survey['notes']) > 30 ? "..." : ""); ?></small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="<?php echo site_url('surveys/view/'.$survey['survey_id']); ?>" 
                                               class="btn btn-sm btn-info" title="ดูรายละเอียด">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="<?php echo site_url('surveys/edit/'.$survey['survey_id']); ?>" 
                                               class="btn btn-sm btn-warning" title="แก้ไข">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?php echo site_url('surveys/delete/'.$survey['survey_id']); ?>" 
                                               class="btn btn-sm btn-danger" title="ลบ"
                                               onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบข้อมูลนี้?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#surveysTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json"
        },
        "pageLength": 25,
        "order": [[ 1, "desc" ]], // Sort by survey year descending
        "columnDefs": [
            { "orderable": false, "targets": [8] } // Disable sorting for action column
        ]
    });

    // Search functionality
    $('#searchInput').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Year filter
    $('#yearFilter').on('change', function() {
        table.column(1).search(this.value).draw();
    });

    // Condition filter
    $('#conditionFilter').on('change', function() {
        table.column(4).search(this.value).draw();
    });

    // Export to CSV
    $('#exportBtn').on('click', function() {
        var csv = 'ลำดับ,ปีที่สำรวจ,ครุภัณฑ์,ประเภท,สภาพ,ผู้สำรวจ,วันที่สำรวจ,หมายเหตุ\n';
        
        table.rows({ search: 'applied' }).every(function() {
            var data = this.data();
            csv += '"' + data[0] + '","' + data[1] + '","' + $(data[2]).text().replace(/\n/g, ' ') + '","' + data[3] + '","' + $(data[4]).text() + '","' + data[5] + '","' + data[6] + '","' + $(data[7]).text() + '"\n';
        });

        var blob = new Blob(["\ufeff" + csv], { type: 'text/csv;charset=utf-8;' });
        var link = document.createElement("a");
        var url = URL.createObjectURL(blob);
        link.setAttribute("href", url);
        link.setAttribute("download", "รายงานสำรวจครุภัณฑ์_" + new Date().toISOString().slice(0,10) + ".csv");
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // Print functionality
    $('#printBtn').on('click', function() {
        window.print();
    });
});
</script>

<style>
@media print {
    .card-header .btn,
    .btn-group,
    .row:first-child {
        display: none !important;
    }
    
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    
    .table {
        font-size: 12px;
    }
    
    .badge {
        color: #000 !important;
        background-color: transparent !important;
        border: 1px solid #000 !important;
    }
}
</style>

