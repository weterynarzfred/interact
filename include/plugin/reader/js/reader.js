{
$(document).on('mousedown', '.reader-page', function(e) {
  let target;
  if(e.which === 1)
    target = $(this).next().offset().top;
  else if(e.which === 3)
    target = $(this).prev().offset().top;
  if(target !== void 0) {
    $('html, body').stop().animate({scrollTop:target}, 200);
  }
});
$(document).on('contextmenu', function(e) {
  e.preventDefault();
});

let pages;

window.addEventListener('beforeNavigation', function(e) {
  if(pages !== void 0 && e.detail.name !== 'reader_manga') {
    pages = void 0;
  }
});

window.addEventListener('afterNavigation', function(e) {
  if(e.detail.name === 'reader_manga') {
    pages = $('.reader-page');
    fitMangaPage();
  }
});

$(window).on('resize', throttle(100, function() {
  if(pages) {
    fitMangaPage();
  }
}));

function fitMangaPage() {
  pages.css({height: $(window).height()});
}
}
