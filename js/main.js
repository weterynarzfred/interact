const ajaxUrl = $('body').data('ajax-url');
const $window = $(window);
const $document = $(document);

let currentScreen = 0;
let currentView = $('.container').addClass('current').data('view');
