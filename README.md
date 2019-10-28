# interact

## Hooks

Hook callbacks take two arguments. First one gets transformed and returned by the callback. Second one is discarded.

### init

called in:
- `include/config.php`

Called after initialization finishes. It does not pass any data.

### get_prop_[prop_name]

called in:
- `include/function/Entry.php`

  - `{mixed} property value,`
  - `{Entry} the entry the property belongs to`

Called when a property on an entry is accessed.

### update_entry

called in:
- `include/function/Entry.php`
  - `{array[mixed]} values to be updated`
  - `{Entry} the entry being updated`

Called when an entry is being updated.

### get_option_[option_name]

called in:
- `include/function/Options.php`
  - `{mixed} value of the option`
  - `{mixed} additional data`

Called when on option is being accessed.

### display_view_urls

called in:
- `include/function/view.php`
  - `{array[string]} urls of folders to be searched`

Called when searching for view template files.

### ajax_urls

called in:
- `include/init.php`
  - `{array[string]} urls of folders to be searched`

Called when searching for files handling ajax requests.

### after_single_entry_progress

called in:
- `view/part/single_entry_progress.php`
  - `{Entry} entry being displayed`

Called after entry progress was displayed.