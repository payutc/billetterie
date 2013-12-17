$(function () {
	$('.btn-confirm').on('click', function (e, confirmed) {
        if (!confirmed) {
            e.preventDefault();
            $($(this).data('modal')).modal();
        }
        return true;
    });
    $('.confirmation-modal-submit').on('click', function (e) {
        $('.btn-confirm').trigger('click', true);
    });
    $('.datetimepicker').datetimepicker();
    // $('.datepicker').datetimepicker({
    //     pickTime: false
    // });
    // $('.timepicker').datetimepicker({
    //     pickDate: false
    // });
});