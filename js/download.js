function download(id, url) {
	doQuery({
		data	:	{
			action	:	'download',
			values	:	{id, url},
		},
		timeout	:	0,
	});
	getView('part/download_progress', '.next-message', {entry: id, url});
}
