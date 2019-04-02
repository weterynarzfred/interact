// interact with the database through ajax
const doQuery = (function() {
	let currentQueryIds = [];

	// update the DOM based on the ajax response
	function handleFragments(data) {
		if(data.fragments !== void 0) {
			const f = data.fragments;
			for(const fragment in f) {
				handleFragment(f[fragment]);
			}
		}
	}

	function handleFragment(fragment) {
		const target = $(fragment.element);
		if(fragment.element !== void 0 && target.length === 0) {
			if(fragment.onEmpty !== void 0) {
				handleFragment(fragment.onEmpty);
			}
		}
		else {
			switch(fragment.type) {
			case 'after':
				target.after(fragment.html);
				break;
			case 'append':
				target.append(fragment.html);
				break;
			case 'delete':
				target.remove();
				break;
			case 'update':
				target.html(fragment.html);
				break;
			case 'replace':
				target.after(fragment.html).remove();
				break;
			case 'location':
				window.history.pushState({}, '', fragment.url);
				break;
			case 'message':
				createMessage(fragment.html);
				break;
			case 'refresh':
				location.reload();
				break;
			}
		}
	}

	function getQueryType(data) {
		if(data.action === 'get_view') return 'get_view_' + data.values.target;
		return data.action;
	}

	function getNextQueryId(data) {
		const type = getQueryType(data);
		for(var queryPair in currentQueryIds) {
			if(currentQueryIds.hasOwnProperty(queryPair)) {
				if(currentQueryIds[queryPair].type === type) {
					return ++currentQueryIds[queryPair].id;
				}
			}
		}
		currentQueryIds.push({
			type, id: 0,
		});
		return 0;
	}

	function getLastQueryId(data) {
		const type = getQueryType(data);
		for(var queryPair in currentQueryIds) {
			if(currentQueryIds.hasOwnProperty(queryPair)) {
				if(currentQueryIds[queryPair].type === type) {
					return currentQueryIds[queryPair].id;
				}
			}
		}
		return false;
	}

	return function(p) {
		if(p.data !== void 0) {
			const queryId = getNextQueryId(p.data);
			let args = {
				method   : 'post',
				url      : ajaxUrl,
				data     : p.data,
				dataType : 'json',
			};
			if(p.timeout !== void 0) args.timeout = p.timeout;
			let request = $.ajax(args);
			request.done(function(data) {
				// prevent calling an action if a more recent query was issued
				if(getLastQueryId(p.data) === queryId) {
					if(p.filter !== void 0) data = p.filter(data);
					if(p.callbackBefore !== void 0) p.callbackBefore(data);
					handleFragments(data);
					if(p.callback !== void 0) p.callback(data);
		      window.dispatchEvent(new CustomEvent("layoutChange"));
		      window.dispatchEvent(new CustomEvent("afterLayoutChange"));
					window.dispatchEvent(
						new CustomEvent('ajaxRequestDone', {
							detail	:	{
								data	:	data,
								values	:	p.data.values,
							}
						})
					);
				}
			});
			return request;
		}
		return false;
	};
})();
