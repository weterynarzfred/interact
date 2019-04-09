/* exported startLoading stopLoading */

function startLoading(element) {
  element.append($('#proto .loading-icon').clone());
  setTimeout(function() {
    element.addClass('loading');
  }, 1);
}

function stopLoading(element) {
  element.removeClass('loading');
  setTimeout(function() {
    element.not('.loading').find('.loading-icon').remove();
  }, 300);
}
