<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">แทงจำหน่ายครุภัณฑ์ใหม่</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">ข้อมูลการจำหน่าย</h6>
        </div>
        <div class="card-body">
            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
            <?php echo form_open("disposals/add_process"); ?>

            <div class="form-group">
                <label for="asset_id">ครุภัณฑ์ที่จำหน่าย <span class="text-danger">*</span></label>
                <select class="form-control" id="asset_id" name="asset_id" required>
                    <option value="">-- เลือกครุภัณฑ์ --</option>
                    <?php foreach ($disposable_assets as $asset): ?>
                        <option value="<?php echo $asset["asset_id"]; ?>" <?php echo set_select("asset_id", $asset["asset_id"]); ?> data-purchase-price="<?php echo $asset["purchase_price"]; ?>" data-depreciation-rate="<?php echo $asset["depreciation_rate"]; ?>" data-purchase-date="<?php echo $asset["purchase_date"]; ?>">
                            <?php echo htmlspecialchars($asset["asset_name"]) . " (" . htmlspecialchars($asset["serial_number"]) . ")"; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="purchase_price">ราคาซื้อ (บาท)</label>
                <input type="text" class="form-control" id="purchase_price" readonly>
            </div>

            <div class="form-group">
                <label for="depreciation_rate">อัตราค่าเสื่อม (%)</label>
                <input type="text" class="form-control" id="depreciation_rate" readonly>
            </div>

            <div class="form-group">
                <label for="purchase_date">วันที่ซื้อ</label>
                <input type="text" class="form-control" id="purchase_date" readonly>
            </div>

            <div class="form-group">
                <label for="book_value">มูลค่าตามบัญชี (บาท)</label>
                <input type="text" class="form-control" id="book_value" readonly>
            </div>

            <div class="form-group">
                <label for="disposal_date">วันที่จำหน่าย <span class="text-danger">*</span></label>
                <input type="date" class="form-control" id="disposal_date" name="disposal_date" value="<?php echo set_value("disposal_date", date("Y-m-d")); ?>" required>
            </div>

            <div class="form-group">
                <label for="disposal_method">วิธีการจำหน่าย <span class="text-danger">*</span></label>
                <select class="form-control" id="disposal_method" name="disposal_method" required>
                    <option value="">-- เลือกวิธีการจำหน่าย --</option>
                    <option value="ขาย" <?php echo set_select("disposal_method", "ขาย"); ?>>ขาย</option>
                    <option value="บริจาค" <?php echo set_select("disposal_method", "บริจาค"); ?>>บริจาค</option>
                    <option value="ทิ้ง" <?php echo set_select("disposal_method", "ทิ้ง"); ?>>ทิ้ง</option>
                    <option value="แลกเปลี่ยน" <?php echo set_select("disposal_method", "แลกเปลี่ยน"); ?>>แลกเปลี่ยน</option>
                    <option value="อื่นๆ" <?php echo set_select("disposal_method", "อื่นๆ"); ?>>อื่นๆ</option>
                </select>
            </div>

            <div class="form-group">
                <label for="disposal_price">ราคาที่จำหน่ายได้ (บาท)</label>
                <input type="number" step="0.01" class="form-control" id="disposal_price" name="disposal_price" value="<?php echo set_value("disposal_price"); ?>">
            </div>

            <div class="form-group">
                <label for="reason">เหตุผลในการจำหน่าย <span class="text-danger">*</span></label>
                <textarea class="form-control" id="reason" name="reason" rows="3" required><?php echo set_value("reason"); ?></textarea>
            </div>

            <div class="form-group">
                <label for="disposal_by">ผู้ดำเนินการ <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="disposal_by" name="disposal_by" value="<?php echo set_value("disposal_by"); ?>" required>
            </div>

            <button type="submit" class="btn btn-primary">บันทึกการจำหน่าย</button>
            <a href="<?php echo base_url("disposals"); ?>" class="btn btn-secondary">ยกเลิก</a>

            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        function calculateBookValue() {
            var purchasePrice = parseFloat($("#purchase_price").val());
            var depreciationRate = parseFloat($("#depreciation_rate").val());
            var purchaseDate = $("#purchase_date").val();
            var disposalDate = $("#disposal_date").val();

            if (isNaN(purchasePrice) || isNaN(depreciationRate) || !purchaseDate || !disposalDate) {
                $("#book_value").val("");
                return;
            }

            var pDate = new Date(purchaseDate);
            var dDate = new Date(disposalDate);

            var yearsDiff = dDate.getFullYear() - pDate.getFullYear();
            var monthsDiff = dDate.getMonth() - pDate.getMonth();
            var daysDiff = dDate.getDate() - pDate.getDate();

            if (daysDiff < 0) {
                monthsDiff--;
            }
            if (monthsDiff < 0) {
                yearsDiff--;
                monthsDiff += 12;
            }

            var totalMonths = (yearsDiff * 12) + monthsDiff;
            var annualDepreciation = purchasePrice * (depreciationRate / 100);
            var monthlyDepreciation = annualDepreciation / 12;
            var accumulatedDepreciation = monthlyDepreciation * totalMonths;
            var bookValue = purchasePrice - accumulatedDepreciation;

            if (bookValue < 0) {
                bookValue = 0;
            }
            $("#book_value").val(bookValue.toFixed(2));
        }

        $("#asset_id").change(function() {
            var selectedOption = $(this).find(":selected");
            var purchasePrice = selectedOption.data("purchase-price");
            var depreciationRate = selectedOption.data("depreciation-rate");
            var purchaseDate = selectedOption.data("purchase-date");

            $("#purchase_price").val(purchasePrice);
            $("#depreciation_rate").val(depreciationRate);
            $("#purchase_date").val(purchaseDate);
            calculateBookValue();
        });

        $("#disposal_date").change(function() {
            calculateBookValue();
        });

        // Initial calculation if an asset is pre-selected (e.g., after validation error)
        if ($("#asset_id").val()) {
            $("#asset_id").trigger("change");
        }
    });
</script>

