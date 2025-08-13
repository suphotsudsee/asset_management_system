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
    
    <!-- Custom JS -->
    <script src="<?php echo base_url('assets/js/custom.js'); ?>"></script>
    
    <!-- Page specific scripts -->
    <?php if (isset($page_scripts) && is_array($page_scripts)): ?>
        <?php foreach ($page_scripts as $script): ?>
            <script src="<?php echo base_url($script); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Inline scripts -->
    <?php if (isset($inline_scripts)): ?>
        <script>
            <?php echo $inline_scripts; ?>
        </script>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            // Initialize date pickers
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                language: 'th'
            });
            
            // Confirm delete actions
            $('.btn-delete').on('click', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                var itemName = $(this).data('item') || 'รายการนี้';
                
                if (confirm('คุณแน่ใจหรือไม่ที่จะลบ ' + itemName + '?')) {
                    window.location.href = url;
                }
            });
            
            // Auto-hide alerts after 5 seconds
            $('.alert').delay(5000).fadeOut();
            
            // Format currency fields
            $('.currency').each(function() {
                var value = parseFloat($(this).text());
                if (!isNaN(value)) {
                    $(this).text(value.toLocaleString('th-TH', {
                        style: 'currency',
                        currency: 'THB'
                    }));
                }
            });
            
            // Format number fields
            $('.number').each(function() {
                var value = parseFloat($(this).text());
                if (!isNaN(value)) {
                    $(this).text(value.toLocaleString('th-TH'));
                }
            });
            
            // Status badges
            $('.asset-status').each(function() {
                var status = $(this).text().trim();
                $(this).removeClass('badge-secondary badge-success badge-danger badge-warning badge-info');
                
                switch(status) {
                    case 'ใช้งาน':
                        $(this).addClass('badge badge-success');
                        break;
                    case 'ชำรุด':
                        $(this).addClass('badge badge-danger');
                        break;
                    case 'จำหน่ายแล้ว':
                        $(this).addClass('badge badge-secondary');
                        break;
                    case 'ซ่อมแซม':
                        $(this).addClass('badge badge-warning');
                        break;
                    default:
                        $(this).addClass('badge badge-info');
                }
            });
            
            // Repair status badges
            $('.repair-status').each(function() {
                var status = $(this).text().trim();
                $(this).removeClass('badge-secondary badge-success badge-danger badge-warning badge-info');
                
                switch(status) {
                    case 'รอดำเนินการ':
                        $(this).addClass('badge badge-warning');
                        break;
                    case 'กำลังซ่อม':
                        $(this).addClass('badge badge-info');
                        break;
                    case 'ซ่อมเสร็จแล้ว':
                        $(this).addClass('badge badge-success');
                        break;
                    case 'ไม่สามารถซ่อมได้':
                        $(this).addClass('badge badge-danger');
                        break;
                    default:
                        $(this).addClass('badge badge-secondary');
                }
            });
            
            // Print button
            $('.btn-print').on('click', function() {
                window.print();
            });
            
            // Search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.searchable-table tbody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
        
        // Utility functions
        function formatCurrency(amount) {
            return parseFloat(amount).toLocaleString('th-TH', {
                style: 'currency',
                currency: 'THB'
            });
        }
        
        function formatDate(dateString) {
            var date = new Date(dateString);
            return date.toLocaleDateString('th-TH', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
        
        function showAlert(type, message) {
            var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                           '<i class="fas fa-' + getAlertIcon(type) + '"></i> ' +
                           message +
                           '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                           '<span aria-hidden="true">&times;</span>' +
                           '</button>' +
                           '</div>';
            $('#alerts-container').html(alertHtml);
            $('.alert').delay(5000).fadeOut();
        }
        
        function getAlertIcon(type) {
            switch(type) {
                case 'success': return 'check-circle';
                case 'danger': return 'exclamation-circle';
                case 'warning': return 'exclamation-triangle';
                case 'info': return 'info-circle';
                default: return 'info-circle';
            }
        }
    </script>
</body>
</html>

