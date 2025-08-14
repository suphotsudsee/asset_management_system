</main>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center py-3 mt-5">
      <div class="container">
        <p class="text-muted mb-0">
          &copy; <?php echo date('Y'); ?> ระบบจัดการครุภัณฑ์ |
          พัฒนาด้วย CodeIgniter 3 + Bootstrap 4
        </p>
      </div>
    </footer>

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/js/jquery-3.6.0.min.js'); ?>"></script>

    <!-- Bootstrap JS -->
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <!-- Date Picker JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.th.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Custom JS (ของโปรเจ็กต์เดิม) -->
    <script src="<?php echo base_url('assets/js/custom.js'); ?>"></script>

    <!-- Page specific scripts (external files) -->
    <?php if (isset($page_scripts) && is_array($page_scripts)): ?>
      <?php foreach ($page_scripts as $script): ?>
        <script src="<?php echo base_url($script); ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>

    <!-- Inline scripts (เฉพาะหน้า) -->
    <?php if (isset($inline_scripts)): ?>
      <script><?php echo $inline_scripts; ?></script>
    <?php endif; ?>

    <!-- ===== Global UI behaviors & DataTables init (กัน init ซ้ำทั้งระบบ) ===== -->
    <script>
      (function ($) {
        /* ---------- Global DataTables ---------- */

        // ค่ามาตรฐานทั้งระบบ
        window.DT_DEFAULTS = {
          pageLength: 25,
          ordering: true,
          autoWidth: false,
          responsive: true,
          retrieve: true, // ถ้ามี instance อยู่แล้ว จะดึงกลับมาแทน ไม่โยน error
          language: {
            url: "//cdn.datatables.net/plug-ins/1.11.5/i18n/Thai.json"
          }
        };

        // ฟังก์ชัน init แบบกันซ้ำ
        window.initDataTables = function (selector, opts) {
          var $tables = $(selector || 'table.data-table, table.datatable, table[data-datatable]');
          $tables.each(function () {
            var $t = $(this);

            // เคย init แล้ว -> แค่ปรับคอลัมน์/วาดใหม่
            if ($.fn.DataTable.isDataTable(this)) {
              try { $t.DataTable().columns.adjust().draw(false); } catch (e) {}
              return;
            }

            // รวม option: ค่ากลาง + data-attr ที่ table + opts ที่ส่งมา
            var optFromData = $t.data('dt') || {};
            var finalOpts = $.extend(true, {}, window.DT_DEFAULTS, optFromData, (opts || {}));
            $t.DataTable(finalOpts);
          });
        };

        // init ครั้งแรกเมื่อ DOM พร้อม
        $(function () {
          /* ---------- Datepicker ---------- */
          $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'th'
          });

          /* ---------- DataTables (สำคัญ: ไม่มี init ซ้ำ per-page แล้ว) ---------- */
          initDataTables();

          /* ---*
