<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { font-size: 14px; }
            .container { max-width: none; }
        }
        
        body {
            font-family: 'Sarabun', 'TH SarabunPSK', sans-serif;
            line-height: 1.6;
        }
        
        .letterhead {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .letterhead h1 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .letterhead h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .document-title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0;
            text-decoration: underline;
        }
        
        .document-number {
            text-align: right;
            margin-bottom: 20px;
        }
        
        .content-section {
            margin-bottom: 25px;
        }
        
        .content-section h4 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .info-table td {
            padding: 8px 0;
            vertical-align: top;
        }
        
        .info-table .label {
            width: 200px;
            font-weight: bold;
        }
        
        .info-table .colon {
            width: 20px;
            text-align: center;
        }
        
        .signature-section {
            margin-top: 50px;
        }
        
        .signature-box {
            text-align: center;
            margin: 40px 0;
        }
        
        .signature-line {
            border-bottom: 1px solid #000;
            width: 300px;
            margin: 0 auto 10px;
            height: 50px;
        }
        
        .approval-section {
            border: 2px solid #000;
            padding: 20px;
            margin-top: 30px;
        }
        
        .approval-section h4 {
            text-align: center;
            margin-bottom: 20px;
            border: none;
        }
        
        .checkbox-group {
            margin: 15px 0;
        }
        
        .checkbox {
            display: inline-block;
            width: 15px;
            height: 15px;
            border: 1px solid #000;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        .priority-high { color: #dc3545; font-weight: bold; }
        .priority-medium { color: #ffc107; font-weight: bold; }
        .priority-low { color: #28a745; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Print Button -->
        <div class="no-print mb-3">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> พิมพ์
            </button>
            <button onclick="window.close()" class="btn btn-secondary ml-2">
                <i class="fas fa-times"></i> ปิด
            </button>
        </div>
        
        <!-- Letterhead -->
        <div class="letterhead">
            <h1>บริษัท/หน่วยงาน [ชื่อองค์กร]</h1>
            <h2>ฝ่ายบริหารทั่วไป / ฝ่ายซ่อมบำรุง</h2>
            <p>ที่อยู่: [ที่อยู่องค์กร]<br>
            โทรศัพท์: [เบอร์โทรศัพท์] แฟกซ์: [เบอร์แฟกซ์]</p>
        </div>
        
        <!-- Document Number and Date -->
        <div class="document-number">
            <strong>เลขที่: </strong>ซบ <?php echo str_pad($repair['repair_id'], 4, '0', STR_PAD_LEFT); ?>/<?php echo date('Y', strtotime($repair['request_date'])) + 543; ?><br>
            <strong>วันที่: </strong><?php echo date('d', strtotime($repair['request_date'])); ?> 
            <?php 
            $months = array('', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                          'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม');
            echo $months[date('n', strtotime($repair['request_date']))];
            echo ' พ.ศ. ' . (date('Y', strtotime($repair['request_date'])) + 543);
            ?>
        </div>
        
        <!-- Document Title -->
        <div class="document-title">
            หนังสือขออนุญาตซ่อมแซมครุภัณฑ์
        </div>
        
        <!-- Asset Information -->
        <div class="content-section">
            <h4>ข้อมูลครุภัณฑ์</h4>
            <table class="info-table">
                <tr>
                    <td class="label">ชื่อครุภัณฑ์</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars($repair['asset_name']); ?></td>
                </tr>
                <tr>
                    <td class="label">หมายเลขซีเรียล</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars($repair['serial_number'] ?: '-'); ?></td>
                </tr>
                <tr>
                    <td class="label">สถานที่ตั้ง</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars($repair['current_location']); ?></td>
                </tr>
                <tr>
                    <td class="label">ผู้รับผิดชอบ</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars($repair['responsible_person']); ?></td>
                </tr>
            </table>
        </div>
        
        <!-- Problem Description -->
        <div class="content-section">
            <h4>รายละเอียดปัญหา</h4>
            <p style="text-align: justify; text-indent: 2em;">
                <?php echo nl2br(htmlspecialchars($repair['problem_description'])); ?>
            </p>
        </div>
        
        <!-- Repair Information -->
        <div class="content-section">
            <h4>ข้อมูลการซ่อมแซม</h4>
            <table class="info-table">
                <tr>
                    <td class="label">ประเภทการซ่อม</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars(isset($repair['repair_type']) ? $repair['repair_type'] : ''); ?></td>
                </tr>
                <tr>
                    <td class="label">ระดับความสำคัญ</td>
                    <td class="colon">:</td>
                    <td>
                        <span class="priority-<?php echo ($repair['priority'] == 'สูง') ? 'high' : (($repair['priority'] == 'ปานกลาง') ? 'medium' : 'low'); ?>">
                            <?php echo htmlspecialchars($repair['priority']); ?>
                        </span>
                    </td>
                </tr>
                <tr>
                    <td class="label">ค่าใช้จ่ายประมาณการ</td>
                    <td class="colon">:</td>
                    <td>
                        <?php if ($repair['estimated_cost'] > 0): ?>
                            <?php echo number_format($repair['estimated_cost'], 2); ?> บาท
                        <?php else: ?>
                            ยังไม่ทราบ
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td class="label">วันที่คาดว่าจะเสร็จ</td>
                    <td class="colon">:</td>
                    <td>
                        <?php if ($repair['expected_completion']): ?>
                            <?php echo date('d/m/Y', strtotime($repair['expected_completion'])); ?>
                        <?php else: ?>
                            ตามความเหมาะสม
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Requester Information -->
        <div class="content-section">
            <h4>ข้อมูลผู้แจ้ง</h4>
            <table class="info-table">
                <tr>
                    <td class="label">ชื่อผู้แจ้ง</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars($repair['requested_by']); ?></td>
                </tr>
                <tr>
                    <td class="label">ข้อมูลติดต่อ</td>
                    <td class="colon">:</td>
                    <td><?php echo htmlspecialchars($repair['contact_info']); ?></td>
                </tr>
                <tr>
                    <td class="label">วันที่แจ้ง</td>
                    <td class="colon">:</td>
                    <td><?php echo date('d/m/Y H:i', strtotime($repair['request_date'])); ?> น.</td>
                </tr>
            </table>
        </div>
        
        <!-- Additional Notes -->
        <?php if (!empty($repair['notes'])): ?>
        <div class="content-section">
            <h4>หมายเหตุเพิ่มเติม</h4>
            <p style="text-align: justify; text-indent: 2em;">
                <?php echo nl2br(htmlspecialchars($repair['notes'])); ?>
            </p>
        </div>
        <?php endif; ?>
        
        <!-- Approval Section -->
        <div class="approval-section">
            <h4>สำหรับเจ้าหน้าที่</h4>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="checkbox-group">
                        <span class="checkbox"></span> อนุมัติ
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox"></span> ไม่อนุมัติ
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox"></span> รอพิจารณา
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="checkbox-group">
                        <span class="checkbox"></span> ซ่อมภายใน
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox"></span> ซ่อมภายนอก
                    </div>
                    <div class="checkbox-group">
                        <span class="checkbox"></span> เปลี่ยนครุภัณฑ์ใหม่
                    </div>
                </div>
            </div>
            
            <div style="margin-top: 20px;">
                <strong>หมายเหตุ:</strong>
                <div style="border-bottom: 1px solid #000; height: 30px; margin: 10px 0;"></div>
                <div style="border-bottom: 1px solid #000; height: 30px; margin: 10px 0;"></div>
            </div>
            
            <div class="row" style="margin-top: 30px;">
                <div class="col-md-6">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div>ผู้พิจารณา</div>
                        <div>วันที่ .........................</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div>ผู้อนุมัติ</div>
                        <div>วันที่ .........................</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Requester Signature -->
        <div class="signature-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div>ผู้แจ้ง</div>
                        <div>(<?php echo htmlspecialchars($repair['requested_by']); ?>)</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="signature-box">
                        <div class="signature-line"></div>
                        <div>หัวหน้างาน</div>
                        <div>วันที่ .........................</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="margin-top: 50px; text-align: center; font-size: 12px; color: #666;">
            <p>หนังสือฉบับนี้ออกโดยระบบจัดการครุภัณฑ์ เมื่อวันที่ <?php echo date('d/m/Y H:i'); ?> น.</p>
            <p>กรุณาเก็บรักษาหนังสือฉบับนี้ไว้เป็นหลักฐาน</p>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Auto focus for better printing experience
            window.focus();
        };
        
        // Print function
        function printDocument() {
            window.print();
        }
        
        // Handle print button click
        document.addEventListener('DOMContentLoaded', function() {
            // Add keyboard shortcut for printing (Ctrl+P)
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'p') {
                    e.preventDefault();
                    printDocument();
                }
            });
        });
    </script>
</body>
</html>

