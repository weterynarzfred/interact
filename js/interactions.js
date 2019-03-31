{
$(window)
	// trigger return on esc key press
	.on('keydown', function(e) {
		if(e.which === 27) {
			$('.return').click();
		}
	});

$(document)
	// stop propagation on buttons
	.on('click', '.button', function(event) {
		event.stopPropagation();
	})
	// show hidden sections
	.on('click', '.show-more', function() {
		$($(this).toggleClass('active').data('target')).slideToggle(300);
	})
	// download from madokami
	.on('click', '.madokami-file', function() {
		const t = $(this);
		download(t.data('id'), t.data('url'), t.data('file-slug'));
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


// refresh edit form after adding a new entry
window.addEventListener('afterFormSubmit_update_entry', function(event) {
	if(event.detail.values.id == '') {
		const id = event.detail.data.lastInsertedId;
		getView('edit_entry', '.view-edit_entry', {entry: id});
	}
});


window.addEventListener('afterLayoutChange', function() {
	jQuery("time.timeago").timeago();
});
