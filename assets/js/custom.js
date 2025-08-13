// Custom JavaScript for Asset Management System

$(document).ready(function() {
    
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Initialize popovers
    $('[data-toggle="popover"]').popover();
    
    // Sidebar toggle for mobile
    $('#sidebarToggle').click(function() {
        $('.sidebar').toggleClass('show');
    });
    
    // Close sidebar when clicking outside on mobile
    $(document).click(function(e) {
        if (!$(e.target).closest('.sidebar, #sidebarToggle').length) {
            $('.sidebar').removeClass('show');
        }
    });
    
    // Form validation
    $('.needs-validation').submit(function(e) {
        var form = this;
        if (form.checkValidity() === false) {
            e.preventDefault();
            e.stopPropagation();
        }
        $(form).addClass('was-validated');
    });
    
    // Confirm delete
    $('.btn-delete').click(function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var itemName = $(this).data('item') || 'รายการนี้';
        
        if (confirm('คุณแน่ใจหรือไม่ที่จะลบ ' + itemName + '?')) {
            window.location.href = url;
        }
    });
    
    // Auto-hide alerts
    $('.alert').delay(5000).fadeOut();
    
    // Loading spinner for forms
    $('form').submit(function() {
        var submitBtn = $(this).find('button[type="submit"]');
        var originalText = submitBtn.html();
        submitBtn.html('<span class="loading-spinner"></span> กำลังดำเนินการ...');
        submitBtn.prop('disabled', true);
        
        // Re-enable button after 10 seconds (fallback)
        setTimeout(function() {
            submitBtn.html(originalText);
            submitBtn.prop('disabled', false);
        }, 10000);
    });
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.searchable-table tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Date picker initialization
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true,
            language: 'th'
        });
    }
    
    // Number formatting
    $('.currency').each(function() {
        var value = parseFloat($(this).text());
        if (!isNaN(value)) {
            $(this).text(value.toLocaleString('th-TH', {
                style: 'currency',
                currency: 'THB'
            }));
        }
    });
    
    // Auto-calculate depreciation
    $('#purchase_price, #depreciation_rate').on('input', function() {
        var price = parseFloat($('#purchase_price').val()) || 0;
        var rate = parseFloat($('#depreciation_rate').val()) || 0;
        var depreciation = price * (rate / 100);
        $('#calculated_depreciation').text(depreciation.toLocaleString('th-TH', {
            style: 'currency',
            currency: 'THB'
        }));
    });
    
    // Print functionality
    $('.btn-print').click(function() {
        window.print();
    });
    
    // Export to Excel (if needed)
    $('.btn-export-excel').click(function() {
        var table = $(this).data('table');
        if (table) {
            var wb = XLSX.utils.table_to_book(document.getElementById(table));
            XLSX.writeFile(wb, 'export.xlsx');
        }
    });
    
    // Asset status color coding
    $('.asset-status').each(function() {
        var status = $(this).text().trim();
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
    
    // Repair status color coding
    $('.repair-status').each(function() {
        var status = $(this).text().trim();
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
    
    // Dynamic form fields
    $('.add-field').click(function() {
        var template = $(this).data('template');
        var container = $(this).data('container');
        if (template && container) {
            $(container).append($(template).html());
        }
    });
    
    $('.remove-field').on('click', function() {
        $(this).closest('.form-group').remove();
    });
    
    // AJAX form submission
    $('.ajax-form').submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        var method = form.attr('method') || 'POST';
        var data = form.serialize();
        
        $.ajax({
            url: url,
            method: method,
            data: data,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message || 'บันทึกข้อมูลเรียบร้อยแล้ว');
                    if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 1500);
                    }
                } else {
                    showAlert('danger', response.message || 'เกิดข้อผิดพลาด');
                }
            },
            error: function() {
                showAlert('danger', 'เกิดข้อผิดพลาดในการเชื่อมต่อ');
            }
        });
    });
    
    // Show alert function
    function showAlert(type, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                       message +
                       '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                       '<span aria-hidden="true">&times;</span>' +
                       '</button>' +
                       '</div>';
        $('#alerts-container').html(alertHtml);
        $('.alert').delay(5000).fadeOut();
    }
    
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

function calculateDepreciation(purchasePrice, depreciationRate, years) {
    return purchasePrice * (depreciationRate / 100) * years;
}

function validateForm(formId) {
    var form = document.getElementById(formId);
    if (form.checkValidity() === false) {
        form.classList.add('was-validated');
        return false;
    }
    return true;
}

