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
    let quantity = 0;
    let unitPrice = 0;
    $(".roomQuantity").each(function (index) {
        quantity += parseFloat($(this).parents('.calcData').find('.roomQuantity').val())
    });
    $(".totalNights").each(function (index) {
        totalNights += parseFloat($(this).parents('.calcData').find('.totalNights').val())
    });
    $(".unitPrice").each(function (index) {
        unitPrice += parseFloat($(this).parents('.calcData').find('.unitPrice').val())
    });
    $('#hotel-quoteamount').val((quantity * totalNights) * unitPrice);
    $('#hotel-totalnights').val((quantity * totalNights));

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
