$(function () {

    $(document).on('change', "#customerId", function () {
        var customerId = $(this).val();
        if ($('#refundtransaction-daterange').val()) {
            var dateRange = $('#refundtransaction-daterange').val();
            customerPending(customerId, dateRange);
        } else {
            customerPending(customerId, "");
        }
    });

    $(document).on('change', "#refundtransaction-daterange", function () {
        var dateRange = $('#refundtransaction-daterange').val();
        if ($('#customerId').val()) {
            var customerId = $('#customerId').val();
            customerPending(customerId, dateRange);
        }
    });

    $('#supplierId').change(function (e) {
        var supplierId = $(this).val();
        if ($('input[name=dateRange2]').val()) {
            var dateRange2 = $('input[name=dateRange2]').val();
            supplierPending(supplierId, dateRange2);
        } else {
            supplierPending(supplierId, "");
        }
    });

    $('input[name=dateRange2]').change(function (e) {
        var dateRange2 = $('input[name=dateRange2]').val();
        if ($('#supplierId').val()) {
            var supplierId = $('#supplierId').val();
            supplierPending(supplierId, dateRange2);
        }
    });

    function customerPending(customerId, dateRange) {
        $.ajax({
            url: ajaxUrl,
            type: 'get',
            data: {
                customerId: customerId,
                dateRange: dateRange
            },
            dataType: 'json',
            success: function (data) {
                $('tbody#t-body').empty();
                $('tbody#t-body').append(data.html);
                $("#totalDue").html(data.totalDue);
            },
            error: function (e) {
                console.log(e);
            }
        });
    }

    function supplierPending(supplierId, dateRange) {
        $.ajax({
            url: ajaxUrl,
            type: 'get',
            data: {
                supplierId: supplierId,
                dateRange: dateRange
            },
            dataType: 'json',
            success: function (data) {
                console.log(data)

                $('tbody#t-body').empty();
                $('tbody#t-body').append(data.html);
            },
            error: function () {
                console.log('Error happend!');
            }
        });
    }

    $('#all-service').on("change", function () {
        if (this.checked) {
            $('.chk').prop('checked', true);
        } else {
            $('.chk').prop('checked', false);
        }
        calcSummery();
    });

    $('#all-supplier').on("change", function () {
        var sum = 0;
        if (this.checked) {
            $('.chk').prop('checked', true);

            $('.amount').each(function () {
                if (!isNaN(parseFloat($(this).val()))) {
                    sum += Math.abs(parseFloat($(this).val()));
                }
            });
            $('#refundtransaction-totalamount').val(sum.toFixed(2));
            $('#refundtransaction-adjustedamount').val(sum.toFixed(2));
            $('#refundtransaction-totalamount').attr('data-old', sum.toFixed(2));

        } else {
            $('.chk').prop('checked', false);
            $('#refundtransaction-totalamount').val(sum.toFixed(2))
            $('#refundtransaction-adjustedamount').val(sum.toFixed(2))
            $('#refundtransaction-totalamount').attr('data-old', sum.toFixed(2))

        }
    });

    $(document).on('change', ".chk", function () {
        calcSummery();
    });

    function calcSummery() {
        var amount = 0;
        var payable = 0;
        var receivable = 0;

        $(".chk").each(function () {
            if ($(this).is(":checked")) {
                payable += parseFloat($(this).parents('tr').find('.payable').val());
                receivable += parseFloat($(this).parents('tr').find('input.receivable').val());
            }
        });
        payable = Math.abs(payable);
        receivable = Math.abs(receivable);
        amount = Math.abs(receivable - payable);
        $('#refundtransaction-payableamount').val(payable.toFixed(2));
        $('#refundtransaction-receivableamount').val(receivable.toFixed(2));
        $('#refundtransaction-totalamount').val(amount.toFixed(2));
        $('#refundtransaction-adjustedamount').val(amount.toFixed(2));
    }


    $('.breakDown').on('change', function () {
        if ($(this).is(':checked') && ($(this).val() === 0)) {
            $('#comment').removeClass('hidden');
        } else {
            $('#comment').addClass('hidden');
        }
    });

    $('#refundtransaction-amount').on("change", function () {
        var total = parseFloat($('#refundtransaction-amount').attr('data-old'));
        var amnt = parseFloat($(this).val());
        if (amnt > total) {
            $(this).val(total)
        }
    });

});