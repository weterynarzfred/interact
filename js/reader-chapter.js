const reader = new Reader();

function Reader() {
  this.pages = [];

  this.startChapter = function() {
    this.view = $('.view-reader_chapter.current');
    this.entryId = this.view.data('entry');
    this.chapter = this.view.data('chapter');
    this.view.find('.reader-page').map(function(i, page) {
      this.pages.push(new ReaderPage(page));
    }.bind(this));
    updateEntry(this.entryId, {last_read_chapter: this.chapter});

    window.addEventListener('layoutChange', this.resize);
    $document.on('click.reader-chapter', '.reader-page', function() {

    });
  };

  this.stopChapter = function() {
    this.chapter = undefined;
    this.pages = [];

    window.removeEventListener('layoutChange', this.resize);
    $document.off('.reader-chapter');
  };

  this.resize = function() {
    this.pages.map(function(page) {
      page.resize();
    });
  }.bind(this);
}

function ReaderPage(element) {
  this.el = $(element);
  this.offset = 0;
}
ReaderPage.prototype.resize = function() {
  this.el.css({
    height  :  f3.h,
  });
};
ReaderPage.prototype.onResize = function() {
  this.offset = this.el.offset().top;
};

window.addEventListener('afterScreenChange', function(event) {
  if(event.detail.previousView === 'reader_chapter') {
    reader.stopChapter();
  }
  if(event.detail.currentView === 'reader_chapter') {
    reader.startChapter();
  }
});
