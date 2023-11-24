let rowNum = 0;

$('#count').text((rowNum + 1));

function addTicket() {
    rowNum++;
    $.ajax({
        url: ticket,
        type: 'get',
        data: {
            row: rowNum,
        },
        success: function (data) {
            $('.card-holder').append(data);
            $('.customerId').val($('.customerId').val());
            $('#count').text((rowNum + 1));
            $
        },
        error: function (error) {
            console.log(error)
        }
    });
}

function remove(row) {
    $('#card' + row).remove();
    titleUpdate(row);

    rowNum--;
    $('#addButton').attr('data-row-number', rowNum);
    $('#count').text((rowNum + 1));
}

function titleUpdate(row) {
    while (($('#card-label-' + (row + 1)).length !== 0)) {
        $('#card-label-' + (row + 1)).text('Ticket ' + (row + 1));
        row++;
    }
}

function calculateQuoteAmount() {
    $('.quoteAmount').each(function (index) {
        let suffix = this.id.match(/\d+/);
        const baseFare = parseFloat($('#baseFare' + suffix).val());
        const tax = parseFloat($('#tax' + suffix).val());
        const otherTax = parseFloat($('#otherTax' + suffix).val());
        let discount = parseFloat($('#discount' + suffix).val());
        const serviceCharge = parseFloat($('#serviceCharge' + suffix).val());
        const discountType = $('#discountType' + suffix).val();
        const govTax = $('#govTax' + suffix).val();
        const ait = ((baseFare + tax) * govTax);

        if (discountType === 'Percentage') {
            discount = baseFare * (discount / 100);
        }
        $('#quoteAmount' + suffix).val((baseFare + tax + otherTax + serviceCharge + ait - discount));
    })
}

$('#customerId').on('change', function (e) {
    $('.customerId').val($(this).val());
});

$('#supplierId0').on('select2:select', function () {
    $('.supplier').val($(this).val()).trigger('change.select2');
});

$(document).on('change', "#airlineId0", function (e) {
    $.ajax({
        url: airlineUrl,
        type: 'get',
        data: {airlineId: $(this).val()},
        dataType: 'json',
        success: function (data) {
            if (data) {
                $('.commission').val(data.commission / 100);
                $('.incentive').val(data.incentive / 100);
                $('.govTax').val(data.govTax);
                //$('.airline').val($(this).val()).trigger('change');
                $(document).set
                $('#airline1').removeClass('select2-offscreen').val($(this).val()).trigger('change.select2');
            }
        }
    });
});

$(document).on('change', ".airline", function (e) {
    var suffix = this.id.match(/\d+/);
    $.ajax({
        url: airlineUrl,
        type: 'get',
        data: {airlineId: $(this).val()},
        dataType: 'json',
        success: function (data) {
            if (data) {
                $('#commission' + suffix).val(data.commission / 100);
                $('#incentive' + suffix).val(data.incentive / 100);
                $('#govTax' + suffix).val(data.govTax);
            }
        }
    });
});

$(document).on('change', ".motherTicket", function (e) {
    var suffix = this.id.match(/\d+/);
    $.ajax({
        url: parentTicketUrl,
        type: 'get',
        data: {motherTicketId: $(this).val()},
        dataType: 'json',
        success: function (data) {
            if (data) {
                $('#paxName' + suffix).val(data.paxName);
                $('#paxType' + suffix).val(data.paxType);
                $('#route' + suffix).val(data.route);
                $('#flightType' + suffix).val(data.flightType);
                $('#tripType' + suffix).val(data.tripType);
            }
        }
    });
});

$(document).on('change', ".type", function (e) {
    var suffix = this.id.match(/\d+/);
    if (this.value === 'Reissue' || this.value === 'Refund') {
        $('#motherTicketId' + suffix).prop("disabled", false)
    } else {
        $('#motherTicketId' + suffix).prop("disabled", true)
    }
});

$(document).on("change paste keyup", ".calculateQuote", function (e) {
    const suffix = this.id.match(/\d+/);
    const baseFare = parseFloat($('#baseFare' + suffix).val());
    const tax = parseFloat($('#tax' + suffix).val());
    const otherTax = parseFloat($('#otherTax' + suffix).val());
    let discount = parseFloat($('#discount' + suffix).val());
    const serviceCharge = parseFloat($('#serviceCharge' + suffix).val());
    const discountType = $('#discountType' + suffix).val();

    if (discountType === 'Percentage') {
        discount = baseFare * (discount / 100);
    }
    $('#quoteAmount' + suffix).val((baseFare + tax + otherTax + serviceCharge - discount));
});

$('#eTicket0').on("change paste keyup", function () {
    var number;
    var zero;
    if (!isNaN(parseInt($(this).val()))) {
        number = $(this).val();
        zero = number.startsWith("0");
    }

    $('.eTicket').each(function (index) {
        if ((index != 0)) {
            if (zero) {
                $(this).val("0" + number);
            } else {
                $(this).val(number);
            }
        }

        number = (parseInt(number) + 1);
    });
});

$('#baseFare0').on("change paste keyup", function () {
    $('.baseFare').val($(this).val());
    calculateQuoteAmount();
})

$('#tax0').on("change paste keyup", function () {
    $('.tax').val($(this).val());
    calculateQuoteAmount();
})

$('#otherTax0').on("change paste keyup", function () {
    $('.otherTax').val($(this).val());
    calculateQuoteAmount();
})

$('#serviceCharge0').on("change paste keyup", function () {
    $('.serviceCharge').val($(this).val());
    calculateQuoteAmount();
})

$('#discount0').on("change paste keyup", function () {
    $('.discount').val($(this).val());
    calculateQuoteAmount();
})

$('#discountType0').on("change paste keyup", function () {
    $('.discountType').val($(this).val());
    calculateQuoteAmount();
})

$('#issueDate0').on("change paste keyup", function () {
    $('.issueDate').val($(this).val());
});

$('#departureDate0').on("change paste keyup", function () {
    $('.departureDate').val($(this).val());
});

$('#paxType0').on("change paste keyup", function () {
    $('.paxType').val($(this).val());
});
$('#bookedOnline0').on("change paste keyup", function () {
    $('.bookedOnline').val($(this).val());
});
$('#route0').on("change paste keyup", function () {
    $('.route').val($(this).val());
});

$('#providerId0').on("change paste keyup", function () {
    $('.providerId').val($(this).val());
});

$('#type0').on("change paste keyup", function () {
    $('.type').val($(this).val());
});

$('#numberOfSegment0').on("change paste keyup", function () {
    $('.numberOfSegment').val($(this).val());
});

$('#pnrCode0').on("change paste keyup", function () {
    $('.pnrCode').val($(this).val());
});

$('#reference0').on("change paste keyup", function () {
    $('.reference').val($(this).val());
});

$('#referenceCommission0').on("change paste keyup", function () {
    $('.referenceCommission').val($(this).val());
});

$('#flightType0').on("change paste keyup", function () {
    $('.flightType').val($(this).val());
});

$('#seatClass0').on("change paste keyup", function () {
    $('.seatClass').val($(this).val());
});

$('#tripType0').on("change paste keyup", function () {
    $('.tripType').val($(this).val());
});

$('#baggage0').on("change paste keyup", function () {
    $('.baggage').val($(this).val());
});

