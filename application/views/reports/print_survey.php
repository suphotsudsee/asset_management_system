<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
    <style>
        body { font-size: 14px; }
        .table th, .table td { font-size: 12px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
<div class="container mt-4">
    <h2 class="text-center mb-4"><?php echo $page_title; ?></h2>
    <?php if (!empty($survey_stats)): ?>
    <p class="text-right">
        ครุภัณฑ์ทั้งหมด <?php echo number_format($survey_stats['total_assets']); ?> รายการ,
        สำรวจแล้ว <?php echo number_format($survey_stats['surveyed']); ?> รายการ,
        ยังไม่สำรวจ <?php echo number_format($survey_stats['not_surveyed']); ?> รายการ
    </p>
    <?php endif; ?>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>รหัส</th>
                <th>ชื่อครุภัณฑ์</th>
                <th>ประเภท</th>
                <th>หมายเลขซีเรียล</th>
                <th>สถานที่</th>
                <th>ผู้รับผิดชอบ</th>
                <th>ราคาทุน</th>
                <th>ค่าเสื่อมสะสม</th>
                <th>มูลค่าตามบัญชี</th>
                <th>สถานะการสำรวจ</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($assets)): foreach ($assets as $asset): ?>
            <tr>
                <td><?php echo htmlspecialchars($asset['asset_code']); ?></td>
                <td><?php echo htmlspecialchars($asset['asset_name']); ?></td>
                <td><?php echo htmlspecialchars($asset['category']); ?></td>
                <td><?php echo htmlspecialchars($asset['serial_number'] ?: '-'); ?></td>
                <td><?php echo htmlspecialchars($asset['current_location']); ?></td>
                <td><?php echo htmlspecialchars($asset['responsible_person']); ?></td>
                <td class="text-right"><?php echo number_format($asset['purchase_price'], 2); ?></td>
                <td class="text-right"><?php echo number_format($asset['accumulated_depreciation'], 2); ?></td>
                <td class="text-right"><?php echo number_format($asset['book_value'], 2); ?></td>
                <td><?php echo $asset['survey_status']; ?></td>
            </tr>
        <?php endforeach; endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
