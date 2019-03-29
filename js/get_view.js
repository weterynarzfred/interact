const getView = function(view, target, details) {
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
		callback	:	function() {
			window.dispatchEvent(
				new CustomEvent('afterGetView', {detail:{view, target, details}})
			);
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
});

function previousView() {
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
