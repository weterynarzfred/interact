/* exported updateEntry */

function updateEntry(id, values) {
  values.id = id;
  doQuery({
    data	:	{
      action	:	'update_entry',
      values,
    },
  });
}
