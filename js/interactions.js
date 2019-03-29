{
$(window)
	// trigger return on esc key press
	.on('keydown', function(e) {
		if(e.which === 27) {
			$('.return').click();
		}
	});

$(document)
	// show hidden sections
	.on('click', '.show-more', function() {
		$($(this).toggleClass('active').data('target')).slideToggle(300);
	})
	// download from madokami
	.on('click', '.madokami-file', function() {
		const t = $(this);
		download(t.data('id'), t.data('url'));
	});
}

function startLoading(element) {
	element.append($('#proto .loading-icon').clone());
	setTimeout(function() {
		element.addClass('loading');
	}, 1);
}

function stopLoading(element) {
	element.removeClass('loading');
	setTimeout(function() {
		element.not('.loading').find('.loading-icon').remove();
	}, 300);
}
