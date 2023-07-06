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
                console.log(data.govTax);
                $('#commission' + suffix).val(data.commission);
                $('#incentive' + suffix).val(data.incentive);
                $('#govTax' + suffix).val(data.govTax);
                //$('#serviceCharge' + suffix).val(data.serviceCharge);
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
            console.log(data);
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

$(document).on("change", ".quoteCalculate", function (e) {
    var suffix = this.id.match(/\d+/);
    var baseFare = parseFloat($('#baseFare' + suffix).val());
    var tax = parseFloat($('#tax' + suffix).val());
    var otherTax = parseFloat($('#otherTax' + suffix).val());
    var discount = parseFloat($('#discount' + suffix).val());
    var serviceCharge = parseFloat($('#serviceCharge' + suffix).val());
    var quoteAmount = (baseFare + tax + otherTax + serviceCharge - discount);
    $('#quoteAmount' + suffix).val(quoteAmount);
});