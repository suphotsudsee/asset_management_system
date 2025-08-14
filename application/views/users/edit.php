<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-edit"></i>
        แก้ไขผู้ใช้
    </h1>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open('users/update/' . $user['user_id']); ?>
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" name="username" value="<?php echo set_value('username', $user['username']); ?>">
                <?php echo form_error('username'); ?>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน (เว้นว่างหากไม่ต้องการเปลี่ยน)</label>
                <input type="password" class="form-control" name="password">
                <?php echo form_error('password'); ?>
            </div>
            <div class="form-group">
                <label for="full_name">ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" name="full_name" value="<?php echo set_value('full_name', $user['full_name']); ?>">
                <?php echo form_error('full_name'); ?>
            </div>
            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" class="form-control" name="email" value="<?php echo set_value('email', $user['email']); ?>">
                <?php echo form_error('email'); ?>
            </div>
            <div class="form-group">
                <label for="role">บทบาท</label>
                <select name="role" class="form-control">
                    <option value="admin" <?php echo ($user['role'] == 'admin') ? 'selected' : ''; ?>>admin</option>
                    <option value="user" <?php echo ($user['role'] == 'user') ? 'selected' : ''; ?>>user</option>
                    <option value="viewer" <?php echo ($user['role'] == 'viewer') ? 'selected' : ''; ?>>viewer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary">ยกเลิก</a>
        <?php echo form_close(); ?>
    </div>
</div>
