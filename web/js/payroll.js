/**
 * Created by Zahangir on 6/21/2017.
 */
$(function () {
    "use strict";

    $(document).on("change keyup", '#employeepayroll-gross', function () {
        let gross = $(this).val();
    });

    $(document).on('change', "#employeeId", function (e) {
        let employeeId = $(this).val();
        $.ajax({
            url: payrollUrl,
            type: 'get',
            data: {employeeId: employeeId},
            dataType: 'json',
            success: function (data) {
                if (data) {
                    $('#gross').val(data.commission);
                    $('#tax').val(data.incentive);
                }
            }
        });
    });

});