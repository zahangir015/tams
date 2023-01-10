$('#createUser').hide();
$('#userCreate').click(function () {
    if ($('#userCreate').is(':checked')) {
        $('#createUser').show()
    }
    if (!$('#userCreate').is(':checked')) {
        $('#createUser').hide();
    }
});