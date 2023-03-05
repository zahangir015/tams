/**
 * Created by Zahangir on 6/21/2017.
 */
$(function () {
    "use strict";

    $(document).on("change", '#leaveapplication-numberofdays', function () {
        let numberOfDays = $(this).val();
        if (for   numberOfDays == 0.5){
            $('#to').prop("readonly", true);
            $('#to').prop("readonly", false);
        }
        alert(numberOfDays);
    });
});