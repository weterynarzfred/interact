// lazy cakes
(function() {
function lazyCake(e) {
	let t = this;
	t.el = $(e);
	t.cake = t.el.children('.cake');
	t.top = 0;
	t.isLoaded = false;
	t.src = t.el.data('bg');
	t.img = $(document.createElement('img')).addClass('lazy-cake-temp');
	t.el.append(t.cake).append(t.img);

	t.resize = function() {
		t.top = t.el.offset().top;
		t.check();
	};
	t.check = function() {
		if(t.top < f3.s + f3.h) {
			t.load();
		}
	};
	t.load = function() {
		console.log('load', this);
		if(!t.isLoaded && t.el.outerHeight() !== 0) {
			t.el.addClass('loading');
			t.img.attr({src:t.src}).load(function() {
				t.el.removeClass('loading');
				t.cake.css({
					backgroundImage: 'url('+t.src+')',
					opacity: 1,
				});
			});
			t.isLoaded = true;
		}
	};
}

function lazyCakesResize() {
	console.log(lazyCakes);
	lazyCakes.map(function(i, e) {e.resize();});
	lazyCakesScroll(lazyCakes);
}
function lazyCakesScroll() {
	lazyCakes.map(function(i, e) {e.check();});
}

let lazyCakes = [];
$(window).load(function() {
	lazyCakes = $('.lazy-cake').map(function(i, e) {
		return new lazyCake(e);
	});
	$(window).scroll(throttle(100, lazyCakesScroll));
	window.addEventListener("afterLayoutChange", lazyCakesResize);
	lazyCakesResize();
});
})();
