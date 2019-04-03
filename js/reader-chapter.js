const reader = new Reader();

function Reader() {
  this.pages = [];
  this.isScrolling = false;

  this.startChapter = function() {
    this.view = $('.view-reader_chapter.current');
    this.entryId = this.view.data('entry');
    this.chapter = this.view.data('chapter');
    this.view.find('.reader-page').map(function(index, page) {
      const readerPage = new ReaderPage(page, index, this);
      this.pages.push(readerPage);
    }.bind(this));
    this.currentPage = this.view.data('last-read-page');
    if(this.currentPage === undefined) this.currentPage = 0;
    else this.showPage(this.currentPage);

    updateEntry(this.entryId, {last_read_chapter: this.chapter});
    window.addEventListener('layoutChange', this.resize);
    $window.on('keydown.reader-chapter', function(event) {
      if(event.which === 40 || event.which === 39 || event.which === 32) {
        event.preventDefault();
        this.showPage(this.currentPage + 1);
      }
      else if(event.which === 38 || event.which === 37 || event.which === 8) {
        event.preventDefault();
        this.showPage(this.currentPage - 1);
      }
    }.bind(this));
    $window.on('contextmenu.reader-chapter', function(event) {
      event.preventDefault();
      return false;
    });
    $window.on('scroll.reader-chapter', function() {
      if(this.isScrolling) return;
      for(const readerPage of this.pages) {
        if(readerPage.offset < window.scrollY + 10) {
          this.currentPage = readerPage.index;
        }
      }
    }.bind(this));
  };

  this.stopChapter = function() {
    this.chapter = undefined;
    this.pages = [];

    window.removeEventListener('layoutChange', this.resize);
    $document.off('.reader-chapter');
    $window.off('.reader-chapter');
  };

  this.resize = function() {
    this.pages.map(function(page) {
      page.resize();
    });
    this.pages.map(function(page) {
      page.onResize();
    });
  }.bind(this);

  this.showPage = function(index) {
    if(this.pages[index] !== undefined) {
      this.isScrolling = true;
      this.currentPage = index;
      const target = this.pages[index].offset;
      $html.stop().animate({scrollTop: target}, 200, function() {
        this.isScrolling = false;
      }.bind(this));
    }
  };
}

function ReaderPage(element, index, reader) {
  this.index = index;
  this.reader = reader;
  this.el = $(element);
  this.offset = 0;
  this.height = 0;

  $document.on('mousedown.reader-chapter', '#' + this.el.attr('id'), function(event) {
    if(event.which === 1) {
      this.reader.showPage(this.index + 1);
    }
    else if(event.which === 3) {
      this.reader.showPage(this.index - 1);
    }
  }.bind(this));
}
ReaderPage.prototype.resize = function() {
  this.el.css({
    height  :  f3.h,
  });
};
ReaderPage.prototype.onResize = function() {
  this.offset = this.el.offset().top;
  this.height = this.el.height();
};

window.addEventListener('afterScreenChange', function(event) {
  if(event.detail.previousView === 'reader_chapter') {
    reader.stopChapter();
  }
  if(event.detail.currentView === 'reader_chapter') {
    reader.startChapter();
  }
});
