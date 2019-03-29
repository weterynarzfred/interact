if(typeof f3 === 'undefined') {
  window.f3 = {
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
      window.dispatchEvent(new CustomEvent("layoutChange"));
      window.dispatchEvent(new CustomEvent("afterLayoutChange"));
    }
  };
  $(window).scroll(throttle(16, f3.scrollCheck));
  $(window).resize(throttle(100, f3.sizeCheck));
  $(window).load(f3.sizeCheck);
  $(document).ready(f3.sizeCheck);
}

function throttle(ms, callback) {
	var lastCall = 0;
	var timeout;
	return function(a) {
		var now = new Date().getTime(),
			diff = now - lastCall;
		if(diff >= ms) {
			lastCall = now;
			callback(a);
		} else {
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

const ajaxUrl = $('body').data('ajax-url');
let currentView = 'home';

function shuffleArray(array) {
  for (let i = array.length - 1; i > 0; i--) {
    const j = Math.floor(Math.random() * (i + 1));
    [array[i], array[j]] = [array[j], array[i]];
  }
}

// interact with the database through ajax
const doQuery = (function() {
	let currentQueryIds = {};

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

	return function(p) {
		if(p.data !== void 0) {
			const queryId = (currentQueryIds[p.data.action] === void 0) ?
			0 : currentQueryIds[p.data.action] + 1;
			currentQueryIds[p.data.action] = queryId;
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
				if(currentQueryIds[p.data.action] === queryId) {
					if(p.filter !== void 0) data = p.filter(data);
					handleFragments(data);
					if(p.callback !== void 0) p.callback(data);
				}
				window.dispatchEvent(
					new CustomEvent('ajaxRequestDone', {
						detail:{
							data:data,
							values:p.data.values,
						}
					})
				);
			});
			return request;
		}
		return false;
	};
})();

{
	// redirecting forms to use AJAX
	$(document).on('submit', '.ajax-form', function(e) {
	  e.preventDefault();
	  const t = $(this);
	  const action = t.data('form-action');
		const details = t.data('details');
	  let values = {};
		startLoading(t);
	  t.find('input').map(function(i, e) {
			const $e = $(e);
			const name = $e.attr('name');
			values[name] = $e.val();
		});
	  doQuery({
	    data  : {
	      action,
	      values,
	    },
			callback	: (function(action, details, values, t) {
				return function() {
					stopLoading(t);
					window.dispatchEvent(
						new CustomEvent('afterFormSubmit_'+action, {detail:{details, values}})
					);
				}
			})(action, details, values, t),
	  });
	})
	// adding entries
	.on('click', '.add-entry', function() {
		doQuery({
	    data  : {
	      action  : 'add_entry',
	    }
	  });
	})
	// removing entries
	.on('click', '.remove-entry', function() {
		const id = $(this).data('id');
		doQuery({
	    data  : {
	      action  : 'remove_entry',
				values	:	{id},
	    }
	  });
	})
	// navigation links
	.on('click', '.navigation-link', function() {
		const name = $(this).data('target');
		const value = $(this).data('value');
		doQuery({
	    data  : {
	      action  : 'display_view',
				values	:	{
					name,
					value,
				},
	    },
			filter	:	(function(name) { return function(data) {
				if(data.success) {
					currentView = name;
				}
				window.dispatchEvent(
					new CustomEvent('beforeNavigation', {detail:{name, value}})
				);
				return data;
			};})(name),
			callback	:	function() {
				window.dispatchEvent(
					new CustomEvent('afterNavigation', {detail:{name, value}})
				);
			},
	  });
	})
	// get view links
	.on('click', '.get-view', function() {
		const view = $(this).data('view');
		const target = $(this).data('target');
		const details = $(this).data('details');
		getView(view, target, details);
	})
	.on('click', '.return', function() {
		previousView();
	});
}
