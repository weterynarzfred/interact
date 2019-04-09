/* exported ajaxUrl $window $document $html currentScreen currentView viewScrollPositions */

const ajaxUrl = $('body').data('ajax-url');
const $window = $(window);
const $document = $(document);
const $html = $('html');

let currentScreen = 0;
let currentView = $('.container').addClass('current').data('view');
const viewScrollPositions = [0];
