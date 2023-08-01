let rowNum = 0;

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
        },
        error: function (error) {
            console.log(error)
        }
    });
}

function totalRow() {
    return $('#addButton').attr('data-row-number')
}

function remove(row) {
    $('#card' + row).remove()
    titleUpdate(row)
    rowNum--
    $('#addButton').attr('data-row-number', rowNum)
}

function titleUpdate(row) {
    while (($('#card-label-' + (row + 1)).length !== 0)) {
        $('#card-label-' + (row + 1)).text('Ticket ' + (row + 1))
        row++
    }
}

$('#customerId').on('change', function (e) {
    $('.customerId').val($(this).val());
});
$('#supplierId').on('change', function (e) {
    $('.supplierId').val($(this).val());
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
                $('.commission').val(data.commission);
                $('.incentive').val(data.incentive);
                $('.govTax').val(data.govTax);
                $('.airline').val($(this).val()).trigger('change');
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

$(document).on("change", ".calculateQuote", function (e) {
    const suffix = this.id.match(/\d+/);
    const baseFare = parseFloat($('#baseFare' + suffix).val());
    const tax = parseFloat($('#tax' + suffix).val());
    const otherTax = parseFloat($('#otherTax' + suffix).val());
    var discount = parseFloat($('#discount' + suffix).val());
    const serviceCharge = parseFloat($('#serviceCharge' + suffix).val());
    const discountType = $('#discountType' + suffix).val();

    if (discountType === 'Percentage') {
        discount = baseFare * (discount / 100);
    }
    const quoteAmount = (baseFare + tax + otherTax + serviceCharge - discount);
    $('#quoteAmount' + suffix).val(quoteAmount);
});

$('#airline0').on("change paste keyup", function () {
    $('.airline').val($(this).val()).trigger('change');
});

$('#ticket-0-basefare').on("change paste keyup", function () {
    $('.baseFare').val($(this).val());
})

$('#ticket-0-tax').on("change paste keyup", function () {
    $('.tax').val($(this).val());
})

$('#ticket-0-othertax').on("change paste keyup", function () {
    $('.otherTax').val($(this).val());
})

$('#issueDate0').on("change paste keyup", function () {
    $('.issueDate').val($(this).val());
});

$('#departureDate0').on("change paste keyup", function () {
    $('.departureDate').val($(this).val());
});

$('#ticket-0-paxname').on("change paste keyup", function () {
    $('.paxName').val($(this).val());
});

$('#ticket-0-paxtype').on("change paste keyup", function () {
    $('.paxType').val($(this).val());
});
$('#ticket-0-bookedonline').on("change paste keyup", function () {
    $('.bookedOnline').val($(this).val());
});
$('#ticket-0-routing').on("change paste keyup", function () {
    $('.routing').val($(this).val());
});

$('#providerId0').on("change paste keyup", function () {
    $('.providerId0').val($(this).val());
});
$('#ticket-0-numberofsegment').on("change paste keyup", function () {
    $('.numberOfSegment').val($(this).val());
});

$('#ticket-0-reference').on("change paste keyup", function () {
    $('.reference').val($(this).val());
});