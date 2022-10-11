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
    let totalCostOfSale = parseFloat($('#holiday-costofsale').val());
    let totalQuoteAmount = parseFloat($('#holiday-quoteamount').val());
    $('#holiday-netprofit').val(totalQuoteAmount - totalCostOfSale);
}

function calculateQuoteAmount() {
    let totalQuoteAmount = 0;
    if ($('.quantity').length > 0) {
        $(".quantity").each(function (index) {
            var quantity = parseFloat($(this).parents('.calcData').find('.quantity').val());
            var perServicePrice = parseFloat($(this).parents('.calcData').find('.perServicePrice').val())
            var holidayQuoteAmount = quantity * perServicePrice;
            totalQuoteAmount += holidayQuoteAmount;
        });
        $('#holiday-quoteamount').val(totalQuoteAmount);
    } else {
        return false
    }
    calculateNetProfit();
}

function calculateCostOfSale() {
    let totalCostOfSale = 0;
    if ($(document).find('.supplierCostOfSale').length) {
        $(".supplierCostOfSale").each(function (index) {
            var costOfSale = parseFloat($(this).val());
            totalCostOfSale += costOfSale;
        });
        $('#holiday-costofsale').val(totalCostOfSale);
    } else {
        return false;
    }
    calculateNetProfit();
}
