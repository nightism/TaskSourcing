$('.taskLink').on('click', function () {
	$('#task_id').val($(this).attr('tid'))
	$('#viewTask').submit()
})