/**
 * Created by Zahangir on 6/21/2017.
 */
$(function () {
    "use strict";

    $(document).on("change", '#leaveapplication-numberofdays', function () {
        let numberOfDays = $(this).val();
        if (parseFloat(numberOfDays) == 0.5) {
            $('#to').prop("readonly", true);
            $('#to').val($("#from").val());
            $('#leaveapplication-availablefrom').prop("readonly", false);
        } else if (parseFloat(numberOfDays) == 1) {
            $('#to').prop("readonly", true);
            $('#to').val($("#from").val());
            $('#leaveapplication-availablefrom').prop("readonly", true);
        } else {
            $('#to').prop("readonly", false);
            $('#leaveapplication-availablefrom').prop("readonly", true);
            $('#leaveapplication-availablefrom').val('');
        }
    });

    $(document).on("change", '#from', function () {
        $('#to').val($(this).val());
    });
});