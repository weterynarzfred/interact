function download(id, url, fileSlug) {
  const request = doQuery({
    data  :  {
      action  :  'download',
      values  :  {id, url},
    },
    timeout  :  0,
  });
  if(request) {
    let state = {isFinished: false};
    request.done(function() {
      state.isFinished = true;
    });
    readDownloadProgress(state, id, url, fileSlug);
  }
}

function readDownloadProgress(state, id, url, fileSlug) {
	const view = 'part/download_progress';
	const target = '.view-part-download_progress#progress-' + fileSlug;
	const callback = createReadDownloadProgressCallback(state, id, url, fileSlug);
  getView(view, target, {entry: id, url}, callback);
}

function createReadDownloadProgressCallback(state, id, url, fileSlug) {
  return function(view, target, details, data) {
    if(!state.isFinished) {
      setTimeout(
				readDownloadProgress.bind(this, state, id, url, fileSlug),
				1000
			);
    }
    else {
      getView('part/reader_filelist', '.view-part-reader_filelist', {entry: id});
      getView('part/madokami_filelist', '.view-part-madokami_filelist', {
        entry  :  id,
        skip_check  :  true,
      });
    }
  };
}
