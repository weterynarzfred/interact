# interact
Manga tracker and reader. Requires PHP and MySQL.

## Clientside functions
// todo

## Serverside globals
`HOME_DIR`

## Serverside functions

### `set_option({string} $name, {mixed} $value, {bool} $save = true)`
defined in: `include/function/Options.php`

Sets a global option to a value.

| parameter | default | description |
| - | - | - |
| *string* `$name` | *required* | name of the option |
| *mixed* `$value` | *required* | new value for the option |
| *bool* `$save` | `true` | should the option be saved to the database |

Known options:

| name | in DB | type | description |
| - | - | - | - |
| `entry_properties` | &cross; | *array[string, bool]* | Array of entry properties loaded from the database and if they should be displayed on the options page. |
| `db_host` | &cross; | *string* | |
| `db_name` | &cross; | *string* | |
| `db_user` | &cross; | *string* | |
| `db_password` | &cross; | *string* | |
| `madokami_user` | &cross; | *string* | |
| `madokami_password` | &cross; | *string* | |
| `scripts` | &cross; | *array[string]* | Array of javascript files to load. |
| `manga_url` | &check; | *string* | |
| `7z_path` | &check; | *string* | |

## Serverside Hooks

Hook callbacks take two arguments. First one gets transformed and returned by the callback. Second one is discarded after use.

### `init`
called in:
- `include/config.php`

Called after initialization finishes. It does not pass any data.

### `get_prop_[prop_name]`
called in:
- `include/function/Entry.php`

  - `{mixed} property value,`
  - `{Entry} the entry the property belongs to`

Called when a property on an entry is accessed.

### `update_entry`
called in:
- `include/function/Entry.php`
  - `{array[mixed]} values to be updated`
  - `{Entry} the entry being updated`

Called when an entry is being updated.

### `get_option_[option_name]`
called in:
- `include/function/Options.php`
  - `{mixed} value of the option`
  - `{mixed} additional data`

Called when on option is being accessed.

### `set_option_[option_name]`
called in:
- `include/function/Options.php`

passed data:
- *{array}*
  - *{mixed}* `value` — value of the option
  - *{bool}* `save` — will the option be saved to the database

Called when on option is being saved.

### `display_view_urls`
called in:
- `include/function/view.php`
  - `{array[string]} urls of folders to be searched`

Called when searching for view template files.

### `ajax_urls`
called in:
- `include/init.php`
  - `{array[string]} urls of folders to be searched`

Called when searching for files handling ajax requests.

### `before_entry_list`
called in:
- `view/part/entry_list.php`
  - `{Array[Entry]} list of entries being displayed`

Called before entry archive is displayed.

### `after_single_entry`
called in:
- `view/part/single_entry.php`
  - `{Entry} entry being displayed`

Called after single entry was displayed in the archive.

### `after_single_entry_progress`
called in:
- `view/part/single_entry_progress.php`
  - `{Entry} entry being displayed`

Called after entry progress was displayed.

### `after_single_entry_reader`
called in:
- `view/reader.php`
  - `{NULL}`
  - `{Array[mixed]}`
    - `{Entry} entry being displayed`
    - `{Array[Array[string]]}` array containing urls and names of downloaded chapters

Called after entry chapter list was displayed.
