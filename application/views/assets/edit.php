<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">
        <i class="fas fa-edit"></i>
        แก้ไขครุภัณฑ์
    </h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group mr-2">
            <a href="<?php echo base_url('assets'); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> กลับ
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle"></i>
                    ข้อมูลครุภัณฑ์
                </h6>
            </div>
            <div class="card-body">
                <?php echo form_open('assets/update/'.$asset['asset_id'], array('id' => 'assetForm')); ?>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="asset_name" class="required">ชื่อครุภัณฑ์</label>
                        <input type="text" class="form-control <?php echo form_error('asset_name') ? 'is-invalid' : ''; ?>"
                               id="asset_name" name="asset_name"
                               value="<?php echo set_value('asset_name', $asset['asset_name']); ?>"
                               required>
                        <div class="invalid-feedback">
                            <?php echo form_error('asset_name'); ?>
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="asset_type" class="required">ประเภทครุภัณฑ์</label>
                        <select class="form-control <?php echo form_error('asset_type') ? 'is-invalid' : ''; ?>"
                                id="asset_type" name="asset_type" required>
                            <option value="">เลือกประเภท</option>
                            <option value="คอมพิวเตอร์" <?php echo set_select('asset_type', 'คอมพิวเตอร์', $asset['asset_type']=='คอมพิวเตอร์'); ?>>คอมพิวเตอร์</option>
                            <option value="เครื่องพิมพ์" <?php echo set_select('asset_type', 'เครื่องพิมพ์', $asset['asset_type']=='เครื่องพิมพ์'); ?>>เครื่องพิมพ์</option>
                            <option value="เครื่องถ่ายเอกสาร" <?php echo set_select('asset_type', 'เครื่องถ่ายเอกสาร', $asset['asset_type']=='เครื่องถ่ายเอกสาร'); ?>>เครื่องถ่ายเอกสาร</option>
                            <option value="โปรเจคเตอร์" <?php echo set_select('asset_type', 'โปรเจคเตอร์', $asset['asset_type']=='โปรเจคเตอร์'); ?>>โปรเจคเตอร์</option>
                            <option value="เครื่องปรับอากาศ" <?php echo set_select('asset_type', 'เครื่องปรับอากาศ', $asset['asset_type']=='เครื่องปรับอากาศ'); ?>>เครื่องปรับอากาศ</option>
                            <option value="รถยนต์" <?php echo set_select('asset_type', 'รถยนต์', $asset['asset_type']=='รถยนต์'); ?>>รถยนต์</option>
                            <option value="เฟอร์นิเจอร์" <?php echo set_select('asset_type', 'เฟอร์นิเจอร์', $asset['asset_type']=='เฟอร์นิเจอร์'); ?>>เฟอร์นิเจอร์</option>
                            <option value="อุปกรณ์เครือข่าย" <?php echo set_select('asset_type', 'อุปกรณ์เครือข่าย', $asset['asset_type']=='อุปกรณ์เครือข่าย'); ?>>อุปกรณ์เครือข่าย</option>
                            <option value="อื่นๆ" <?php echo set_select('asset_type', 'อื่นๆ', $asset['asset_type']=='อื่นๆ'); ?>>อื่นๆ</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('asset_type'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="serial_number">หมายเลขซีเรียล</label>
                        <input type="text" class="form-control <?php echo form_error('serial_number') ? 'is-invalid' : ''; ?>"
                               id="serial_number" name="serial_number"
                               value="<?php echo set_value('serial_number', $asset['serial_number']); ?>">
                        <div class="invalid-feedback">
                            <?php echo form_error('serial_number'); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="purchase_date" class="required">วันที่จัดซื้อ</label>
                        <input type="date" class="form-control <?php echo form_error('purchase_date') ? 'is-invalid' : ''; ?>"
                               id="purchase_date" name="purchase_date"
                               value="<?php echo set_value('purchase_date', $asset['purchase_date']); ?>" required>
                        <div class="invalid-feedback">
                            <?php echo form_error('purchase_date'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="purchase_price" class="required">ราคาจัดซื้อ (บาท)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('purchase_price') ? 'is-invalid' : ''; ?>"
                                   id="purchase_price" name="purchase_price"
                                   value="<?php echo set_value('purchase_price', $asset['purchase_price']); ?>"
                                   step="0.01" min="0" required>
                            <div class="input-group-append">
                                <span class="input-group-text">บาท</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('purchase_price'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="depreciation_rate" class="required">อัตราค่าเสื่อมราคา (%/ปี)</label>
                        <div class="input-group">
                            <input type="number" class="form-control <?php echo form_error('depreciation_rate') ? 'is-invalid' : ''; ?>"
                                   id="depreciation_rate" name="depreciation_rate"
                                   value="<?php echo set_value('depreciation_rate', $asset['depreciation_rate']); ?>"
                                   step="0.01" min="0" max="100" required>
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="invalid-feedback">
                                <?php echo form_error('depreciation_rate'); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="current_location" class="required">สถานที่ตั้งปัจจุบัน</label>
                        <input type="text" class="form-control <?php echo form_error('current_location') ? 'is-invalid' : ''; ?>"
                               id="current_location" name="current_location"
                               value="<?php echo set_value('current_location', $asset['current_location']); ?>"
                               required>
                        <div class="invalid-feedback">
                            <?php echo form_error('current_location'); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="status" class="required">สถานะ</label>
                        <select class="form-control <?php echo form_error('status') ? 'is-invalid' : ''; ?>" id="status" name="status" required>
                            <option value="">เลือกสถานะ</option>
                            <option value="ใช้งาน" <?php echo set_select('status', 'ใช้งาน', $asset['status']=='ใช้งาน'); ?>>ใช้งาน</option>
                            <option value="ชำรุด" <?php echo set_select('status', 'ชำรุด', $asset['status']=='ชำรุด'); ?>>ชำรุด</option>
                            <option value="ซ่อมแซม" <?php echo set_select('status', 'ซ่อมแซม', $asset['status']=='ซ่อมแซม'); ?>>ซ่อมแซม</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('status'); ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="acquisition_method" class="required">วิธีการได้มา</label>
                        <select class="form-control <?php echo form_error('acquisition_method') ? 'is-invalid' : ''; ?>"
                                id="acquisition_method" name="acquisition_method" required>
                            <option value="">เลือกวิธีการ</option>
                            <option value="จัดซื้อ" <?php echo set_select('acquisition_method', 'จัดซื้อ', $asset['acquisition_method']=='จัดซื้อ'); ?>>จัดซื้อ</option>
                            <option value="รับบริจาค" <?php echo set_select('acquisition_method', 'รับบริจาค', $asset['acquisition_method']=='รับบริจาค'); ?>>รับบริจาค</option>
                            <option value="โอนจากหน่วยงานอื่น" <?php echo set_select('acquisition_method', 'โอนจากหน่วยงานอื่น', $asset['acquisition_method']=='โอนจากหน่วยงานอื่น'); ?>>โอนจากหน่วยงานอื่น</option>
                            <option value="เช่า" <?php echo set_select('acquisition_method', 'เช่า', $asset['acquisition_method']=='เช่า'); ?>>เช่า</option>
                            <option value="อื่นๆ" <?php echo set_select('acquisition_method', 'อื่นๆ', $asset['acquisition_method']=='อื่นๆ'); ?>>อื่นๆ</option>
                        </select>
                        <div class="invalid-feedback">
                            <?php echo form_error('acquisition_method'); ?>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="responsible_person" class="required">ผู้รับผิดชอบ</label>
                        <input type="text" class="form-control <?php echo form_error('responsible_person') ? 'is-invalid' : ''; ?>"
                               id="responsible_person" name="responsible_person"
                               value="<?php echo set_value('responsible_person', $asset['responsible_person']); ?>"
                               required>
                        <div class="invalid-feedback">
                            <?php echo form_error('responsible_person'); ?>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="warranty_info">ข้อมูลการรับประกัน</label>
                    <textarea class="form-control" id="warranty_info" name="warranty_info" rows="3"><?php echo set_value('warranty_info', $asset['warranty_info']); ?></textarea>
                </div>

                <div class="form-group">
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-save"></i> อัปเดตข้อมูล
                            </button>
                        </div>
                        <div class="col-md-6">
                            <a href="<?php echo base_url('assets'); ?>" class="btn btn-secondary btn-block">
                                <i class="fas fa-times"></i> ยกเลิก
                            </a>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>

<style>
.required::after {
    content: " *";
    color: red;
}
</style>
