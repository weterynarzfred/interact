/* exported createMessage */
/* exported clearMessages */

const createMessage = (function() {
  return function(html, meta) {
    if (meta === 'page_update') return;
    const el = $(document.createElement('div'))
      .addClass('message')
      .html(html)
      .appendTo('#messages')
      .click(() => {
        el.slideUp(300);
        setTimeout(() => el.remove(), 300);
      });
  };
})();

const clearMessages = () => {
  const messages = $('.message');
  messages.slideUp(300);
  setTimeout(() => messages.remove(), 300);
};

window.addEventListener('afterGetView', clearMessages);
