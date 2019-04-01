function previousView() {
	if(currentScreen > 0) {
		currentScreen--;
		$('#content-bar-offset').css({marginLeft:(-currentScreen * 100) + '%'});
		$('.container').eq(currentScreen).addClass('current');
		$('.container').each(function(index) {
			if(index > currentScreen) {
				$(this).removeClass('current');
				setTimeout(function() {
					$(this).remove();
				}.bind(this), 500);
			}
		});
	}
}

function getView(view, target, details, callback) {
	window.dispatchEvent(
		new CustomEvent('beforeGetView', {detail:{view, target, details}})
	);
	doQuery({
		data  : {
			action  : 'get_view',
			values	:	{
				view,
				target,
				details,
			},
		},
		callback	:	function(data) {
			window.dispatchEvent(
				new CustomEvent('afterGetView', {detail:{view, target, details, data}})
			);
			if(callback !== void 0) callback.call(this, view, target, details, data);
		},
	});
}

let currentScreen = 0;

window.addEventListener('beforeGetView', function(event) {
	if(event.detail.target === '.next-container') {
		startLoading($('.container'));
		const nextContainer = $(document.createElement('div'))
			.addClass('next-container');
		$('#content-bar').append(nextContainer);
	}
	else {
		startLoading($(event.detail.target));
	}
});

window.addEventListener('afterGetView', function(event) {
	if(event.detail.target === '.next-container') {
		currentScreen++;
		$('#content-bar-offset').css({marginLeft:(-currentScreen * 100) + '%'});
		$('.container').removeClass('current').eq(currentScreen).addClass('current');

		stopLoading($('.container.loading'));
	}
	else if(event.detail.view === 'part/download_progress') {
		const target = $(event.detail.target);
		if(target.data('percentage') > 0) {
			target
				.siblings('.progress')
				.css({width: target.data('percentage') + '%'});
		}
	}
	window.dispatchEvent(new CustomEvent("LayoutChange"));
	window.dispatchEvent(new CustomEvent("afterLayoutChange"));
});

$('.container').addClass('current');
