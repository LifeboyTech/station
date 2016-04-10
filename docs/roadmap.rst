Roadmap & Todos
===============

There are still a few missing pieces from our transition from Laravel 4 to 5:

* Fix emailers (upgrade to L5)
* Replace `Session::*` usage from Laravel 4
* Replace `Request::*` usage from Laravel 4
* Replace `->lists()` - convert to array - this now returns an object in L5
* Add user self-edit, self-password-changer
* Tag final production release and change install instructions to use that tag.