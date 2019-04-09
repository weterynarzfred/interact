/* exported previousView getView */

function previousView() {
  if (currentScreen > 0) {
    currentScreen--;
    const previousView = currentView;
    currentView = $('.container')
      .eq(currentScreen)
      .addClass('current')
      .data('view');
    window.dispatchEvent(new CustomEvent('afterScreenChange', {detail: {
      direction	:	-1,
      previousView,
      currentView,
    }}));

    $('#content-bar-offset').css({marginLeft:(-currentScreen * 100) + '%'});
    $('.container').each(function(index) {
      if (index > currentScreen) {
        $(this).removeClass('current');
        setTimeout(function() {
          $(this).remove();
        }.bind(this), 500);
      }
    });
  }
}

function getView(view, target, details, callback) {
  window.dispatchEvent(new CustomEvent('beforeGetView', {detail:{view, target, details}}));
  doQuery({
    data  : {
      action  : 'get_view',
      values	:	{
        view,
        target,
        details,
      },
    },
    callback	:	function(data) {
      window.dispatchEvent(new CustomEvent('afterGetView', {detail:{view, target, details, data}}));
      if (callback !== void 0) callback.call(this, view, target, details, data);
    },
  });
}

// prepare for new content
window.addEventListener('beforeGetView', function(event) {
  // add a new container for a new view
  if (event.detail.target === '.next-container') {
    startLoading($('.container'));
    $(document.createElement('div'))
      .addClass('next-container')
      .appendTo('#content-bar');
  }
  else {
    startLoading($(event.detail.target));
  }
});

// process newly added content
window.addEventListener('afterGetView', function(event) {
  // change current container
  if (event.detail.target === '.next-container') {
    currentScreen++;
    const previousView = currentView;
    currentView = $('.container')
      .removeClass('current')
      .eq(currentScreen)
      .addClass('current')
      .data('view');
    window.dispatchEvent(new CustomEvent('afterScreenChange', {detail: {
      direction	:	1,
      previousView,
      currentView,
    }}));
    $('#content-bar-offset').css({marginLeft:(-currentScreen * 100) + '%'});
    stopLoading($('.container.loading'));
  }
  // update download progress bar
  else if (event.detail.view === 'part/download_progress') {
    const target = $(event.detail.target);
    if (target.data('percentage') > 0) {
      target
        .siblings('.progress')
        .css({width: target.data('percentage') + '%'});
    }
  }
  window.dispatchEvent(new CustomEvent('LayoutChange'));
  window.dispatchEvent(new CustomEvent('afterLayoutChange'));
});
