/* exported createMessage */

const createMessage = (function(){
  const messages = $('#messages');
  function Message(html) {
    this.html = html;
    this.el = $(document.createElement('div'))
      .addClass('message')
      .html(this.html);
    messages.append(this.el);
    setTimeout(function() {
      this.el.addClass('removing');
      setTimeout(function() {
        this.el.remove();
      }.bind(this), 300);
    }.bind(this), 10000);
  }
  return function(html) {
    return new Message(html);
  };
})();
