function download(id, url, fileSlug) {
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
		readProgress.call(state, id, url, fileSlug);
	}
}

function readProgress(id, url, fileSlug) {
	getView(
		'part/download_progress',
		'.view-part-download_progress#progress-' + fileSlug,
		{entry: id, url},
		createReadProgressCallback(this, id, url, fileSlug)
	);
}

function createReadProgressCallback(state, id, url, fileSlug) {
	return function(view, target, details, data) {
		if(!state.isFinished) {
			setTimeout(readProgress.bind(state, id, url, fileSlug), 1000);
		}
		else {
			getView('part/reader_filelist', '.view-part-reader_filelist', {entry: id});
		}
	}
}
