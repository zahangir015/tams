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
    return $('#addButton').attr('data-row-number');
}

function remove(row) {
    $('#card' + row).remove();
    titleUpdate(row);
    rowNum--;
    $('#addButton').attr('data-row-number', rowNum);
}

function titleUpdate(row) {
    while (($('#card-label-' + (row + 1)).length !== 0)) {
        $('#card-label-' + (row + 1)).text('Visa Supplier ' + (row + 1));
        row++;
    }
}

$('#customerId').on('change', function (e) {
    $('.customerId').val($(this).val());
});
$('#supplierId').on('change', function (e) {
    $('.supplierId').val($(this).val());
});

function calculateNetProfit() {
    let totalCostOfSale = parseFloat($('#visa-costofsale').val());
    let totalQuoteAmount = parseFloat($('#visa-quoteamount').val());
    $('#visa-netprofit').val(totalQuoteAmount - totalCostOfSale);
}

function calculateQuoteAmount() {
    let totalQuoteAmount = 0;
    let totalQuantity = 0;
    $(".quantity").each(function (index) {
        var quantity = parseFloat($(this).parents('.calcData').find('.quantity').val());
        var perServicePrice = parseFloat($(this).parents('.calcData').find('.unitPrice').val());
        if (!isNaN(quantity) && !isNaN(perServicePrice)) {
            totalQuoteAmount += parseFloat(quantity * perServicePrice);
            totalQuantity += quantity;
        }
    });
    console.log(totalQuoteAmount);
    console.log(totalQuantity);
    $('#visa-quoteamount').val(totalQuoteAmount);
    $('#visa-quantity').val(totalQuantity);
    calculateNetProfit();
}

function calculateCostOfSale() {
    let totalCostOfSale = 0;
    $(".costOfSale").each(function (index) {
        var costOfSale = parseFloat($(this).val());
        totalCostOfSale += costOfSale;
    });
    $('#visa-costofsale').val(totalCostOfSale);
    calculateNetProfit();
}
