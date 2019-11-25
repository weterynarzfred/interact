const scrapeMadokamiList = [];

$('.single-entry').map(function() {
  const t = $(this);
  if (t.parents('.entry-is-finished').length === 0) {
    const details = t.data('details');
    if (details.madokami_last_check < new Date().getTime() / 1000 - 24 * 60 * 60) {
      scrapeMadokamiList.push(details.entry);
    }
  }
});

if (scrapeMadokamiList.length > 0) {
  shuffleArray(scrapeMadokamiList);
  scrapeMadokami();
  createMessage('checking madokami');
}

function scrapeMadokami() {
  if (scrapeMadokamiList.length > 0) {
    let id = scrapeMadokamiList.pop();
    doQuery({
      data: {
        action: 'madokami_scrape',
        values: {id},
      },
      callback: function() {
        setTimeout(scrapeMadokami, 500);
      },
    });
  }
  else {
    createMessage('finished checking madokami');
  }
}
