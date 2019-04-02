// refresh edit form after adding a new entry
window.addEventListener('afterFormSubmit_update_entry', function(event) {
	if(event.detail.values.id == '') {
		const id = event.detail.data.lastInsertedId;
		getView('edit_entry', '.view-edit_entry', {entry: id});
	}
});

// convert dates to time ago
window.addEventListener('afterLayoutChange', function() {
	$('time.timeago').timeago();
});

// scroll window to top after screen change
window.addEventListener('afterScreenChange', function() {
	$('html').animate({scrollTop: 0}, 500);
});
