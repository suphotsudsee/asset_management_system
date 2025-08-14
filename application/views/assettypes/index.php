<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-tags"></i>
        ประเภทครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?php echo base_url('assettypes/add'); ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> เพิ่มประเภท
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?php if (!empty($types)): ?>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ชื่อประเภท</th>
                            <th>คำอธิบาย</th>
                            <th>การดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($types as $type): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($type['type_name']); ?></td>
                                <td><?php echo htmlspecialchars($type['description']); ?></td>
                                <td>
                                    <a href="<?php echo base_url('assettypes/edit/' . $type['type_id']); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?php echo base_url('assettypes/delete/' . $type['type_id']); ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบประเภทนี้?');">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-center text-muted mb-0">ไม่พบประเภทครุภัณฑ์</p>
        <?php endif; ?>
    </div>
</div>
