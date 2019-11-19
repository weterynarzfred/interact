/* exported createMessage */

const createMessage = (function() {
  const messages = $('#messages');
  class Message {
    constructor(html) {
      this.html = html;
      console.log(html);
      
      this.el = $(document.createElement('div'))
        .addClass('message')
        .html(this.html)
        .appendTo(messages);

      // setTimeout(function () {
      //   this.el.addClass('removing');
      //   setTimeout(function () {
      //     this.el.remove();
      //   }.bind(this), 300);
      // }.bind(this), 10000);
    }
  }
  return function(html) {
    return new Message(html);
  };
})();
