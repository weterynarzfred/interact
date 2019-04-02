function readerFit() {
	console.log('readierFit');
	$('.reader-page').css({
		height	:	f3.h,
	});
}

window.addEventListener('afterScreenChange', function(event) {
	if(event.detail.currentView === 'reader_chapter') {
		readerFit();
	}
});

window.addEventListener('layoutChange', function(event) {
	if(currentView === 'reader_chapter') {
		readerFit();
	}
});
