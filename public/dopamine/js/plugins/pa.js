var ad = false;
$('.approve-draft').click(function() {
	if (ad) {
		return;
	}
	if ($('.spc:not(:checked)').length) {
		makeModal('Security', 'fa fa-ban', 'Clear all images', false, false);
		return true;
	}
	ad = true;
	$(this).attr('disabled', true).html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
	$.post("/product/approve/", "id=" + $(this).data('approve'), function() {
		$('.approve-draft').removeClass('btn-theme').addClass('btn-success').html('Approved');
	});
});