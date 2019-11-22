const scrapeMadokamiList = [];

$('.single-entry').map(function() {
  const t = $(this);
  if (t.parents('.entry-is-finished').length === 0) {
    const details = t.data('details');
    console.log(details);
    if (details.madokami_last_check < new Date().getTime() / 1000 + 24 * 60 * 60) {
      scrapeMadokamiList.push(details.entry);
    }
  }
});

if (scrapeMadokamiList.length > 0) scrapeMadokami();

function scrapeMadokami() {
  let id = scrapeMadokamiList.pop();
  doQuery({
    data: {
      action: 'madokami_scrape',
      values: {id},
    },
    callback: function(data) {
      setTimeout(scrapeMadokami, 500);
    },
  });
}
