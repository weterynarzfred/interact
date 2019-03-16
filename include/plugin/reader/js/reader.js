{
$(document).on('mousedown', '.reader-page', function(e) {
  let target;
  if(e.which === 1) {
		let el = $(this).next();
		if(el.length > 0) target = el.offset().top;
	}
  else if(e.which === 3) {
		let el = $(this).prev();
    if(el.length > 0) target = el.offset().top;
	}
  if(target !== void 0) {
    $('html, body').stop().animate({scrollTop:target}, 200);
  }
});

$(document).on('keydown', function(e) {
	if(currentView === 'reader_manga') {
		if(e.which === 32 || e.which === 39 || e.which === 40) {
			e.preventDefault();
			const target = window.scrollY + $(window).height();
			$('html, body').stop().animate({scrollTop:target}, 200);
		}
		else if(e.which === 8 || e.which === 37 || e.which === 38) {
			e.preventDefault();
			const target = window.scrollY - $(window).height();
			$('html, body').stop().animate({scrollTop:target}, 200);
		}
	}
});

$(document).on('contextmenu', function(e) {
  e.preventDefault();
});

let pages;
let pagesArray;
let lastReadPage = 0;
let resetLastReadPage = false;

window.addEventListener('beforeNavigation', function(e) {
  if(pages !== void 0 && e.detail.name !== 'reader_manga') {
    pages = void 0;
		$(window).off('.reader');
  }
});

window.addEventListener('afterNavigation', function(e) {
  if(e.detail.name === 'reader_manga') {
    pages = $('.reader-page');
		pagesArray = pages.map(function() {
			return {
				el	:	$(this),
				offset	:	0,
			}
		});
    fitMangaPage();
		if(resetLastReadPage) {
			resetLastReadPage = false;
		}
		else {
			lastReadPage = $('.reader-manga-pages-container').data('last-read-page');
			if(lastReadPage === '') lastReadPage = 0;
			if(lastReadPage > 0) {
				$('html, body').animate({scrollTop: pagesArray[lastReadPage].offset}, 1000);
			}
		}

		$(window)
			.on('scroll.reader', throttle(1000, function() {
				let pageId = 0;
				for(let i = lastReadPage + 1; i < pagesArray.length; i++) {
					if(pagesArray[i].offset > window.scrollY + 10) {
						pageId = i - 1;
						break;
					}
				}
				if(pageId > lastReadPage) {
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
							data.fragments = data.fragments.filter(function(e) {
								return e.type !== 'message';
							});
							return data;
						},
				  });
				}
			}))
			.on('resize.reader', throttle(100, function() {
			    fitMangaPage();
			}));
  }
});

function fitMangaPage() {
  pages.css({height: $(window).height()});
	pagesArray.map(function() {
		this.offset = this.el.offset().top;
	});
}

// last read page
$(document).on('click', '.reader-manga-file', function() {
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
window.addEventListener('ajaxRequestDone', function(e) {
	if(!e.detail.reader_skip_refresh) {
		if(currentView === 'reader') {
			if(e.detail.type === 'update_entry') {
				if(e.detail.success) {
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
		}
	}
});

}
