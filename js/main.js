/*
exported
  ajaxUrl
  $window
  $document
  $html
  currentScreen
  currentView
  currentZoom
  viewScrollPositions
*/

const ajaxUrl = $('body').data('ajax-url');
const $window = $(window);
const $document = $(document);
const $html = $('html');

let currentScreen = 0;
let currentView = $('.container').addClass('current').data('view');
let currentZoom = 1;
const viewScrollPositions = [0];
