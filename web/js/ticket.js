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
                $('#commission' + suffix).val(data.commission);
                $('#incentive' + suffix).val(data.incentive);
                $('#govtTax' + suffix).val(data.govtTax);
                $('#serviceCharge' + suffix).val(data.serviceCharge);
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