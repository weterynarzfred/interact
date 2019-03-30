const getView = function(view, target, details, callback) {
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
};

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
		$('#content-bar').css({marginLeft:(-currentScreen * 100) + '%'});
		stopLoading($('.container.loading'));
	}
	else if(event.detail.view === 'part/download_progress') {
		const target = $(event.detail.target);
		console.log(target.data('percentage'));
		target.siblings('.progress').css({width: target.data('percentage') + '%'});
	}
	window.dispatchEvent(new CustomEvent("LayoutChange"));
	window.dispatchEvent(new CustomEvent("afterLayoutChange"));
});

function previousView() {
	if(currentScreen > 0) {
		currentScreen--;
		$('#content-bar').css({marginLeft:(-currentScreen * 100) + '%'});
		$('.container').each(function(index) {
			if(index > currentScreen) {
				setTimeout(function() {
					$(this).remove();
				}.bind(this), 500);
			}
		});
	}
}
