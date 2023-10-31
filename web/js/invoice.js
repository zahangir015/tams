$(function () {
    $(document).on('change', "#customerId", function () {
        var customerId = $(this).val();
        if ($('input[name=dateRange]').val()) {
            var dateRange = $('input[name=dateRange]').val();
            pendingServices(customerId, dateRange);
        } else {
            pendingServices(customerId, "");
        }
    });

    $('input[name=dateRange]').change(function (e) {
        var dateRange = $('input[name=dateRange]').val();
        if ($('#customerId').val()) {
            var customerId = $('#customerId').val();
            pendingServices(customerId, dateRange);
        }
    });

    function pendingServices(customerId, dateRange) {
        $.ajax({
            url: ajaxUrl, type: 'get', data: {
                customerId: customerId, dateRange: dateRange
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
            $('#total').text(sum.toFixed(2));
            $('#invoiceAmount').val(sum.toFixed(2));
        } else {
            $('.chk').prop('checked', false);
            $('#amount').val(sum.toFixed(2));
            $('#total').text(sum.toFixed(2));
            $('#invoiceAmount').val(sum.toFixed(2));
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
        $('#amount').val(sum);
        $('#total').text(sum.toFixed(2));
        $('#invoiceAmount').val(sum.toFixed(2));
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

    var payAmount = 0;
    if ($("#transaction-amount").length) {
        payAmount = parseFloat($('#transaction-amount').val());
    }
    if ($("#groupinvoice-amount").length) {
        payAmount = parseFloat($('#groupinvoice-amount').val()) || 0;
    }

    $('#refundId').on('change', function () {
        let adjustmentAmount = calculateAdjustmentAmount();
        $('#transaction-paidamount').val(adjustmentAmount);
    });

    $('#advancePayment').on('change', function () {
        let adjustmentAmount = calculateAdvanceAdjustmentAmount();
        $('#transaction-paidamount').val(adjustmentAmount);
    });


    $('#invoice-discount').change(function (e) {
        var discount = parseFloat($(this).val());
        if (!isNaN(discount)) {
            var amount = parseFloat($('#invoice-due').val()) || 0;
            $('#transaction-amount').val(amount - discount);
        }
    });

    $('#clientId').change(function (e) {
        var clientId = $(this).val();
        groupAjaxCall(clientId);
    });

    function calculateAdjustmentAmount() {
        let adjustmentAmount = 0;
        let amounts = $("#refundId :selected").map(function (i, el) {
            return $(el).text().split(" | ")[1];
        }).get();
        for (let i = 0; i < amounts.length; i++) {
            adjustmentAmount += parseFloat(amounts[i]);
        }
        return adjustmentAmount;
    }
    function calculateAdvanceAdjustmentAmount() {
        let adjustmentAmount = 0;
        let amounts = $("#advancePayment :selected").map(function (i, el) {
            return $(el).text().split(" | ")[1];
        }).get();
        console.log(amounts);
        for (let i = 0; i < amounts.length; i++) {
            adjustmentAmount += parseFloat(amounts[i]);
        }
        return adjustmentAmount;
    }

    function groupAjaxCall(clientId) {
        $.ajax({
            url: groupInvoiceUrl, type: 'get', data: {
                clientId: clientId
            }, dataType: 'json', success: function (data) {
                $('tbody#t-body').append(data.html);
                $('#totalPayable').text(data.totalPayable);
                $('#refundId').children('option').remove();
                $('#refundId').append(data.refundList);
            }, error: function () {
                console.log('Error happend!');
            }
        });
    }

});