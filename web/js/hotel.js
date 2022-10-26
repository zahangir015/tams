let rowNum = 0;

function addSupplier() {
    rowNum++;
    $.ajax({
        url: supplier,
        type: 'get',
        data: {
            row: rowNum,
        },
        success: function (data) {
            $('.card-holder').append(data)
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
        $('#card-label-' + (row + 1)).text('Hotel Supplier ' + (row + 1))
        row++
    }
}

$('#customerId').on('change', function (e) {
    $('.customerId').val($(this).val());
});
$('#supplierId').on('change', function (e) {
    $('.supplierId').val($(this).val());
});

function updateSummery() {
    calculateQuoteAmount();
    calculateCostOfSale();
    calculateNetProfit();
}

function calculateNetProfit() {
    let totalCostOfSale = parseFloat($('#hotel-costofsale').val());
    let totalQuoteAmount = parseFloat($('#hotel-quoteamount').val());
    $('#hotel-netprofit').val(totalQuoteAmount - totalCostOfSale);
}

function calculateQuoteAmount() {
    let totalNights = 0;
    let quote = 0;
    $(".nights").each(function () {
        var numberOfNights = parseFloat($('#nights' + $(this).attr('id').match(/\d+/)).val());
        var rooms = parseFloat($('#roomQuantity' + $(this).attr('id').match(/\d+/)).val());
        var unitPrice = parseFloat($('#unitPrice' + $(this).attr('id').match(/\d+/)).val());
        if (!isNaN(numberOfNights) && !isNaN(rooms) && !isNaN(unitPrice)) {
            var nights = parseFloat(numberOfNights * rooms);
            quote += (nights * unitPrice);
            totalNights += nights;
        }
    });
    $('#hotel-quoteamount').val(quote);
    $('#hotel-totalnights').val((totalNights));

    calculateNetProfit();
}

function calculateCostOfSale() {
    let totalCostOfSale = 0;
    if ($(document).find('.costOfSale').length) {
        $(".costOfSale").each(function (index) {
            var costOfSale = parseFloat($(this).val());
            totalCostOfSale += costOfSale;
        });
        $('#hotel-costofsale').val(totalCostOfSale);
    } else {
        return false;
    }
    calculateNetProfit();
}
