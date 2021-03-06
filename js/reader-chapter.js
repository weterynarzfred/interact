class Reader {
  constructor() {
    this.pages = [];
    this.isScrolling = false;
    this.resize = this.resize.bind(this);
  }

  startChapter() {
    this.view = $('.view-reader_chapter.current');
    this.entryId = this.view.data('entry');
    this.entryRead = $('.view-reader').data('read');
    this.chapter = this.view.data('chapter');
    this.view.find('.reader-page').map(function (index, page) {
      const readerPage = new ReaderPage(page, index, this);
      this.pages.push(readerPage);
    }.bind(this));
    this.resize();
    this.markCurrent();
    window.addEventListener('layoutChange', this.resize);
    $window.on('keydown.reader-chapter', function (event) {
      if (event.which === 40 || event.which === 39 || event.which === 32) {
        if (currentZoom === 1) {
          event.preventDefault();
          this.showPage(this.currentPage + 1);
        }
      }
      else if (event.which === 38 || event.which === 37 || event.which === 8) {
        if (currentZoom === 1) {
          event.preventDefault();
          this.showPage(this.currentPage - 1);
        }
      }
    }.bind(this));
    $window.on('contextmenu.reader-chapter', function (event) {
      event.preventDefault();
      return false;
    });
    $window.on('scroll.reader-chapter', throttle(100, function () {
      if (currentView !== 'reader_chapter')
        return;
      if (this.isScrolling)
        return;
      for (const readerPage of this.pages) {
        if (readerPage.offset > window.scrollY - 10) {
          if (this.currentPage != readerPage.index) {
            this.currentPage = readerPage.index;
            updateEntry(this.entryId, { last_read_page: this.currentPage });
          }
          break;
        }
      }
    }.bind(this)));
  }

  markCurrent() {
    const lastReadChapter = $('.reader-file.last-read .reader-filename').text();
    if (this.chapter != lastReadChapter) {
      this.currentPage = 0;
      updateEntry(this.entryId, {
        last_read_chapter: this.chapter,
        last_read_page: 0,
      });
    }
    else {
      this.currentPage = this.view.data('last-read-page');
      if (this.currentPage === undefined)
        this.currentPage = 0;
      else
        this.showPage(this.currentPage, 500);
    }
  }

  stopChapter() {
    if (this.currentPage === this.pages.length - 1) {
      if (this.chapter > this.entryRead) {
        updateEntry(this.entryId, { read: this.chapter });
        $('.view-reader').data({ read: this.chapter });
      }
    }
    this.chapter = undefined;
    this.pages = [];
    window.removeEventListener('layoutChange', this.resize);
    $('.reader-page').off('.reader-chapter');
    $document.off('.reader-chapter');
    $window.off('.reader-chapter');
  }

  showPage(index, time) {
    if (this.pages[index] !== undefined) {
      if (time === undefined)
        time = 200;
      this.isScrolling = true;
      this.currentPage = index;
      updateEntry(this.entryId, { last_read_page: this.currentPage });
      const target = this.pages[index].offset;
      $html.stop().animate({ scrollTop: target }, time, function () {
        this.isScrolling = false;
      }.bind(this));
    }
  }

  resize() {
    this.pages.map(function (page) {
      page.resize();
    });
    this.pages.map(function (page) {
      page.onResize();
    });
  }
}

class ReaderPage {
  constructor(element, index, reader) {
    this.index = index;
    this.reader = reader;
    this.el = $(element);
    this.offset = 0;
    this.height = 0;
    this.el.on('mousedown.reader-chapter', function (event) {
      if (event.which === 1) {
        this.reader.showPage(this.index + 1);
      }
      else if (event.which === 3) {
        this.reader.showPage(this.index - 1);
      }
    }.bind(this));
  }

  resize() {
    this.el.css({
      height: f3.h * currentZoom,
    });
  }

  onResize() {
    this.offset = this.el.offset().top;
    this.height = this.el.height();
  }
}

const reader = new Reader();

window.addEventListener('afterScreenChange', function(event) {
  if (event.detail.previousView === 'reader_chapter') {
    reader.stopChapter();
  }
  if (event.detail.currentView === 'reader_chapter') {
    reader.startChapter();
  }
});
