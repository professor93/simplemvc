# Simplemvc
Simple mvc example on PHP Language

---
Directory structure:
```
 app/          -     All codebase 
 app/configs   -     Configuration files
 app/core      -     Core Class files
 app/helpers   -     Helper files
 app/models    -     Model files
 app/routes    -     Route files (default route: route.php)
 app/views     -     View template files
```

Install:

`git clone https://github.com/professor93/simplemvc`

then:

`composer dump-autoload`

---
### Docs
- Route example:

```
Route::get($path, $callback, $name);
// $path -  string, Path for routing
// $callback - string|callback , Callback function or Controller action or maybe simple string
// $name - string, Name of Route, For alias and url generation

Route::get('/', 'MainController@index', 'main.url');

```

- Model example: 
```
// Select
User::find($id); // get one item from users table.
User::all(); // get all users

// Update
$user = User::find(1);
$user->name = 'John';
$user->save();

// Instert
$user = new User();
$user->name = 'Harry';
$user->email = 'henry.ford@gmail.com';
$user->save(); // returns created User object.

// Delete
$user = User::find(1);
$user->delete();
```