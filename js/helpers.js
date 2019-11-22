/* exported f3 throttle shuffleArray */
const f3 = {
  s: 0,
  h: 0,
  w: 0,
  scrollCheck: function() {
    f3.s = window.scrollY;
  },
  sizeCheck: function() {
    f3.h = $(window).height();
    f3.w = $(window).width();
    f3.scrollCheck();
    window.dispatchEvent(new CustomEvent('layoutChange'));
    window.dispatchEvent(new CustomEvent('afterLayoutChange'));
  },
};
$(window).scroll(throttle(16, f3.scrollCheck));
$(window).resize(throttle(100, f3.sizeCheck));
$(window).load(f3.sizeCheck);
$(document).ready(f3.sizeCheck);

/**
 * Returns a function that will call the callback unless the last call was called too recently.
 * @param {number} ms 
 * @param {function} callback 
 * @returns {function} function to be called
 */
function throttle(ms, callback) {
  var lastCall = 0;
  var timeout;
  return function(a) {
    var now = new Date().getTime(),
      diff = now - lastCall;
    if (diff >= ms) {
      lastCall = now;
      callback(a);
    }
    else {
      clearTimeout(timeout);
      timeout = setTimeout(
        (function(a) {
          return function() {
            callback(a);
          };
        })(a),
        ms
      );
    }
  };
}

/**
 * Randomizes the order within an array.
 * @param {any[]} array 
 */
function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [
      array[i],
      array[j],
    ] = [
      array[j],
      array[i],
    ];
  }
}
