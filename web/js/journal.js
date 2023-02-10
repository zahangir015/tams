/**
 * Created by Zahangir on 6/21/2017.
 */
$(function () {
    "use strict";

    $(document).on("change paste keyup", '.refModel', function () {
        let row = $(this).attr('id').match(/\d+/);
        $('#refId' + row).prop('disabled', false);
    });

    $(document).on("change paste keyup", '.refId', function () {
        let row = $(this).attr('id').match(/\d+/);
        $('#refId' + row).prop('disabled', false);
    });

    $(document).on("change paste keyup", '.debit, .credit', function () {
        var debitSum = 0;
        var creditSum = 0;

        $('.debit').each(function () {
            if (!isNaN($(this).val())) {
                debitSum += parseFloat($(this).val());
            }
        });

        $('.credit').each(function () {
            if (!isNaN($(this).val())) {
                console.log($(this).val());
                creditSum += parseFloat($(this).val());
            }
        });

        $('#journal-debit').val(debitSum);
        $('#journal-credit').val(creditSum);
        $('#journal-outofbalance').val(parseFloat((debitSum - creditSum)));
    });
});