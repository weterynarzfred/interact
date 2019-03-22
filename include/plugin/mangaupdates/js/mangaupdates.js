const mangaupdatesLastUpdate = $('#mangaupdates-data').data('last-update');
let idElements = [];
if(mangaupdatesLastUpdate < new Date().getTime()/1000 - 3600) {
	$('.entry-mangaupdates-data').map(function() {
		const t = $(this);
		if(t.parents('.entry-is-finished').length === 0) {
			idElements.push({
				entry	:	t.data('entry-id'),
				mangaupdates	:	t.data('mangaupdates-id'),
			});
		}
		t.remove();
	});
	shuffleArray(idElements);
	check_mangaupdates();
}

function check_mangaupdates() {
	if(idElements.length > 0) {
		const element = idElements.pop();
		doQuery({
			data	:	{
				action	:	'check_mangaupdates',
				values	:	[element],
			},
			callback	:	function(data) {
				if(data.success) {
					setTimeout(check_mangaupdates, 5000);
				}
			},
		});
	}
}
