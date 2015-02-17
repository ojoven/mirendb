List of things to do:
* Create a base config.ini where to select the desired behaviour
* In config.ini add new configurations (git vs. svn)
* If it's a WP based project, we should change paths and absolute URLs
* Add a initializer script, that can be used by the user as a human understandable configuration setting
* Integrate it with composer
* Add exclude-from-revisions, so the devs can exclude tables from being integrated, like a .gitignore for DB, LOL
* Add behaviours for Flyway and Laravel migrations (though, needs to be said, this should work by itself without these tools)