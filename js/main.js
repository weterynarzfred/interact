const ajaxUrl = $('body').data('ajax-url');

function throttle(ms, callback) {
	var lastCall=0;
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
			timeout=setTimeout((function(a) {
				return function(){
					callback(a);
				}
			})(a), ms);
		}
	};
}

// update the DOM based on the ajax response
function handleFragments(data) {
	if(data.fragments !== void 0) {
		const f = data.fragments;
		for(const fragment in f) {
			switch(f[fragment].type) {
			case 'after':
				$(f[fragment].element).after(f[fragment].html);
				break;
			case 'append':
				$(f[fragment].element).append(f[fragment].html);
				break;
			case 'delete':
				$(f[fragment].element).remove();
				break;
			case 'update':
				$(f[fragment].element).html(f[fragment].html);
				break;
			case 'replace':
				$(f[fragment].element).after(f[fragment].html).remove();
				break;
			case 'location':
				window.history.pushState({}, '', f[fragment].url);
				break;
			case 'message':
				createMessage(f[fragment].html);
				break;
			case 'refresh':
				location.reload();
				break;
			}
		}
	}
}


// interact with the database through ajax
let currentQueryIds = {};
function doQuery(p) {
	if(p.data !== void 0) {
		const queryId = (currentQueryIds[p.data.action] === void 0)
			? 0 : currentQueryIds[p.data.action] + 1;
		currentQueryIds[p.data.action] = queryId;
		let request = $.ajax({
			method   : 'post',
			url      : ajaxUrl,
			data     : p.data,
			dataType : 'json',
		});
		request.done(function(data) {
			// prevent calling an action if a more recent query was issued
			if(currentQueryIds[p.data.action] === queryId) {
				if(p.filter !== void 0) p.filter(data);
				handleFragments(data);
				if(p.callback !== void 0) p.callback(data);
			}
		});
	}
}


// redirecting forms to use AJAX
$(document).on('submit', '.ajax-form', function(e) {
  e.preventDefault();

  const t = $(this);
  const formData = new FormData(this);
  const action = t.data('form-action');
  // data to be submitted
  let values = {};

  t.find('input').map(function(i, e) {
		const $e = $(e);
		const name = $e.attr('name');
		values[name] = $e.val();
	});

  doQuery({
    data  : {
      action,
      values,
    }
  });

});


// adding entries
$(document).on('click', '.add-entry', function() {
	doQuery({
    data  : {
      action  : 'add_entry',
    }
  });
});


// removing entries
$(document).on('click', '.remove-entry', function() {
	const id = $(this).parents('.entry').data('id');
	doQuery({
    data  : {
      action  : 'remove_entry',
			values	:	{id},
    }
  });
});


// navigation links
$(document).on('click', '.navigation-link', function() {
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
		filter	:	function() {
			window.dispatchEvent(
				new CustomEvent('beforeNavigation', {detail:{name, value}})
			);
		},
		callback	:	function() {
			window.dispatchEvent(
				new CustomEvent('afterNavigation', {detail:{name, value}})
			);
		},
  });
});
