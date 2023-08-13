$(function () {
    $(document).on('change', "#supplierId", function () {
        var supplierId = $(this).val();
        if ($('input[name=dateRange]').val()) {
            var dateRange = $('input[name=dateRange]').val();
            pendingServices(supplierId, dateRange);
        } else {
            pendingServices(supplierId, "");
        }
    });

    $('#bill-daterange').change(function (e) {
        var dateRange = $('#bill-daterange').val();
        if ($('#supplierId').val()) {
            var supplierId = $('#supplierId').val();
            pendingServices(supplierId, dateRange);
        }
    });

    function pendingServices(supplierId, dateRange) {
        $.ajax({
            url: ajaxUrl, type: 'get', data: {
                supplierId: supplierId, dateRange: dateRange
            }, dataType: 'json', success: function (data) {
                $("tbody#t-body").empty();
                $("#totalPayable").empty();
                $('tbody#t-body').append(data.html);
                $('#totalPayable').text(data.totalPayable);
                $('#grand-total').text(data.totalPayable);
            }, error: function (e) {
                alert(e.error())
            }
        });
    }

    $('#all').on("change", function () {
        var sum = 0;
        if (this.checked) {
            $('.chk').prop('checked', true);
            $('.amount').each(function () {
                if (!isNaN(parseFloat($(this).val()))) {
                    sum += parseFloat($(this).val());
                }
            });
            $('#amount').val(sum.toFixed(2));
            $('#total, #dueAmount').text(sum.toFixed(2));
            $('#dueAmount').val(sum.toFixed(2));
        } else {
            $('.chk').prop('checked', false);
            $('#amount').val(sum.toFixed(2));
            $('#total, #dueAmount').text(sum.toFixed(2));
            $('#dueAmount').val(sum.toFixed(2));
        }
    });
    
    function sumAllSelectedReceivingAmount() {
        var sum = 0;
        $(".amount").each(function () {
            if ($('#chk' + $(this).attr('id').match(/\d+/)).is(':checked')) {
                if (!isNaN(parseFloat($(this).val()))) {
                    sum += parseFloat($(this).val());
                }
            }
        });
        $('#amount').val(sum.toFixed(2));
        $('#total, #dueAmount').text(sum.toFixed(2));
        $('#dueAmount').val(sum.toFixed(2));
    }

    $(document).on('change', ".amount", function () {
        sumAllSelectedReceivingAmount();
    });

    $(document).on('change', ".chk", function () {
        if (this.checked) {
            sumAllSelectedReceivingAmount();
        } else {
            if ($('#all').is(':checked')) {
                $('#all').prop('checked', false);
            }
            sumAllSelectedReceivingAmount();
        }
    });

});