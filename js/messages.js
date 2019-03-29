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

window.addEventListener('afterGetView', function(event) {
	if(event.detail.target === '.next-message') {
		console.log(event.detail);
		const messages = $('#messages');
		const el = $(document.createElement('div'))
      .addClass('message')
      .html(event.detail.data.fragments[0].html);
		messages.append(el);
	}
});
