$(function () {
    $(document).on('change', '.quotePart', function () {
        var baseFare = parseFloat($('#ticket-basefare').val());
        var tax = parseFloat($('#ticket-tax').val());
        var otherTax = parseFloat($('#ticket-othertax').val());
        var airlineId = parseInt($('#ticket-airlineid').val());

        calculateCostOfSale(baseFare, tax, otherTax, airlineId)
    });

    $(document).on('change', '.serviceCharge', function () {
        var quoteAmount;
        var serviceCharge = parseFloat($('#ticketrefund-refundcharge').val());
        var costOfSale = parseFloat($('#ticket-costofsale').val());
        quoteAmount = costOfSale + serviceCharge;
        $('#ticket-quoteamount').val(quoteAmount);
    });

    function calculateCostOfSale(baseFare, tax, otherTax, airlineId) {
        $.ajax({
            url: calculateCost,
            type: 'get',
            data: {
                baseFare: baseFare,
                tax: tax,
                otherTax: otherTax,
                airlineId: airlineId
            },
            success: function (cost) {
                var airlineCharge = parseFloat($('#ticketrefund-airlinerefundcharge').val());
                var supplierCharge = parseFloat($('#ticketrefund-supplierrefundcharge').val());
                var costOfSale = parseFloat(cost)
                costOfSale += parseFloat(airlineCharge + supplierCharge)
                $('#ticket-costofsale').val(costOfSale);
                var quoteAmount;
                var serviceCharge = parseFloat($('#ticketrefund-refundcharge').val());
                quoteAmount = parseFloat(costOfSale + serviceCharge);
                $('#ticket-quoteamount').val(quoteAmount);
            },
            error: function (error) {
                console.log(error)
            }
        })
    }
});
