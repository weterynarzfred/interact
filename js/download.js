function download(id, url) {
	const request = doQuery({
		data	:	{
			action	:	'download',
			values	:	{id, url},
		},
		timeout	:	0,
	});
	if(request) {
		let state = {isFinished: false};
		request.done(function() {
			state.isFinished = true;
		});
		readProgress.call(state, id, url);
	}
}

function readProgress(id, url) {
	getView(
		'part/download_progress',
		'.view-part-download_progress',
		{entry: id, url},
		(function(state, id, url) {
			return function(view, target, details, data) {
				if(!state.isFinished) {
					setTimeout(readProgress.bind(state, id, url), 1000);
				}
				else {
					getView('part/reader_filelist', '.view-part-reader_filelist', {entry: id});
				}
			}
		})(this, id, url)
	);
}
