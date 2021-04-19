$(function() {
    $('.form-control, .form-checkbox').on('input, change', function(x) {
        $('#saveDepartmentsBtn').addClass("pale-green-link");
        $('#undoDepartmentsBtn').show();
    })
    

});