$('.taskLink').on('click', function () {
	$.get("task.php", {"task_id": $(this).attr('tid')})
})