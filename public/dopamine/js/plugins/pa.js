$('.approve-draft').click(function() {
	$(this).html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
	$.post("/product/approve/", "id=" + $(this).data('approve'), function() {
		$('.approve-draft').removeClass('btn-theme').addClass('btn-success').html('Approved');
	});
});