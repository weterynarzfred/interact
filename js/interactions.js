// redirect forms to use AJAX
$document.on('submit', '.ajax-form', function(e) {
  e.preventDefault();
  const t = $(this);
  const action = t.data('form-action');
  const details = t.data('details');
  let values = {};
  startLoading(t);
  t.find('input').map(function(i, e) {
    const $e = $(e);
    const name = $e.attr('name');
    values[name] = $e.val();
  });
  doQuery({
    data  : {
      action,
      values,
    },
    callback	: (function(action, details, values, t) {
      return function(data) {
        stopLoading(t);
        window.dispatchEvent(new CustomEvent(
          'afterFormSubmit_' + action,
          {detail: {details, values, data}}
        ));
      };
    })(action, details, values, t),
  });
});

// trigger return on esc key and return button
$window.on('keydown', function(e) {
  if (e.which === 27) {
    previousView();
  }
});
$document.on('click', '.return', function() {
  previousView();
});

// get-view links
$document.on('click', '.get-view', function() {
  const $this = $(this);
  const view = $this.data('view');
  const target = $this.data('target');
  const details = $this.data('details');
  getView(view, target, details);
});

// delete entry
$(document).on('click', '.delete-button', function() {
  const id = $(this).parents('.view').data('entry');
  doQuery({
    data: {
      action: 'delete_entry',
      values: {
        id,
      },
    },
  });
  previousView();
});

// stop propagation on buttons
$document.on('click', '.button', function(event) {
  event.stopPropagation();
});

// show hidden sections
$document.on('click', '.show-more', function() {
  $($(this).toggleClass('active').data('target')).slideToggle(300);
});

// download from madokami
$document.on('click', '.madokami-file', function() {
  const t = $(this);
  download(t.data('id'), t.data('url'), t.data('file-slug'));
});


// reader chapter page size
$document.on('click', '.reader-chapter-plus', function() {
  currentZoom *= 1.1;
  window.dispatchEvent(new CustomEvent('layoutChange'));
  window.dispatchEvent(new CustomEvent('afterLayoutChange'));
});
$document.on('click', '.reader-chapter-minus', function() {
  currentZoom /= 1.1;
  window.dispatchEvent(new CustomEvent('layoutChange'));
  window.dispatchEvent(new CustomEvent('afterLayoutChange'));
});
