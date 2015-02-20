List of things to do:
* Create a base config.ini where to select the desired behaviour -> mostly done
* Integrate it with composer
* Add a initializer script, that can be used by the user as a human understandable configuration setting
* In config.ini add control version configurations (git vs. svn)
* Add exclude-from-revisions, so the devs can exclude tables from being integrated, like a .gitignore for DB, LOL
* Similar to .sqlignore but 
* If it's a WP based project, we should change paths and absolute URLs
* Add behaviours for Flyway and Laravel migrations (though, needs to be said, this should work by itself without these tools)
* Task Grunt, gulp