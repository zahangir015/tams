$(function () {
    $(document).on('change', '.quotePart', function () {
        var costOfSale;
        var airlineCharge = parseFloat($('#ticketrefund-airlinerefundcharge').val());
        var supplierCharge = parseFloat($('#ticketrefund-supplierrefundcharge').val());
        costOfSale = airlineCharge + supplierCharge;
        $('#ticket-costofsale').val(costOfSale);

        var quoteAmount;
        var serviceCharge = parseFloat($('#ticketrefund-refundcharge').val());
        quoteAmount = costOfSale + serviceCharge;
        $('#ticket-quoteamount').val(quoteAmount);
    });

    $(document).on('change', '.serviceCharge', function () {
        var quoteAmount;
        var serviceCharge = parseFloat($('#ticketrefund-refundcharge').val());
        var costOfSale = parseFloat($('#ticket-costofsale').val());
        quoteAmount = costOfSale + serviceCharge;
        $('#ticket-quoteamount').val(quoteAmount);
    });
});
