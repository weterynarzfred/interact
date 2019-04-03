// lazy cakes
const lazyCakes = [];
function lazyCakesResize() {
	lazyCakes.map(function(e) {e.resize();});
	lazyCakesScroll();
}
function lazyCakesScroll() {
	lazyCakes.map(function(e) {e.check();});
}
const createLazyCake = (function() {
	function LazyCake(e) {
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
		t.resize();
	}

	return function(i, element) {
		const lazyCake = new LazyCake(element)
		lazyCakes.push(lazyCake);
		return lazyCake;
	}
})();

$window.load(function() {
	$('.lazy-cake').map(createLazyCake);

	$window.scroll(throttle(100, lazyCakesScroll));
	window.addEventListener("afterLayoutChange", lazyCakesResize);

	window.addEventListener("ajaxRequestDone", function(event) {
		const fragments = event.detail.data.fragments;
		if(fragments === void 0) return;
		if(fragments.length === 0) return;
		for(const f in fragments) {
			let element = fragments[f].element;
			if(element === void 0) continue;
			if(element === '.next-container') {
				element = '.container.current';
			}
			$(element).find('.lazy-cake').map(createLazyCake);
		}
	});

	lazyCakesResize();
});
