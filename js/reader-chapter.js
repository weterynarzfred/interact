function readerFit() {
	$('.reader-page').css({
		height	:	f3.h,
	});
}

window.addEventListener('afterScreenChange', function(event) {
	if(event.detail.currentView === 'reader_chapter') {
		readerFit();
		const view = $('.view-reader_chapter.current');
		const entryId = view.data('entry');
		const chapter = view.data('chapter');
		updateEntry(entryId, {last_read_chapter: chapter});
	}
});

window.addEventListener('layoutChange', function(event) {
	if(currentView === 'reader_chapter') {
		readerFit();
	}
});
