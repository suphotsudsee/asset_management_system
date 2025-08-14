<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-user-plus"></i>
        เพิ่มผู้ใช้ใหม่
    </h1>
</div>

<div class="card">
    <div class="card-body">
        <?php echo form_open('users/store'); ?>
            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" name="username" value="<?php echo set_value('username'); ?>">
                <?php echo form_error('username'); ?>
            </div>
            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input type="password" class="form-control" name="password">
                <?php echo form_error('password'); ?>
            </div>
            <div class="form-group">
                <label for="full_name">ชื่อ-นามสกุล</label>
                <input type="text" class="form-control" name="full_name" value="<?php echo set_value('full_name'); ?>">
                <?php echo form_error('full_name'); ?>
            </div>
            <div class="form-group">
                <label for="email">อีเมล</label>
                <input type="email" class="form-control" name="email" value="<?php echo set_value('email'); ?>">
                <?php echo form_error('email'); ?>
            </div>
            <div class="form-group">
                <label for="role">บทบาท</label>
                <select name="role" class="form-control">
                    <option value="admin">admin</option>
                    <option value="user" selected>user</option>
                    <option value="viewer">viewer</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="<?php echo base_url('users'); ?>" class="btn btn-secondary">ยกเลิก</a>
        <?php echo form_close(); ?>
    </div>
</div>
