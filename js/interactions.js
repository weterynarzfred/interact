{
$(window)
	// trigger return on esc key press
	.on('keydown', function(e) {
		if(e.which === 27) {
			$('.return-button').click();
		}
	});

$(document)
	// show hidden sections
	.on('click', '.show-more', function() {
		$($(this).toggleClass('active').data('target')).slideToggle(300);
	});
}
