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

function lazyCakesResize() {
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
	window.addEventListener("ajaxRequestDone", function(event) {
		if(event.detail.data.fragments !== void 0) {
			if(event.detail.data.fragments.length !== 0) {
				for(var f in event.detail.data.fragments) {
					if(event.detail.data.fragments.hasOwnProperty(f)) {
						if(event.detail.data.fragments[f].element !== void 0) {
							if(event.detail.data.fragments[f].element === '.next-container') {
								event.detail.data.fragments[f].element = '.container.current';
							}
							$(event.detail.data.fragments[f].element).find('.lazy-cake').map(function(i, e) {
								lazyCakes.push(new lazyCake(e));
							});
						}
					}
				}
			}
		}
	});
	lazyCakesResize();
});
})();
