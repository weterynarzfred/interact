// reader_manga.php
{

	let pages = [];
	let lastReadPage = 0;
	let resetLastReadPage = false;
	let isEnabled = false;
	let isAutoScrolling = false;

	window.addEventListener('afterNavigation', function() {
	  if(!isEnabled && currentView === 'reader_manga') {
			start();
		}
	});
	window.addEventListener('beforeNavigation', function() {
		if(isEnabled && currentView !== 'reader_manga') {
			stop();
		}
	});

	function start() {
    pages = $('.reader-page').map(function() {
			return {
				el	:	$(this),
				offset	:	0,
			}
		});
    fitMangaPage();
		if(!resetLastReadPage) {
			lastReadPage = $('.reader-manga-pages-container').data('last-read-page');
			if(lastReadPage === '') {
				lastReadPage = 0;
			}
			else if(lastReadPage > 0) {
				isAutoScrolling = true;
				saveLastReadPage(lastReadPage);
				$('html, body').animate(
					{scrollTop: pages[lastReadPage].offset},
					1000,
					function() {isAutoScrolling = false;},
				);
			}
		}
		resetLastReadPage = false;

		$(window)
			.on('scroll.reader_manga', throttle(1000, function() {
				if(!isAutoScrolling) {
					saveLastReadPage();
				}
			}))
			.on('resize.reader_manga', throttle(100, fitMangaPage))
			.on('contextmenu.reader_manga', function(e) {
			  e.preventDefault();
			});

		$(document).on('mousedown.reader_manga', '.reader-page', function(e) {
		  if(e.which === 1) {
				ScrollToAdjacent(1, $(this).data('id'));
			}
		  else if(e.which === 3) {
				ScrollToAdjacent(-1, $(this).data('id'));
			}
		})
		.on('keydown.reader_manga', function(e) {
			if(currentView === 'reader_manga') {
				if(e.which === 32 || e.which === 39 || e.which === 40) {
					e.preventDefault();
					ScrollToAdjacent(1);
				}
				else if(e.which === 8 || e.which === 37 || e.which === 38) {
					e.preventDefault();
					ScrollToAdjacent(-1);
				}
			}
		})
		isEnabled = true;
	}

	function stop(event) {
		saveLastReadPage();
		$(window).off('.reader_manga');
		$(document).off('.reader_manga');
    pages = [];
		isEnabled = false;
	}

	function saveLastReadPage(pageId) {
		if(pageId === void 0) {
			for(let i = 0; i < pages.length; i++) {
				if(pages[i].offset > window.scrollY + 10) {
					pageId = i - 1;
					break;
				}
			}
		}
		if(pageId !== lastReadPage) {
			lastReadPage = pageId;
			doQuery({
				data  : {
					action	:	'update_entry',
					values	:	{
						id	:	$('.reader-manga-pages-container').data('id'),
						last_read_page	:	pageId,
					},
				},
				filter	:	function(data) {
					data.fragments = data.fragments.filter(function(fragment) {
						return fragment.type !== 'message';
					});
					return data;
				},
			});
		}
	}

	function fitMangaPage() {
		const windowHeight = $(window).height();
	  pages.map(function() {
			this.el.css({height: windowHeight});
			this.offset = this.el.offset().top;
		});
	}

	function ScrollToAdjacent(offset, basePage) {
		if(basePage === void 0) {
			basePage = lastReadPage;
		}
		basePage += offset;
		if(basePage >= pages.length || basePage < 0) {
			return;
		}
		isAutoScrolling = true;
		$('html').stop().animate(
			{scrollTop: pages[basePage].offset},
			200,
			function() {isAutoScrolling = false;},
		);
		saveLastReadPage(basePage);
	}
}

// reader.php
{

	let isEnabled = false;

	window.addEventListener('afterNavigation', function() {
	  if(!isEnabled && currentView === 'reader') {
			start();
		}
	});
	window.addEventListener('beforeNavigation', function() {
		if(isEnabled && currentView !== 'reader') {
			stop();
		}
	});

	function start() {
		// refresh last read page
		$(document).on('click.reader', '.reader-manga-file', function() {
			const t = $(this);
			let text = t.text();
			text = text.replace(/(^\s*|\s*$)/g,'');
			const isLastRead = t.find('.reader-manga-file-last-read').length > 0;
			if(!isLastRead) {
				let args = {
					data  : {
						action	:	'update_entry',
						values	:	{
							id	:	t.parents('.reader-manga-file-list').data('id'),
							last_read_file	:	text,
							last_read_page	:	0.
						},
					},
					filter	:	function(data) {
						data.reader_skip_refresh = true;
						return data;
					}
				};
				resetLastReadPage = true;
				doQuery(args);
			}
		});

		// refresah view on read progress update
		window.addEventListener('ajaxRequestDone', refreshFileList);

		isEnabled = true;
	}

	function stop() {
		$(document).off('.reader');
		window.removeEventListener('ajaxRequestDone', refreshFileList);
		isEnabled = false;
	}

	function refreshFileList(event) {
		if(
			event.detail.reader_skip_refresh
			|| currentView !== 'reader'
			|| event.detail.type !== 'update_entry'
			|| !event.detail.success
		) {
			return;
		}
		doQuery({
			data	: {
				action	:	'display_view',
				values	:	{
					name	:	'reader',
					value	:	$('.reader-manga-file-list').data('id'),
				},
			}
		});
	}

}
